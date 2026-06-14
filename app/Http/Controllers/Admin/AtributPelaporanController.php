<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AtributPelaporan;
use App\Models\Komoditas;
use Illuminate\Http\Request;

class AtributPelaporanController extends Controller
{
    public function index()
    {
        $atributs = AtributPelaporan::with('komoditas')->get();
        $komoditas = Komoditas::all();
        return view('admin.index_atribut', compact('atributs', 'komoditas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'komoditas_id' => 'nullable|exists:komoditas,komoditas_id',
            'nama_atribut' => 'required|string|max:50',
            'jenis_field' => 'required|in:text,number,image',
            'satuan' => 'nullable|string|max:50',
        ]);

        AtributPelaporan::create([
            'komoditas_id' => $request->komoditas_id,
            'nama_atribut' => $request->nama_atribut,
            'satuan' => $request->jenis_field === 'number' ? $request->satuan : null,
            'tipe_atribut' => 'produk',
            'jenis_field' => $request->jenis_field,
        ]);

        return redirect()->back()->with('success', 'Atribut berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'komoditas_id' => 'nullable|exists:komoditas,komoditas_id',
            'nama_atribut' => 'required|string|max:50',
            'jenis_field' => 'required|in:text,number,image',
            'satuan' => 'nullable|string|max:50',
        ]);

        $atribut = AtributPelaporan::findOrFail($id);
        $atribut->update([
            'komoditas_id' => $request->komoditas_id,
            'nama_atribut' => $request->nama_atribut,
            'satuan' => $request->jenis_field === 'number' ? $request->satuan : null,
            'jenis_field' => $request->jenis_field,
        ]);

        return redirect()->back()->with('success', 'Atribut berhasil diperbarui');
    }

    public function destroy($id)
    {
        $atribut = AtributPelaporan::findOrFail($id);
        $atribut->delete();

        return redirect()->back()->with('success', 'Atribut berhasil dihapus');
    }
}
