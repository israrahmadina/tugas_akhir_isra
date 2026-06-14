<?php

namespace App\Http\Controllers\Kelompok;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\UsahaKelompok;
use App\Models\KelompokBinaan;
use App\Models\Notifikasi;
use App\Models\Penyuluh;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $kelompok = $user->kelompokBinaan;
        
        $stats = [
            'total_produk' => UsahaKelompok::where('kelompok_id', $kelompok->kelompok_id)->count(),
            'total_laporan' => Laporan::where('kelompok_id', $kelompok->kelompok_id)->count(),
            'laporan_bulan_ini' => Laporan::where('kelompok_id', $kelompok->kelompok_id)
              ->whereYear('created_at', date('Y'))
              ->whereMonth('created_at', date('m'))
              ->count(),
        ];

        $laporanTerbaru = Laporan::where('kelompok_id', $kelompok->kelompok_id)
            ->with('details.usaha', 'jadwal')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Ambil notifikasi terbaru
        $notifikasis = Notifikasi::where('user_id', $user->user_id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('kelompok.dashboard_kelompok', compact('stats', 'laporanTerbaru', 'kelompok', 'notifikasis'));
    }
}
