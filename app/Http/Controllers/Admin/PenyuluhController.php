<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Penyuluh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PenyuluhController extends Controller
{
    public function index()
    {
        $users = User::with('penyuluh', 'role')
            ->whereHas('role', function ($query) {
                $query->where('role_name', 'Penyuluh');
            })
            ->get();

        return view('admin.index_penyuluh', compact('users'));
    }

    public function store(Request $request)
    {
        $role = Role::where('role_name', 'Penyuluh')->first();

        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:user',
            'password' => 'required|string|min:6',
            'foto_profile' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'nip_penyuluh' => 'required|string|max:10|unique:penyuluh,nip_penyuluh',
            'jabatan' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:15',
        ]);

        DB::beginTransaction();
        try {
            $fotoPath = null;
            if ($request->hasFile('foto_profile')) {
                $fotoPath = $request->file('foto_profile')->store('foto_profile', 'public');
            }

            $user = User::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $role->role_id,
                'foto_profile' => $fotoPath,
                'jabatan' => $request->jabatan,
                'contact_person' => $request->contact_person,
            ]);

            Penyuluh::create([
                'nip_penyuluh' => $request->nip_penyuluh,
                'user_id' => $user->user_id,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Penyuluh berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($fotoPath) {
                Storage::disk('public')->delete($fotoPath);
            }
            return redirect()->back()->withErrors(['error' => 'Gagal menambahkan penyuluh: ' . $e->getMessage()])->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $penyuluh = Penyuluh::where('user_id', $id)->first();

        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:user,email,' . $id . ',user_id',
            'nip_penyuluh' => 'required|string|max:10|unique:penyuluh,nip_penyuluh,' . ($penyuluh ? $penyuluh->nip_penyuluh : 'NULL') . ',nip_penyuluh',
            'jabatan' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:15',
            'foto_profile' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $userData = [
            'nama' => $request->nama,
            'email' => $request->email,
            'jabatan' => $request->jabatan,
            'contact_person' => $request->contact_person,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:6']);
            $userData['password'] = Hash::make($request->password);
        }

        // Handle upload foto profil baru
        if ($request->hasFile('foto_profile')) {
            if ($user->foto_profile) {
                Storage::disk('public')->delete($user->foto_profile);
            }
            $userData['foto_profile'] = $request->file('foto_profile')->store('foto_profile', 'public');
        }

        // Handle hapus foto profil
        if ($request->boolean('hapus_foto') && $user->foto_profile) {
            Storage::disk('public')->delete($user->foto_profile);
            $userData['foto_profile'] = null;
        }

        DB::beginTransaction();
        try {
            $user->update($userData);

            if ($penyuluh) {
                // If NIP (nip_penyuluh) changed, since it's a primary key, we delete/re-create or update it
                if ($penyuluh->nip_penyuluh !== $request->nip_penyuluh) {
                    $penyuluh->delete();
                    Penyuluh::create([
                        'nip_penyuluh' => $request->nip_penyuluh,
                        'user_id' => $user->user_id,
                    ]);
                }
            } else {
                Penyuluh::create([
                    'nip_penyuluh' => $request->nip_penyuluh,
                    'user_id' => $user->user_id,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Penyuluh berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal memperbarui penyuluh: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->foto_profile) {
            Storage::disk('public')->delete($user->foto_profile);
        }

        $user->delete();

        return redirect()->back()->with('success', 'Penyuluh berhasil dihapus');
    }
}