@extends('layouts.app')

@section('title', 'Dashboard Kelompok Binaan')

@section('content')

<!-- Welcome Section -->
<div class="mb-10">
    <h3 class="text-3xl font-black text-[#1A3636] tracking-tighter uppercase italic">Selamat Datang, {{ $kelompok->nama_kelompok }}</h3>
    <p class="text-sm text-gray-500 mt-1 font-medium">Pantau perkembangan produk dan laporan kelompok Anda hari ini.</p>
</div>

<!-- CARD STATISTIK -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 flex items-center justify-between group hover:shadow-md transition-all duration-500">
        <div>
            <p class="text-gray-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Total Produk</p>
            <h2 class="text-4xl font-black text-[#1A3636] tracking-tighter italic">{{ $stats['total_produk'] }}</h2>
        </div>
        <div class="w-14 h-14 bg-[#4F6F52]/10 text-[#4F6F52] rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
        </div>
    </div>

    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 flex items-center justify-between group hover:shadow-md transition-all duration-500">
        <div>
            <p class="text-gray-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Laporan Terkirim</p>
            <h2 class="text-4xl font-black text-[#1A3636] tracking-tighter italic">{{ $stats['total_laporan'] }}</h2>
        </div>
        <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M8 13h8"/><path d="M8 17h8"/><path d="M10 9H8"/></svg>
        </div>
    </div>

    <div class="bg-[#4F6F52] p-8 rounded-[2rem] shadow-xl shadow-[#4F6F52]/20 flex items-center justify-between group hover:bg-[#1A3636] transition-all duration-500">
        <div>
            <p class="text-white/50 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Laporan Bulan Ini</p>
            <h2 class="text-4xl font-black text-white tracking-tighter italic">{{ $stats['laporan_bulan_ini'] }}</h2>
        </div>
        <div class="w-14 h-14 bg-white/10 text-white rounded-2xl flex items-center justify-center group-hover:rotate-12 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
        </div>
    </div>

</div>

<!-- NOTIFIKASI SECTION -->
<div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 mb-10">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-sm font-black text-[#1A3636] uppercase tracking-[0.2em] italic">Notifikasi Terbaru</h3>
        @if($notifikasis->where('is_read', false)->count() > 0)
            <form action="{{ route('notifikasi.read-all') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="text-[9px] font-bold text-gray-500 uppercase tracking-widest hover:text-[#4F6F52] transition-colors">
                    Tandai Semua Dibaca
                </button>
            </form>
        @endif
    </div>

    <div class="space-y-3">
        @forelse($notifikasis as $notif)
            <div class="flex items-start gap-4 p-4 rounded-xl {{ $notif->is_read ? 'bg-gray-50' : 'bg-blue-50 border border-blue-100' }} transition-colors">
                <div class="w-2 h-2 rounded-full {{ $notif->is_read ? 'bg-gray-300' : 'bg-blue-500' }} mt-2 shrink-0"></div>
                
                <div class="flex-1">
                    <p class="text-sm {{ $notif->is_read ? 'text-gray-600' : 'text-gray-800 font-semibold' }}">
                        {{ $notif->pesan }}
                    </p>
                    <p class="text-[9px] text-gray-400 mt-1">
                        {{ $notif->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>

                @if(!$notif->is_read)
                    <form action="{{ route('notifikasi.read', $notif->notifikasi_id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="text-[9px] font-bold text-blue-600 uppercase tracking-widest hover:text-blue-700 transition-colors shrink-0">
                            Tandai Dibaca
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <div class="text-center py-8">
                <p class="text-gray-400 text-sm italic">Tidak ada notifikasi terbaru</p>
            </div>
        @endforelse
    </div>
</div>

<!-- GRAFIK + TABEL -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

    <!-- INFO KELOMPOK -->
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gray-50 rounded-full -mr-16 -mt-16 opacity-50"></div>
        <h3 class="text-sm font-black text-[#1A3636] uppercase tracking-[0.2em] mb-6 relative z-10 italic">Informasi Kelompok</h3>
        
        <div class="space-y-6 relative z-10">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-[#4F6F52]/5 flex items-center justify-center text-[#4F6F52] shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Nama Kelompok</p>
                    <p class="text-sm font-bold text-gray-700 mt-0.5">{{ $kelompok->nama_kelompok }}</p>
                </div>
            </div>

            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Jenis Izin</p>
                    <p class="text-sm font-bold text-gray-700 mt-0.5">{{ $kelompok->jenis_izin }}</p>
                </div>
            </div>

            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Nomor SK</p>
                    <p class="text-sm font-bold text-gray-700 mt-0.5">{{ $kelompok->nomor }}</p>
                </div>
            </div>

            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Status Kawasan</p>
                    <p class="text-sm font-bold text-gray-700 mt-0.5">{{ $kelompok->status_kawasan }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- TABEL LAPORAN TERBARU -->
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-sm font-black text-[#1A3636] uppercase tracking-[0.2em] italic">Laporan Terbaru</h3>
            <a href="{{ route('pelaporan.index') }}" class="text-[10px] font-black text-[#4F6F52] uppercase tracking-widest hover:underline transition-all">Lihat Semua</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-400 text-[10px] font-black uppercase tracking-widest border-b border-gray-50">
                        <th class="pb-4">Produk</th>
                        <th class="pb-4">Periode</th>
                        <th class="pb-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($laporanTerbaru as $l)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-4">
                            <span class="font-bold text-gray-700">{{ $l->produk->nama_produk }}</span>
                        </td>
                        <td class="py-4 text-gray-500 font-medium text-xs">{{ \Carbon\Carbon::parse($l->jadwal->tanggal_mulai)->translatedFormat('F Y') }}</td>
                        <td class="py-4 text-center">
                            <span class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[9px] font-bold rounded uppercase border border-blue-100">
                                Terkirim
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-12 text-center text-gray-400 italic text-xs">Belum ada laporan yang dikirimkan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection