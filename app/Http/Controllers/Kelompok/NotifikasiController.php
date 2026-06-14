<?php

namespace App\Http\Controllers\Kelompok;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $notifikasis = Notifikasi::where('user_id', $user->user_id)
            ->with('riwayatVerifikasi.pelaporan')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('kelompok.notifikasi', compact('notifikasis'));
    }

    public function markAsRead($id)
    {
        $notif = Notifikasi::where('notifikasi_id', $id)
            ->where('user_id', auth()->user()->user_id)
            ->firstOrFail();

        $notif->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Notifikasi telah ditandai dibaca.');
    }

    public function markAllAsRead()
    {
        Notifikasi::where('user_id', auth()->user()->user_id)
            ->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }
}
