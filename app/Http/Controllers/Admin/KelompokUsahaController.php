<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KelompokUsaha;
use App\Models\Lembaga;
use App\Models\Penyuluh;
use App\Models\Produk;
use App\Models\Role;
use App\Models\Skema;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class KelompokUsahaController extends Controller
{
    public function index()
    {
        $role = Role::where('role_name', 'Kelompok Usaha')->first();
        $users = User::where('role_id', $role->role_id)
            ->with(['kelompokUsaha.penyuluh.user', 'kelompokUsaha.skema', 'kelompokUsaha.produk'])
            ->get();

        $penyuluhs = Penyuluh::with('user')->get();
        $skemas    = Skema::all();
        $produks   = Produk::with('kategori')->get();
        $lembagas  = Lembaga::all();

        return view('admin.index_kelompok', compact('users', 'penyuluhs', 'skemas', 'produks', 'lembagas'));
    }

    public function store(Request $request)
    {
        $role = Role::where('role_name', 'Kelompok Usaha')->first();

        $request->validate([
            'nama'               => 'required|string|max:100',
            'email'              => 'required|email|max:100|unique:user',
            'password'           => 'required|string|min:6',
            'foto_profile'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'nama_usaha'         => 'required|string|max:225',
            'nip_penyuluh'       => 'nullable|exists:penyuluh,nip_penyuluh',
            'lembaga_id'         => 'nullable|exists:lembaga,lembaga_id',
            'skema_id'           => 'nullable|exists:skema,skema_id',
            'produk_id'          => 'nullable|exists:produk,produk_id',
            'legalitas_perizinan'=> 'nullable|string|max:50',
            'deskripsi'          => 'nullable|string',
            'alamat_detail'      => 'required|string',
            'provinsi'           => 'required|string',
            'provinsi_nama'      => 'required|string',
            'kabupaten'          => 'required|string',
            'kabupaten_nama'     => 'required|string',
            'kecamatan'          => 'required|string',
            'kecamatan_nama'     => 'required|string',
            'desa'               => 'required|string',
            'desa_nama'          => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $fotoPath = null;
            if ($request->hasFile('foto_profile')) {
                $fotoPath = $request->file('foto_profile')->store('foto_profile', 'public');
            }

            $user = User::create([
                'nama'         => $request->nama,
                'email'        => $request->email,
                'password'     => Hash::make($request->password),
                'role_id'      => $role->role_id,
                'foto_profile' => $fotoPath,
            ]);

            $alamat = json_encode([
                'provinsi'  => ['id' => $request->provinsi,  'nama' => $request->provinsi_nama],
                'kabupaten' => ['id' => $request->kabupaten, 'nama' => $request->kabupaten_nama],
                'kecamatan' => ['id' => $request->kecamatan, 'nama' => $request->kecamatan_nama],
                'desa'      => ['id' => $request->desa,      'nama' => $request->desa_nama],
                'detail'    => $request->alamat_detail,
            ]);

            KelompokUsaha::create([
                'user_id'             => $user->user_id,
                'nip_penyuluh'        => $request->nip_penyuluh ?: null,
                'lembaga_id'          => $request->lembaga_id ?: null,
                'skema_id'            => $request->skema_id ?: null,
                'produk_id'           => $request->produk_id ?: null,
                'nama_usaha'          => $request->nama_usaha,
                'legalitas_perizinan' => $request->legalitas_perizinan ?: null,
                'deskripsi'           => $request->deskripsi ?: null,
                'alamat_lengkap'      => $alamat,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Kelompok Usaha berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($fotoPath) Storage::disk('public')->delete($fotoPath);
            return redirect()->back()
                ->withErrors(['error' => 'Gagal: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $user     = User::findOrFail($id);
        $kelompok = KelompokUsaha::where('user_id', $id)->first();

        $request->validate([
            'nama'               => 'required|string|max:100',
            'email'              => 'required|email|max:100|unique:user,email,' . $id . ',user_id',
            'foto_profile'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'nama_usaha'         => 'required|string|max:225',
            'nip_penyuluh'       => 'nullable|exists:penyuluh,nip_penyuluh',
            'lembaga_id'         => 'nullable|exists:lembaga,lembaga_id',
            'skema_id'           => 'nullable|exists:skema,skema_id',
            'produk_id'          => 'nullable|exists:produk,produk_id',
            'legalitas_perizinan'=> 'nullable|string|max:50',
            'deskripsi'          => 'nullable|string',
            'alamat_detail'      => 'required|string',
            'provinsi'           => 'required|string',
            'provinsi_nama'      => 'required|string',
            'kabupaten'          => 'required|string',
            'kabupaten_nama'     => 'required|string',
            'kecamatan'          => 'required|string',
            'kecamatan_nama'     => 'required|string',
            'desa'               => 'required|string',
            'desa_nama'          => 'required|string',
        ]);

        $userData = [
            'nama'  => $request->nama,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:6']);
            $userData['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto_profile')) {
            if ($user->foto_profile) Storage::disk('public')->delete($user->foto_profile);
            $userData['foto_profile'] = $request->file('foto_profile')->store('foto_profile', 'public');
        }

        if ($request->boolean('hapus_foto') && $user->foto_profile) {
            Storage::disk('public')->delete($user->foto_profile);
            $userData['foto_profile'] = null;
        }

        DB::beginTransaction();
        try {
            $user->update($userData);

            $alamat = json_encode([
                'provinsi'  => ['id' => $request->provinsi,  'nama' => $request->provinsi_nama],
                'kabupaten' => ['id' => $request->kabupaten, 'nama' => $request->kabupaten_nama],
                'kecamatan' => ['id' => $request->kecamatan, 'nama' => $request->kecamatan_nama],
                'desa'      => ['id' => $request->desa,      'nama' => $request->desa_nama],
                'detail'    => $request->alamat_detail,
            ]);

            $kelompokData = [
                'nip_penyuluh'        => $request->nip_penyuluh ?: null,
                'lembaga_id'          => $request->lembaga_id ?: null,
                'skema_id'            => $request->skema_id ?: null,
                'produk_id'           => $request->produk_id ?: null,
                'nama_usaha'          => $request->nama_usaha,
                'legalitas_perizinan' => $request->legalitas_perizinan ?: null,
                'deskripsi'           => $request->deskripsi ?: null,
                'alamat_lengkap'      => $alamat,
            ];

            if ($kelompok) {
                $kelompok->update($kelompokData);
            } else {
                KelompokUsaha::create(array_merge($kelompokData, ['user_id' => $user->user_id]));
            }

            DB::commit();
            return redirect()->back()->with('success', 'Kelompok Usaha berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->foto_profile) {
            Storage::disk('public')->delete($user->foto_profile);
        }
        $user->delete();
        return redirect()->back()->with('success', 'Kelompok Usaha berhasil dihapus.');
    }
}
