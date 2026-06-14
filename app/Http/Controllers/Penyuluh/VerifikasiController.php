<?php

namespace App\Http\Controllers\Penyuluh;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\RiwayatVerifikasi;
use App\Models\Notifikasi;
use App\Models\KelompokBinaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifikasiController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $penyuluh = $user->penyuluh;

        if (!$penyuluh) {
            return redirect()->back()->withErrors(['error' => 'Data Penyuluh tidak ditemukan.']);
        }

        // Ambil laporan dari kelompok yang dibina oleh penyuluh ini
        $pelaporans = Laporan::whereHas('kelompokBinaan', function($q) use ($penyuluh) {
                $q->where('penyuluh_id', $penyuluh->penyuluh_id);
            })
            ->with('kelompokBinaan', 'jadwal', 'details.nilais', 'buktis')
            ->where('status_verifikasi', 'pending') // Hanya yang pending
            ->orderBy('created_at', 'desc')
            ->get();

        return view('penyuluh.verifikasi', compact('pelaporans'));
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'catatan' => $request->status === 'rejected' ? 'required|string|max:200' : 'nullable|string|max:200'
        ]);

        $pelaporan = Laporan::findOrFail($id);

        // Map form values to database enum values
        $statusVerifikasiDb = $request->status === 'approved' ? 'diverifikasi' : 'ditolak';
        $statusRiwayatDb = $request->status === 'approved' ? 'diterima' : 'ditolak';

        DB::beginTransaction();
        try {
            // Update status di tabel laporan
            $pelaporan->update([
                'status_verifikasi' => $statusVerifikasiDb,
            ]);

            // Simpan riwayat verifikasi
            $riwayat = RiwayatVerifikasi::create([
                'laporan_id' => $pelaporan->laporan_id,
                'user_id' => auth()->user()->user_id,
                'status_verifikasi' => $statusRiwayatDb,
                'catatan_verifikasi' => $request->catatan,
                'status_validasi' => 'pending',
                'tanggal' => now(),
            ]);

            // Kirim notifikasi ke kelompok binaan
            $kelompok = $pelaporan->kelompokBinaan;
            if ($kelompok && $kelompok->user) {
                $pesan = $request->status === 'approved'
                    ? 'Laporan Anda telah diverifikasi oleh Penyuluh. Menunggu validasi KPHL...'
                    : 'Laporan Anda ditolak oleh Penyuluh. Alasan: ' . ($request->catatan ?? '-');
                
                Notifikasi::create([
                    'user_id' => $kelompok->user->user_id,
                    'riwayat_id' => $riwayat->riwayat_id,
                    'pesan' => $pesan,
                    'is_read' => false,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Laporan berhasil ' . ($request->status === 'approved' ? 'diverifikasi' : 'ditolak'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal melakukan verifikasi: ' . $e->getMessage()]);
        }
    }
}
