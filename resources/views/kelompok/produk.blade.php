@extends('layouts.app')

@section('title', 'Manajemen Produk')

@section('content')
 
<!-- SUCCESS MESSAGE -->
@if(session('success'))
    <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-2xl border border-green-200">
        {{ session('success') }}
    </div>
@endif

 <!-- HEADER -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h3 class="text-2xl font-bold text-[#1A3636]">Data Produk</h3>
        <p class="text-sm text-gray-500 mt-1">Kelola produk kelompok binaan.</p>
    </div>

    <a href="{{ route('kelompok.produk.create') }}"
   class="bg-[#4F6F52] text-white px-5 py-2 rounded-xl">
   + Tambah Produk
</a>
</div>

<!-- TABLE -->
<div class="bg-white rounded-3xl shadow-sm border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-gray-50 text-gray-400 uppercase text-[10px]">
                    <th class="py-5 px-6">No</th>
                    <th class="py-5 px-6">Gambar</th>
                    <th class="py-5 px-6">Nama Produk</th>
                    <th class="py-5 px-6">Deskripsi</th>
                    <th class="py-5 px-6">Tipe</th>
                    <th class="py-5 px-6 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($produks as $index => $produk)
                <tr class="border-t hover:bg-gray-50">

                    <!-- NO -->
                    <td class="py-4 px-6">
                        {{ $index + 1 }}
                    </td>

                    <!-- GAMBAR -->
                    <td class="py-4 px-6">
                        @if($produk->gambar_produk)
                            <img src="{{ asset('storage/' . $produk->gambar_produk) }}"
                                class="w-14 h-14 object-cover rounded-lg">
                        @else
                            <div class="w-14 h-14 bg-gray-200 rounded-lg flex items-center justify-center text-xs text-gray-400">
                                No Img
                            </div>
                        @endif
                    </td>

                    <!-- NAMA -->
                    <td class="py-4 px-6 font-semibold">
                       {{ $produk->nama_usaha }}
                    </td>

                    <!-- DESKRIPSI -->
                    <td class="py-4 px-6 text-gray-500">
                        {{ \Illuminate\Support\Str::limit($produk->deskripsi, 50) }}
                    </td>

                    <!-- TIPE (JENIS PRODUK) -->
                    <td class="py-4 px-6">
                        <div class="flex flex-col">
                            <span class="font-bold text-gray-700">{{ $produk->komoditas->nama_komoditas ?? '-' }}</span>
                            <span class="text-[10px] text-gray-400 uppercase tracking-widest">{{ $produk->komoditas->kategori->nama_kategori ?? '' }}</span>
                        </div>
                    </td>

                    <!-- AKSI -->
                    <td class="py-4 px-6">
                        <div class="flex justify-center gap-2">

                            <!-- EDIT -->
                            <a href="{{ route('kelompok.produk.edit', $produk->usaha_id) }}"
                                class="bg-blue-100 text-blue-600 px-3 py-1 rounded-lg text-xs hover:bg-blue-200">
                                Edit
                            </a>

                            <!-- DELETE -->
                            <form action="{{ route('kelompok.produk.destroy', $produk->usaha_id) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-100 text-red-600 px-3 py-1 rounded-lg text-xs hover:bg-red-200">
                                    Hapus
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-10 text-gray-400">
                        Belum ada produk
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>

@endsection