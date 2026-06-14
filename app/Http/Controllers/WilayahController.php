<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class WilayahController extends Controller
{
    public function provinsi()
    {
       $response = Http::withoutVerifying()
    ->get('https://wilayah.id/api/provinces.json');

        return response()->json($response->json()['data']);
    }

    public function kabupaten($kode)
{
    $response = Http::withoutVerifying()
        ->get("https://wilayah.id/api/regencies/$kode.json");

    return response()->json($response->json()['data']);
}

public function kecamatan($kode)
{
    $response = Http::withoutVerifying()
        ->get("https://wilayah.id/api/districts/$kode.json");

    return response()->json($response->json()['data']);
}

public function desa($kode)
{
    $response = Http::withoutVerifying()
        ->get("https://wilayah.id/api/villages/$kode.json");

    return response()->json($response->json()['data']);
}
}