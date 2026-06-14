<?php

namespace App\Http\Controllers\Kphl;

use App\Http\Controllers\Controller;
use App\Models\JadwalPelaporan;
use App\Models\Komoditas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = JadwalPelaporan::with('userKphl', 'komoditas')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        $jenisProduks = Komoditas::all();

        return view('kphl.jadwal', compact('jadwals', 'jenisProduks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'komoditas_id' => 'required|exists:komoditas,komoditas_id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'target_capaian' => 'nullable|numeric|min:0',
        ]);

        // Validasi target wajib jika komoditas butuh target
        $komoditas = Komoditas::find($request->komoditas_id);
        if ($komoditas && $komoditas->menggunakan_target && !$request->filled('target_capaian')) {
            return redirect()->back()
                ->withErrors(['target_capaian' => 'Komoditas ini wajib mengisi target capaian.'])
                ->withInput();
        }

        $data = $request->all();
        $data['user_id_kphl'] = Auth::user()->user_id;
        // Hapus target jika komoditas tidak menggunakan target
        if (!$komoditas || !$komoditas->menggunakan_target) {
            $data['target_capaian'] = null;
        }

        JadwalPelaporan::create($data);

        return redirect()->back()
            ->with('success', 'Jadwal pelaporan berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $jadwal = JadwalPelaporan::findOrFail($id);

        $request->validate([
            'komoditas_id' => 'required|exists:komoditas,komoditas_id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'target_capaian' => 'nullable|numeric|min:0',
        ]);

        // Validasi target wajib jika komoditas butuh target
        $komoditas = Komoditas::find($request->komoditas_id);
        if ($komoditas && $komoditas->menggunakan_target && !$request->filled('target_capaian')) {
            return redirect()->back()
                ->withErrors(['target_capaian' => 'Komoditas ini wajib mengisi target capaian.'])
                ->withInput();
        }

        $data = $request->all();
        if (!$komoditas || !$komoditas->menggunakan_target) {
            $data['target_capaian'] = null;
        }

        $jadwal->update($data);

        return redirect()->back()
            ->with('success', 'Jadwal pelaporan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $jadwal = JadwalPelaporan::findOrFail($id);

        $jadwal->delete();

        return redirect()->back()
            ->with('success', 'Jadwal pelaporan berhasil dihapus');
    }
}