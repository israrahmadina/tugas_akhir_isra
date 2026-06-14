<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skema;
use Illuminate\Http\Request;

class SkemaController extends Controller
{
    public function index()
    {
        $skemas = Skema::all();
        return view('admin.index_skema', compact('skemas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_skema' => 'required|string|max:225',
            'jenis_kelompok_binaan' => 'required|in:KPS,KTH',
        ]);

        Skema::create([
            'nama_skema' => $request->nama_skema,
            'jenis_kelompok_binaan' => $request->jenis_kelompok_binaan,
        ]);

        return redirect()->back()->with('success', 'Skema berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_skema' => 'required|string|max:225',
            'jenis_kelompok_binaan' => 'required|in:KPS,KTH',
        ]);

        $skema = Skema::findOrFail($id);
        $skema->update([
            'nama_skema' => $request->nama_skema,
            'jenis_kelompok_binaan' => $request->jenis_kelompok_binaan,
        ]);

        return redirect()->back()->with('success', 'Skema berhasil diperbarui');
    }

    public function destroy($id)
    {
        $skema = Skema::findOrFail($id);
        $skema->delete();

        return redirect()->back()->with('success', 'Skema berhasil dihapus');
    }
}
