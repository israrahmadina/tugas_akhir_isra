@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h3 class="text-3xl font-black text-[#1A3636] tracking-tighter uppercase italic">Notifikasi</h3>
        <p class="text-sm text-gray-500 mt-1 font-medium">Daftar lengkap notifikasi status laporan Anda.</p>
    </div>

    @if($notifikasis->isEmpty())
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-8 text-center">
            <p class="text-sm text-gray-400 font-bold uppercase tracking-widest">Tidak ada notifikasi</p>
        </div>
    @else
        <!-- Mark All as Read Button -->
        @if($notifikasis->where('is_read', false)->count() > 0)
            <div class="mb-6">
                <form action="{{ route('kelompok.notifikasi.mark-all-read') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-[#4F6F52] hover:bg-[#1A3636] text-white font-bold text-sm rounded transition">
                        Tandai Semua Dibaca
                    </button>
                </form>
            </div>
        @endif

        <!-- Notifikasi List -->
        <div class="space-y-4">
            @foreach($notifikasis as $notif)
                <div class="bg-white rounded-xl shadow-sm border {{ $notif->is_read ? 'border-gray-100' : 'border-blue-200 bg-blue-50' }} p-6">
                    <div class="flex justify-between items-start gap-4">
                        <div class="flex-1">
                            <!-- Status Indicator -->
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-2 h-2 rounded-full {{ $notif->is_read ? 'bg-gray-300' : 'bg-blue-500' }}"></div>
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    {{ $notif->created_at->format('d/m/Y H:i') }}
                                </span>
                            </div>

                            <!-- Message -->
                            <p class="text-sm {{ $notif->is_read ? 'text-gray-600' : 'text-gray-800 font-semibold' }}">
                                {{ $notif->pesan }}
                            </p>

                            <!-- Laporan Reference -->
                            @if($notif->riwayatVerifikasi && $notif->riwayatVerifikasi->pelaporan)
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <p class="text-xs text-gray-500 font-medium">
                                        <span class="text-gray-400">Laporan:</span> 
                                        {{ $notif->riwayatVerifikasi->pelaporan->jadwal->periode_nama ?? 'N/A' }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Action Button -->
                        @if(!$notif->is_read)
                            <form action="{{ route('kelompok.notifikasi.mark-read', $notif->notifikasi_id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold rounded transition whitespace-nowrap">
                                    Tandai Dibaca
                                </button>
                            </form>
                        @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded">
                                Sudah Dibaca
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($notifikasis->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $notifikasis->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
