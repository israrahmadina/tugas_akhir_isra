<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\KelompokUsaha;
use App\Models\Role;

class AuthController extends Controller
{
    // =========================
    // TAMPILKAN LOGIN
    // =========================
    public function showLoginForm()
    {
        return view('login');
    }

    // =========================
    // PROSES LOGIN
    // =========================
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah',
        ]);
    }

    // =========================
    // PROSES REGISTER KELOMPOK USAHA
    // =========================
    public function register(Request $request)
    {
        // VALIDASI
        $request->validate([
            'nama'          => 'required|string|max:100',
            'email'         => 'required|email|unique:user,email',
            'password'      => 'required|min:6',
            'foto_profile'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'nama_usaha'    => 'required|string|max:225',
            'legalitas'     => 'nullable|string',
            'deskripsi'     => 'nullable|string',
            'provinsi'      => 'required|string',
            'provinsi_nama' => 'required|string',
            'kabupaten'     => 'required|string',
            'kabupaten_nama'=> 'required|string',
            'kecamatan'     => 'required|string',
            'kecamatan_nama'=> 'required|string',
            'desa'          => 'required|string',
            'desa_nama'     => 'required|string',
            'alamat_detail' => 'required|string',
        ]);

        // UPLOAD FOTO PROFIL
        $fotoPath = null;
        if ($request->hasFile('foto_profile')) {
            $fotoPath = $request->file('foto_profile')->store('foto_profile', 'public');
        }

        // CARI ROLE KELOMPOK USAHA
        $role = Role::where('role_name', 'Kelompok Usaha')->first();
        if (!$role) {
            return back()
                ->withErrors(['role' => 'Role Kelompok Usaha tidak ditemukan di sistem'])
                ->withInput();
        }

        // SIMPAN USER
        $user = User::create([
            'nama'         => $request->nama,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'role_id'      => $role->role_id,
            'foto_profile' => $fotoPath,
        ]);

        // SUSUN DATA ALAMAT JSON
        $alamat_json = json_encode([
            'provinsi'  => ['id' => $request->provinsi,  'nama' => $request->provinsi_nama],
            'kabupaten' => ['id' => $request->kabupaten, 'nama' => $request->kabupaten_nama],
            'kecamatan' => ['id' => $request->kecamatan, 'nama' => $request->kecamatan_nama],
            'desa'      => ['id' => $request->desa,      'nama' => $request->desa_nama],
            'detail'    => $request->alamat_detail,
        ]);

        // SIMPAN DATA KELOMPOK USAHA
        KelompokUsaha::create([
            'user_id'            => $user->user_id,
            'nama_usaha'         => $request->nama_usaha,
            'legalitas_perizinan'=> $request->legalitas,
            'deskripsi'          => $request->deskripsi,
            'alamat_lengkap'     => $alamat_json,
            // nip_penyuluh, lembaga_id, skema_id, produk_id
            // akan diisi oleh admin/penyuluh setelah verifikasi
            'skema_id'           => null,
            'produk_id'          => null,
        ]);

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silakan login dan tunggu verifikasi dari admin.');
    }

    // =========================
    // TAMPILKAN FORM PROFIL
    // =========================
    public function editProfile()
    {
        $user = auth()->user();
        return view('profile', compact('user'));
    }

    // =========================
    // UPDATE PROFIL
    // =========================
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'nama'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:user,email,' . $user->user_id . ',user_id',
            'foto_profile' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password'              => 'required|min:6|same:password_confirmation',
                'password_confirmation' => 'required',
            ]);
        }

        $data = [
            'nama'  => $request->nama,
            'email' => $request->email,
        ];

        // Handle upload foto profil
        if ($request->hasFile('foto_profile')) {
            if ($user->foto_profile) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->foto_profile);
            }
            $path = $request->file('foto_profile')->store('foto_profile', 'public');
            $data['foto_profile'] = $path;
        }

        // Handle hapus foto
        if ($request->boolean('hapus_foto') && $user->foto_profile) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->foto_profile);
            $data['foto_profile'] = null;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    // =========================
    // LOGOUT
    // =========================
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}