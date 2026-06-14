<?php

namespace App\Http\Controllers\Penyuluh;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\KelompokBinaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenyuluhDashboardController extends Controller
{
    public function index()
    {
        $penyuluh = Auth::user()->penyuluh;
        
        if (!$penyuluh) {
            Auth::logout();
            return redirect('/login')->withErrors(['error' => 'Profil penyuluh tidak ditemukan.']);
        }

        $penyuluh_id = $penyuluh->penyuluh_id;

        // Total Kelompok Binaan
        $totalKelompok = KelompokBinaan::where('penyuluh_id', $penyuluh_id)->count();

        // Statistik Pelaporan
        $pelaporanQuery = Laporan::whereHas('kelompokBinaan', function ($query) use ($penyuluh_id) {
            $query->where('penyuluh_id', $penyuluh_id);
        });

        $pending = (clone $pelaporanQuery)->where('status_verifikasi', 'pending')->count();
        $verified = (clone $pelaporanQuery)->where('status_verifikasi', 'diverifikasi')->count();
        $rejected = (clone $pelaporanQuery)->where('status_verifikasi', 'ditolak')->count();

        // Pelaporan Terbaru
        $pelaporan = (clone $pelaporanQuery)->with('kelompokBinaan')
            ->latest()
            ->limit(10)
            ->get();

        return view('penyuluh.dashboard_penyuluh', compact(
            'totalKelompok', 
            'pending', 
            'verified', 
            'rejected', 
            'pelaporan'
        ));
    }
}
