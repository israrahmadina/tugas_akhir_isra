<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['role_id' => 'RL001', 'role_name' => 'Admin'],
            ['role_id' => 'RL002', 'role_name' => 'Penyuluh'],
            ['role_id' => 'RL003', 'role_name' => 'Kepala KPHL'],
            ['role_id' => 'RL004', 'role_name' => 'Seksi'],
            ['role_id' => 'RL005', 'role_name' => 'Lembaga'],
            ['role_id' => 'RL006', 'role_name' => 'Kelompok Usaha'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['role_id' => $role['role_id']], $role);
        }
    }
}