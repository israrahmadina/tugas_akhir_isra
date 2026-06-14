<?php

namespace App\Http\Controllers\Kphl;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\RiwayatVerifikasi;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ValidasiController extends Controller
{
    public function index()
    {
        // Ambil laporan yang SUDAH diverifikasi oleh penyuluh dan sedang pending untuk validasi
        $pelaporans = Laporan::with('produk.kelompok', 'jadwal', 'nilais.atribut', 'buktis')
            ->where('status_verifikasi', 'diverifikasi')
            ->where('status_validasi', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('kphl.validasi', compact('pelaporans'));
    }

    public function validateReport(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'catatan' => $request->status === 'rejected' ? 'required|string|max:200' : 'nullable|string|max:200'
        ]);

        $pelaporan = Laporan::findOrFail($id);

        // Map form values to database enum values
        $statusValidasiDb = $request->status === 'approved' ? 'divalidasi' : 'ditolak';
        $statusRiwayatDb = $request->status === 'approved' ? 'diterima' : 'ditolak';

        DB::beginTransaction();
        try {
            // Update status di tabel laporan
            $pelaporan->update([
                'status_validasi' => $statusValidasiDb,
            ]);

            // Update riwayat verifikasi yang terkait dengan laporan ini
            $riwayat = RiwayatVerifikasi::where('laporan_id', $pelaporan->laporan_id)
                ->latest('created_at')
                ->first();

            if ($riwayat) {
                $riwayat->update([
                    'status_validasi' => $statusRiwayatDb,
                    'catatan_validasi' => $request->catatan,
                ]);
            } else {
                // Jika riwayat belum ada, buat baru
                $riwayat = RiwayatVerifikasi::create([
                    'laporan_id' => $pelaporan->laporan_id,
                    'user_id' => auth()->user()->user_id,
                    'status_verifikasi' => 'diterima',
                    'status_validasi' => $statusRiwayatDb,
                    'catatan_validasi' => $request->catatan,
                    'tanggal' => now(),
                ]);
            }

            // Kirim notifikasi ke kelompok binaan
            $kelompok = $pelaporan->kelompokBinaan;
            if ($kelompok && $kelompok->user) {
                $pesan = $request->status === 'approved'
                    ? 'Laporan Anda telah divalidasi oleh KPHL. Proses selesai.'
                    : 'Laporan Anda ditolak oleh KPHL. Alasan: ' . ($request->catatan ?? '-');
                
                Notifikasi::create([
                    'user_id' => $kelompok->user->user_id,
                    'riwayat_id' => $riwayat->riwayat_id,
                    'pesan' => $pesan,
                    'is_read' => false,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Laporan berhasil ' . ($request->status === 'approved' ? 'divalidasi' : 'ditolak'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal melakukan validasi: ' . $e->getMessage()]);
        }
    }
}
