@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="mb-8">
    <h3 class="text-3xl font-bold text-[#1A3636]">
        Dashboard Monitoring
    </h3>
    <p class="text-sm text-gray-500 mt-1">
        Selamat datang kembali, {{ Auth::user()->nama }}.
    </p>
</div>

<!-- Statistik -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-6">

    <!-- Kelompok -->
    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-500">Total Kelompok</p>
                <h3 class="text-4xl font-black text-[#1A3636] mt-2">
                    {{ $kelompokCount }}
                </h3>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-[#4F6F52]/10 flex items-center justify-center">
                👥
            </div>
        </div>
    </div>

    <!-- Komoditas -->
    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-500">Komoditas</p>
                <h3 class="text-4xl font-black text-[#1A3636] mt-2">
                    {{ $komoditasCount }}
                </h3>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-[#D6BD98]/20 flex items-center justify-center">
                📦
            </div>
        </div>
    </div>

    <!-- Laporan -->
    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-500">Total Laporan</p>
                <h3 class="text-4xl font-black text-[#1A3636] mt-2">
                    {{ $laporanCount }}
                </h3>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center">
                📊
            </div>
        </div>
    </div>

    <!-- Penyuluh -->
    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-500">Penyuluh</p>
                <h3 class="text-4xl font-black text-[#1A3636] mt-2">
                    {{ $penyuluhCount }}
                </h3>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-green-50 flex items-center justify-center">
                🌿
            </div>
        </div>
    </div>

    <!-- User -->
    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-500">Pengguna</p>
                <h3 class="text-4xl font-black text-[#1A3636] mt-2">
                    {{ $userCount }}
                </h3>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-orange-50 flex items-center justify-center">
                👤
            </div>
        </div>
    </div>

</div>

<!-- Search -->
<div class="bg-white mt-8 p-6 rounded-[2rem] shadow-sm border border-gray-100">

    <div class="flex flex-col lg:flex-row gap-4 justify-between">

        <div class="w-full lg:w-1/2">
            <input
                type="text"
                placeholder="Cari kelompok, komoditas, atau laporan..."
                class="input input-bordered w-full">
        </div>

        <div class="flex gap-3">

            <select class="select select-bordered">
                <option>Semua Komoditas</option>
                <option>Kelulut</option>
                <option>Madu Sialang</option>
                <option>Rotan</option>
            </select>

            <select class="select select-bordered">
                <option>Urutkan</option>
                <option>Nama Kelompok</option>
                <option>Komoditas</option>
                <option>Laporan Terbaru</option>
            </select>

        </div>

    </div>

</div>

<!-- Grafik + Aktivitas -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mt-8">

    <!-- Grafik -->
    <div class="xl:col-span-2 bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">

        <div class="mb-6">
            <h4 class="font-bold text-xl text-[#1A3636]">
                Pelaporan Per Komoditas
            </h4>
            <p class="text-sm text-gray-500">
                Visualisasi jumlah pelaporan berdasarkan komoditas.
            </p>
        </div>

        <!-- Dummy Chart -->
        <div class="h-80 flex items-end justify-around gap-4">

            <div class="text-center">
                <div class="w-14 h-44 bg-[#4F6F52] rounded-t-xl"></div>
                <p class="mt-2 text-sm">Kelulut</p>
            </div>

            <div class="text-center">
                <div class="w-14 h-32 bg-[#739072] rounded-t-xl"></div>
                <p class="mt-2 text-sm">Rotan</p>
            </div>

            <div class="text-center">
                <div class="w-14 h-52 bg-[#4F6F52] rounded-t-xl"></div>
                <p class="mt-2 text-sm">Kopi</p>
            </div>

            <div class="text-center">
                <div class="w-14 h-24 bg-[#739072] rounded-t-xl"></div>
                <p class="mt-2 text-sm">Gaharu</p>
            </div>

            <div class="text-center">
                <div class="w-14 h-64 bg-[#4F6F52] rounded-t-xl"></div>
                <p class="mt-2 text-sm">Madu</p>
            </div>

        </div>

    </div>

    <!-- Aktivitas -->
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">

        <h4 class="font-bold text-xl text-[#1A3636] mb-6">
            Aktivitas Terbaru
        </h4>

        <div class="space-y-5">

            <div class="border-l-4 border-[#4F6F52] pl-4">
                <p class="font-semibold">Kelompok Harapan Jaya</p>
                <p class="text-sm text-gray-500">
                    Mengirim laporan produksi kelulut.
                </p>
            </div>

            <div class="border-l-4 border-[#4F6F52] pl-4">
                <p class="font-semibold">Kelompok Tani Makmur</p>
                <p class="text-sm text-gray-500">
                    Menambahkan data komoditas.
                </p>
            </div>

            <div class="border-l-4 border-[#4F6F52] pl-4">
                <p class="font-semibold">Penyuluh Lapangan</p>
                <p class="text-sm text-gray-500">
                    Memverifikasi laporan kelompok.
                </p>
            </div>

        </div>

    </div>

</div>

<!-- Monitoring -->
<div class="bg-white mt-8 p-8 rounded-[2rem] shadow-sm border border-gray-100">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h4 class="font-bold text-xl text-[#1A3636]">
                Monitoring Pelaporan
            </h4>
            <p class="text-sm text-gray-500">
                Ringkasan perkembangan pelaporan kelompok binaan.
            </p>
        </div>
    </div>

    <div class="overflow-x-auto">

        <table class="table">

            <thead>
                <tr>
                    <th>Kelompok</th>
                    <th>Komoditas</th>
                    <th>Status</th>
                    <th>Laporan Terakhir</th>
                </tr>
            </thead>

            <tbody>

                <tr>
                    <td>Harapan Jaya</td>
                    <td>Kelulut</td>
                    <td>
                        <div class="badge badge-success">
                            Aktif
                        </div>
                    </td>
                    <td>10 Juni 2026</td>
                </tr>

                <tr>
                    <td>Tani Makmur</td>
                    <td>Rotan</td>
                    <td>
                        <div class="badge badge-warning">
                            Menunggu
                        </div>
                    </td>
                    <td>05 Juni 2026</td>
                </tr>

                <tr>
                    <td>Sejahtera</td>
                    <td>Madu Hutan</td>
                    <td>
                        <div class="badge badge-success">
                            Aktif
                        </div>
                    </td>
                    <td>09 Juni 2026</td>
                </tr>

            </tbody>

        </table>

    </div>

</div>

@endsection