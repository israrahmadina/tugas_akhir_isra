<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Penyuluh;
use App\Models\KelompokUsaha;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin
        $adminRole = Role::where('role_name', 'Admin')->first();
        if ($adminRole) {
            User::firstOrCreate(
                ['email' => 'admin@pelaporan.com'],
                [
                    'user_id'  => 'USR001',
                    'role_id'  => $adminRole->role_id,
                    'nama'     => 'Administrator Utama',
                    'password' => Hash::make('password'),
                ]
            );
        }

        // 2. Kepala KPHL
        $kepalaKphlRole = Role::where('role_name', 'Kepala KPHL')->first();
        if ($kepalaKphlRole) {
            User::firstOrCreate(
                ['email' => 'kepalakphl@pelaporan.com'],
                [
                    'user_id'  => 'USR002',
                    'role_id'  => $kepalaKphlRole->role_id,
                    'nama'     => 'Kepala KPHL',
                    'jabatan'  => 'Kepala KPHL',
                    'password' => Hash::make('password'),
                ]
            );
        }

        // 3. Seksi
        $seksiRole = Role::where('role_name', 'Seksi')->first();
        if ($seksiRole) {
            User::firstOrCreate(
                ['email' => 'seksi@pelaporan.com'],
                [
                    'user_id'  => 'USR003',
                    'role_id'  => $seksiRole->role_id,
                    'nama'     => 'Petugas Seksi',
                    'jabatan'  => 'Seksi Pelaporan',
                    'password' => Hash::make('password'),
                ]
            );
        }

        // 4. Penyuluh
        $penyuluhRole = Role::where('role_name', 'Penyuluh')->first();
        if ($penyuluhRole) {
            $userPenyuluh = User::firstOrCreate(
                ['email' => 'penyuluh@pelaporan.com'],
                [
                    'user_id'  => 'USR004',
                    'role_id'  => $penyuluhRole->role_id,
                    'nama'     => 'Budi Penyuluh',
                    'jabatan'  => 'Penyuluh Kehutanan',
                    'password' => Hash::make('password'),
                ]
            );

            Penyuluh::firstOrCreate(
                ['user_id' => $userPenyuluh->user_id],
                [
                    'nip_penyuluh' => 'PNY001',
                ]
            );
        }

        // 5. Lembaga
        $lembagaRole = Role::where('role_name', 'Lembaga')->first();
        if ($lembagaRole) {
            User::firstOrCreate(
                ['email' => 'lembaga@pelaporan.com'],
                [
                    'user_id'  => 'USR005',
                    'role_id'  => $lembagaRole->role_id,
                    'nama'     => 'Ketua Lembaga',
                    'jabatan'  => 'Ketua Lembaga Masyarakat',
                    'password' => Hash::make('password'),
                ]
            );
        }

        // 6. Kelompok Usaha
        $kelompokRole = Role::where('role_name', 'Kelompok Usaha')->first();
        if ($kelompokRole) {
            $userKelompok = User::firstOrCreate(
                ['email' => 'kelompok@pelaporan.com'],
                [
                    'user_id'  => 'USR006',
                    'role_id'  => $kelompokRole->role_id,
                    'nama'     => 'Ketua Kelompok Usaha',
                    'password' => Hash::make('password'),
                ]
            );

            KelompokUsaha::firstOrCreate(
                ['user_id' => $userKelompok->user_id],
                [
                    'kelompok_id'       => 'KLU001',
                    'nama_usaha'        => 'Kelompok Usaha Hutan Lestari',
                    'legalitas_perizinan' => 'Izin Pemanfaatan Hutan Kemasyarakatan',
                    'alamat_lengkap'    => 'Jl. Raya Kehutanan No. 45, Padang',
                    // skema_id & produk_id diisi setelah tabel skema & produk ada datanya
                ]
            );
        }
    }
}