<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalPelaporan;
use App\Models\Komoditas;
use App\Models\User;
use Illuminate\Http\Request;

class JadwalPelaporanController extends Controller
{
    public function index()
    {
        $jadwals = JadwalPelaporan::with('userKphl', 'komoditas')->orderBy('tanggal_mulai', 'desc')->get();
        $kphls = User::whereHas('role', function($q) {
            $q->where('role_name', 'KPHL');
        })->get();
        $komoditas = Komoditas::all();

        return view('admin.index_jadwal', compact('jadwals', 'kphls', 'komoditas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id_kphl'    => 'required|exists:user,user_id',
            'komoditas_id'    => 'nullable|exists:komoditas,komoditas_id',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        JadwalPelaporan::create($request->only(['user_id_kphl', 'komoditas_id', 'tanggal_mulai', 'tanggal_selesai']));

        return redirect()->back()->with('success', 'Jadwal pelaporan berhasil dibuat');
    }

    public function update(Request $request, $id)
    {
        $jadwal = JadwalPelaporan::findOrFail($id);

        $request->validate([
            'user_id_kphl'    => 'required|exists:user,user_id',
            'komoditas_id'    => 'nullable|exists:komoditas,komoditas_id',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $jadwal->update($request->only(['user_id_kphl', 'komoditas_id', 'tanggal_mulai', 'tanggal_selesai']));

        return redirect()->back()->with('success', 'Jadwal pelaporan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $jadwal = JadwalPelaporan::findOrFail($id);
        $jadwal->delete();

        return redirect()->back()->with('success', 'Jadwal pelaporan berhasil dihapus');
    }
}
