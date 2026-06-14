<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Komoditas;
use App\Models\KategoriKomoditas;
use Illuminate\Http\Request;

class KomoditasController extends Controller
{
    public function index()
    {
        $komoditas = Komoditas::with('kategori')->get();
        $kategori = KategoriKomoditas::all();
        return view('admin.index_komoditas', compact('komoditas', 'kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori_komoditas,kategori_id',
            'nama_komoditas' => 'required|string|max:255',
        ]);

        Komoditas::create([
            'kategori_id' => $request->kategori_id,
            'nama_komoditas' => $request->nama_komoditas,
            'menggunakan_target' => $request->boolean('menggunakan_target'),
        ]);

        return redirect()->back()->with('success', 'Komoditas berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori_komoditas,kategori_id',
            'nama_komoditas' => 'required|string|max:255',
        ]);

        $komo = Komoditas::findOrFail($id);
        $komo->update([
            'kategori_id' => $request->kategori_id,
            'nama_komoditas' => $request->nama_komoditas,
            'menggunakan_target' => $request->boolean('menggunakan_target'),
        ]);

        return redirect()->back()->with('success', 'Komoditas berhasil diperbarui');
    }

    public function destroy($id)
    {
        $komo = Komoditas::findOrFail($id);
        $komo->delete();

        return redirect()->back()->with('success', 'Komoditas berhasil dihapus');
    }
}
