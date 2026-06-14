<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriKomoditas;

class KategoriKomoditasSeeder extends Seeder
{
    public function run(): void
    {
        KategoriKomoditas::updateOrCreate(
            ['kategori_id' => 'KTG001'],
            [
                'nama_kategori' => 'Produk',
                'tipe' => 'produk',
            ]
        );

        KategoriKomoditas::updateOrCreate(
            ['kategori_id' => 'KTG002'],
            [
                'nama_kategori' => 'Pariwisata',
                'tipe' => 'pariwisata',
            ]
        );
    }
}