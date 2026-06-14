<?php

namespace App\Http\Controllers\Penyuluh;

use App\Http\Controllers\Controller;
use App\Models\RiwayatVerifikasi;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $penyuluh = $user->penyuluh;

        if (!$penyuluh) {
            return redirect()->back()->withErrors(['error' => 'Data Penyuluh tidak ditemukan.']);
        }

        // Ambil riwayat verifikasi dari laporan kelompok yang dibina penyuluh ini
        $riwayats = RiwayatVerifikasi::with('pelaporan.kelompokBinaan', 'pelaporan.jadwal', 'pelaporan.details', 'pelaporan.kelompokBinaan.penyuluh.user')
            ->whereHas('pelaporan.kelompokBinaan', function ($q) use ($penyuluh) {
                $q->where('penyuluh_id', $penyuluh->penyuluh_id);
            })
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('penyuluh.riwayat', compact('riwayats'));
    }
}
