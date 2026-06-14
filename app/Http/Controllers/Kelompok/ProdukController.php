<?php

namespace App\Http\Controllers\Kelompok;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\UsahaKelompok;
use App\Models\Komoditas;

class ProdukController extends Controller
{
    // =========================
    // HALAMAN LIST PRODUK
    // =========================
    public function index()
    {
        // AMBIL PRODUK KHUSUS KELOMPOK YANG SEDANG LOGIN
        $kelompok_id = auth()->user()->kelompokBinaan->kelompok_id;
        
        $produks = UsahaKelompok::with('komoditas.kategori')
            ->where('kelompok_id', $kelompok_id)
            ->get();

        return view('kelompok.produk', compact('produks'));
    }

    // =========================
    // FORM TAMBAH PRODUK
    // =========================
    public function create()
    {
        $komoditas = Komoditas::with('kategori')->get();

        return view('kelompok.tambah_produk', compact('komoditas'));
    }


    // SIMPAN PRODUK BARU

    public function store(Request $request)
    {
        // VALIDASI
        $request->validate([
            'nama_usaha' => 'required|string|max:100',
            'komoditas_id' => 'required|exists:komoditas,komoditas_id',
            'deskripsi' => 'nullable|string',
            'gambar_produk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'provinsi' => 'required|string',
            'provinsi_nama' => 'required|string',
            'kabupaten' => 'required|string',
            'kabupaten_nama' => 'required|string',
            'kecamatan' => 'required|string',
            'kecamatan_nama' => 'required|string',
            'desa' => 'required|string',
            'desa_nama' => 'required|string',
            'alamat_detail' => 'required|string',
        ]);

        // UPLOAD GAMBAR
        $path = null;
        if ($request->hasFile('gambar_produk')) {
            $path = $request->file('gambar_produk')->store('produk', 'public');
        }

        // SIMPAN ALAMAT KELOMPOK BINAAN
        $kelompok = auth()->user()->kelompokBinaan;
        $alamat_data = [
            'provinsi' => [
                'id' => $request->provinsi,
                'nama' => $request->provinsi_nama,
            ],
            'kabupaten' => [
                'id' => $request->kabupaten,
                'nama' => $request->kabupaten_nama,
                ],
            'kecamatan' => [
                'id' => $request->kecamatan,
                'nama' => $request->kecamatan_nama,
            ],
            'desa' => [
                'id' => $request->desa,
                'nama' => $request->desa_nama,
            ],
            'detail' => $request->alamat_detail,
        ];
        $alamat_json = json_encode($alamat_data);
        $kelompok->update(['alamat_lengkap' => $alamat_json]);

        // SIMPAN PRODUK
        UsahaKelompok::create([
            'kelompok_id' => $kelompok->kelompok_id,
            'komoditas_id' => $request->komoditas_id,
            'nama_usaha' => $request->nama_usaha,
            'deskripsi' => $request->deskripsi,
            'gambar_produk' => $path,
        ]);

        return redirect()->route('kelompok.produk')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    // =========================
    // FORM EDIT PRODUK
    // =========================
    public function edit($id)
    {
        $produk = UsahaKelompok::findOrFail($id);

        // Pastikan hanya bisa edit produk sendiri
        if ($produk->kelompok_id !== auth()->user()->kelompokBinaan->kelompok_id) {
            abort(403);
        }

        $komoditas = Komoditas::with('kategori')->get();

        return view('kelompok.edit_produk', compact('produk', 'komoditas'));
    }

    // =========================
    // UPDATE PRODUK
    // =========================
    public function update(Request $request, $id)
    {
        $produk = UsahaKelompok::findOrFail($id);

        // Pastikan hanya bisa update produk sendiri
        $kelompok = auth()->user()->kelompokBinaan;
        if ($produk->kelompok_id !== $kelompok->kelompok_id) {
            abort(403);
        }

        $request->validate([
            'nama_usaha' => 'required|string|max:100',
            'komoditas_id' => 'required|exists:komoditas,komoditas_id',
            'deskripsi' => 'nullable|string',
            'gambar_produk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'provinsi' => 'required|string',
            'provinsi_nama' => 'required|string',
            'kabupaten' => 'required|string',
            'kabupaten_nama' => 'required|string',
            'kecamatan' => 'required|string',
            'kecamatan_nama' => 'required|string',
            'desa' => 'required|string',
            'desa_nama' => 'required|string',
            'alamat_detail' => 'required|string',
        ]);

        // SIMPAN ALAMAT KELOMPOK BINAAN
        $alamat_data = [
            'provinsi' => [
                'id' => $request->provinsi,
                'nama' => $request->provinsi_nama,
            ],
            'kabupaten' => [
                'id' => $request->kabupaten,
                'nama' => $request->kabupaten_nama,
            ],
            'kecamatan' => [
                'id' => $request->kecamatan,
                'nama' => $request->kecamatan_nama,
            ],
            'desa' => [
                'id' => $request->desa,
                'nama' => $request->desa_nama,
            ],
            'detail' => $request->alamat_detail,
        ];
        $alamat_json = json_encode($alamat_data);
        $kelompok->update(['alamat_lengkap' => $alamat_json]);

        $data = [
            'komoditas_id' => $request->komoditas_id,
            'nama_usaha' => $request->nama_usaha,
            'deskripsi' => $request->deskripsi,
        ];

        if ($request->hasFile('gambar_produk')) {
            // Hapus gambar lama jika ada
            if ($produk->gambar_produk) {
                Storage::disk('public')->delete($produk->gambar_produk);
            }
            $data['gambar_produk'] = $request->file('gambar_produk')->store('produk', 'public');
        }

        $produk->update($data);

        return redirect()->route('kelompok.produk')
            ->with('success', 'Produk berhasil diupdate');
    }

    // =========================
    // HAPUS PRODUK
    // =========================
    public function destroy($id)
    {
        $produk = UsahaKelompok::findOrFail($id);

        // Pastikan hanya bisa hapus produk sendiri
        if ($produk->kelompok_id !== auth()->user()->kelompokBinaan->kelompok_id) {
            abort(403);
        }

        // Hapus gambar dari storage
        if ($produk->gambar_produk) {
            Storage::disk('public')->delete($produk->gambar_produk);
        }

        $produk->delete();

        return redirect()->route('kelompok.produk')
            ->with('success', 'Produk berhasil dihapus');
    }
}