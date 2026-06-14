@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <div>
            <h1 class="text-2xl font-bold text-[#1A3636]">
                Dashboard Penyuluh
            </h1>
            <p class="text-xs text-gray-500 mt-1 font-medium">
                Sistem Pemantauan dan Verifikasi Pelaporan Kelompok Binaan
            </p>
        </div>
        <div class="px-4 py-2 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-800 text-xs font-semibold flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block animate-pulse"></span>
            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
        </div>
    </div>

    <!-- CARD STATS -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

        <!-- TOTAL KELOMPOK -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:-translate-y-1 hover:shadow-md transition-all duration-300 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Kelompok</p>
                <h2 class="text-3xl font-black mt-2 text-[#1A3636] leading-none">
                    {{ $totalKelompok }}
                </h2>
                <p class="text-[10px] text-gray-400 mt-2 font-medium">Kelompok Binaan Aktif</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-[#1A3636] flex items-center justify-center border border-emerald-100/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>

        <!-- PENDING -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:-translate-y-1 hover:shadow-md transition-all duration-300 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pending Verifikasi</p>
                <h2 class="text-3xl font-black mt-2 text-amber-600 leading-none">
                    {{ $pending }}
                </h2>
                <p class="text-[10px] text-amber-500 mt-2 font-medium">Butuh Tindakan Segera</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- DIVERIFIKASI -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:-translate-y-1 hover:shadow-md transition-all duration-300 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Diverifikasi</p>
                <h2 class="text-3xl font-black mt-2 text-emerald-600 leading-none">
                    {{ $verified }}
                </h2>
                <p class="text-[10px] text-emerald-500 mt-2 font-medium">Laporan Disetujui</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- DITOLAK -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:-translate-y-1 hover:shadow-md transition-all duration-300 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Ditolak</p>
                <h2 class="text-3xl font-black mt-2 text-rose-600 leading-none">
                    {{ $rejected }}
                </h2>
                <p class="text-[10px] text-rose-500 mt-2 font-medium">Perlu Perbaikan</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

    </div>

    <!-- DASHBOARD CONTENT -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- PRODUK KELOMPOK -->
        <div class="xl:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col justify-between">

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-lg font-bold text-[#1A3636]">
                        Produk Kelompok Binaan
                    </h2>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Sebaran komoditas yang dikelola kelompok binaan.
                    </p>
                </div>
                <div class="text-xs font-bold text-gray-500 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">
                    Tahun 2026
                </div>
            </div>

            <!-- Custom Chart Representation -->
            <div class="relative h-72 border-b border-gray-100 pb-2">
                <!-- Gridlines -->
                <div class="absolute inset-0 flex flex-col justify-between pointer-events-none pb-8">
                    <div class="border-t border-gray-100/70 w-full h-0"></div>
                    <div class="border-t border-gray-100/70 w-full h-0"></div>
                    <div class="border-t border-gray-100/70 w-full h-0"></div>
                    <div class="border-t border-gray-100/70 w-full h-0"></div>
                    <div class="border-t border-gray-100/70 w-full h-0"></div>
                </div>
                
                <!-- Bars -->
                <div class="relative z-10 h-full flex items-end justify-around px-4">
                    <div class="flex flex-col items-center group relative">
                        <!-- Tooltip -->
                        <div class="absolute -top-10 scale-0 group-hover:scale-100 transition-all duration-200 bg-[#1A3636] text-white text-xs font-bold px-2.5 py-1 rounded shadow-md pointer-events-none whitespace-nowrap">
                            40 Laporan
                        </div>
                        <div class="w-12 h-40 rounded-t-lg bg-gradient-to-t from-[#1F3D2B] to-[#4F6F52] hover:opacity-90 transition-all cursor-pointer shadow-sm"></div>
                        <span class="mt-3 text-xs font-bold text-gray-600">Kelulut</span>
                    </div>

                    <div class="flex flex-col items-center group relative">
                        <!-- Tooltip -->
                        <div class="absolute -top-10 scale-0 group-hover:scale-100 transition-all duration-200 bg-[#1A3636] text-white text-xs font-bold px-2.5 py-1 rounded shadow-md pointer-events-none whitespace-nowrap">
                            28 Laporan
                        </div>
                        <div class="w-12 h-28 rounded-t-lg bg-gradient-to-t from-[#4F7942] to-[#739072] hover:opacity-90 transition-all cursor-pointer shadow-sm"></div>
                        <span class="mt-3 text-xs font-bold text-gray-600">Rotan</span>
                    </div>

                    <div class="flex flex-col items-center group relative">
                        <!-- Tooltip -->
                        <div class="absolute -top-10 scale-0 group-hover:scale-100 transition-all duration-200 bg-[#1A3636] text-white text-xs font-bold px-2.5 py-1 rounded shadow-md pointer-events-none whitespace-nowrap">
                            56 Laporan
                        </div>
                        <div class="w-12 h-56 rounded-t-lg bg-gradient-to-t from-[#1F3D2B] to-[#4F6F52] hover:opacity-90 transition-all cursor-pointer shadow-sm"></div>
                        <span class="mt-3 text-xs font-bold text-gray-600">Madu</span>
                    </div>

                    <div class="flex flex-col items-center group relative">
                        <!-- Tooltip -->
                        <div class="absolute -top-10 scale-0 group-hover:scale-100 transition-all duration-200 bg-[#1A3636] text-white text-xs font-bold px-2.5 py-1 rounded shadow-md pointer-events-none whitespace-nowrap">
                            32 Laporan
                        </div>
                        <div class="w-12 h-32 rounded-t-lg bg-gradient-to-t from-[#4F7942] to-[#739072] hover:opacity-90 transition-all cursor-pointer shadow-sm"></div>
                        <span class="mt-3 text-xs font-bold text-gray-600">Kopi</span>
                    </div>

                    <div class="flex flex-col items-center group relative">
                        <!-- Tooltip -->
                        <div class="absolute -top-10 scale-0 group-hover:scale-100 transition-all duration-200 bg-[#1A3636] text-white text-xs font-bold px-2.5 py-1 rounded shadow-md pointer-events-none whitespace-nowrap">
                            48 Laporan
                        </div>
                        <div class="w-12 h-48 rounded-t-lg bg-gradient-to-t from-[#1F3D2B] to-[#4F6F52] hover:opacity-90 transition-all cursor-pointer shadow-sm"></div>
                        <span class="mt-3 text-xs font-bold text-gray-600">Gaharu</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- AKTIVITAS -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col justify-between">
            <div>
                <h2 class="text-lg font-bold text-[#1A3636] mb-5">
                    Aktivitas Terbaru
                </h2>

                <div class="relative border-l border-gray-100 ml-3 pl-6 space-y-6">
                    <!-- item 1 -->
                    <div class="relative">
                        <span class="absolute -left-[30px] top-1 w-4 h-4 rounded-full bg-emerald-50 border-2 border-emerald-500 flex items-center justify-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        </span>
                        <div>
                            <p class="text-xs font-bold text-gray-700">Pelaporan Diverifikasi</p>
                            <p class="text-[11px] text-gray-400 mt-0.5 font-medium">Laporan kelompok binaan disetujui & diverifikasi.</p>
                            <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full mt-1.5 inline-block">Selesai</span>
                        </div>
                    </div>

                    <!-- item 2 -->
                    <div class="relative">
                        <span class="absolute -left-[30px] top-1 w-4 h-4 rounded-full bg-amber-50 border-2 border-amber-500 flex items-center justify-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        </span>
                        <div>
                            <p class="text-xs font-bold text-gray-700">Menunggu Verifikasi</p>
                            <p class="text-[11px] text-gray-400 mt-0.5 font-medium">Laporan baru masuk, dalam antrean pemeriksaan.</p>
                            <span class="text-[9px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full mt-1.5 inline-block">Pending</span>
                        </div>
                    </div>

                    <!-- item 3 -->
                    <div class="relative">
                        <span class="absolute -left-[30px] top-1 w-4 h-4 rounded-full bg-rose-50 border-2 border-rose-500 flex items-center justify-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                        </span>
                        <div>
                            <p class="text-xs font-bold text-gray-700">Revisi Pelaporan</p>
                            <p class="text-[11px] text-gray-400 mt-0.5 font-medium">Laporan ditolak & dikembalikan untuk perbaikan.</p>
                            <span class="text-[9px] font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full mt-1.5 inline-block">Revisi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/30 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-lg font-bold text-[#1A3636]">
                        Monitoring Pelaporan
                    </h2>
                    <span class="text-[10px] font-bold text-[#1A3636] bg-[#1A3636]/5 px-2.5 py-1 rounded-full">
                        {{ $pelaporan->count() }} Laporan
                    </span>
                </div>
                <p class="text-xs text-gray-400 mt-0.5 font-medium">
                    Daftar laporan kelompok binaan yang masuk ke sistem.
                </p>
            </div>
            
            <div class="relative w-full md:w-72">
                <input
                    type="text"
                    id="searchTableInput"
                    placeholder="Cari kelompok atau produk..."
                    class="w-full pl-9 pr-4 py-2 text-xs font-medium bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-emerald-500/5 focus:border-[#4F6F52] outline-none transition-all">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-400 font-bold uppercase tracking-widest text-[10px] border-b border-gray-100">
                        <th class="py-4 px-6">Kelompok</th>
                        <th class="py-4 px-6">Produk</th>
                        <th class="py-4 px-6">Bulan</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($pelaporan as $item)
                    <tr class="hover:bg-gray-50/50 transition-all duration-200 table-row-item">
                        <td class="py-4 px-6">
                            <span class="font-bold text-gray-700">{{ $item->produk->kelompok->nama_kelompok ?? 'N/A' }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="font-semibold text-[#4F6F52]">{{ $item->produk->nama_produk ?? 'N/A' }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="text-xs text-gray-500 font-medium">{{ $item->bulan_pelaporan }}</span>
                        </td>
                        <td class="py-4 px-6">
                            @if($item->status_verifikasi == 'pending')
                                <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full bg-amber-50 text-amber-700 border border-amber-200/50 animate-pulse">
                                    Pending
                                </span>
                            @elseif($item->status_verifikasi == 'diverifikasi')
                                <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/50">
                                    Diverifikasi
                                </span>
                            @else
                                <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full bg-rose-50 text-rose-700 border border-rose-200/50">
                                    Ditolak
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if($item->status_verifikasi == 'pending')
                                <a href="{{ route('penyuluh.verifikasi') }}"
                                   class="inline-block px-3.5 py-2 bg-[#1F3D2B] hover:bg-[#1A3636] text-white text-xs font-bold rounded-lg transition shadow-sm">
                                    Verifikasi
                                </a>
                            @else
                                <a href="{{ route('penyuluh.riwayat') }}"
                                   class="inline-block px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-bold rounded-lg transition">
                                    Detail
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-12 text-gray-400 font-semibold text-sm">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Belum ada data pelaporan</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    document.getElementById('searchTableInput')?.addEventListener('keyup', function(e) {
        const query = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.table-row-item');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endpush
