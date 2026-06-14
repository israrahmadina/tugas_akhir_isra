@extends('layouts.app')

@section('title', 'Manajemen Jadwal Pelaporan')

@section('content')

<!-- Header Section -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h3 class="text-2xl font-bold text-[#1A3636]">Jadwal Pelaporan</h3>
        <p class="text-sm text-gray-500 mt-1">Atur periode waktu pelaporan untuk setiap komoditas.</p>
    </div>

    <button onclick="openModal('modalTambah')"
       class="bg-[#4F6F52] text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#1A3636] transition-all duration-300 shadow-lg shadow-[#4F6F52]/20 flex items-center gap-2 group">
        <div class="bg-white/20 p-1 rounded-lg group-hover:bg-white/30 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
        </div>
        Buat Jadwal Baru
    </button>
</div>

@if(session('success'))
<div id="alert-success" class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-8 rounded-xl shadow-sm flex items-center justify-between animate-in slide-in-from-top duration-300">
    <div class="flex items-center gap-3">
        <div class="bg-green-500 text-white p-1 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <span class="text-sm font-bold">{{ session('success') }}</span>
    </div>
</div>
@endif

@if($errors->any())
<div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-8 rounded-xl shadow-sm">
    <ul class="list-disc pl-5 text-sm font-bold">
        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul>
</div>
@endif

<!-- Calendar List Presentation -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($jadwals as $jadwal)
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-md transition-all duration-300">
        <!-- Card Top: Komoditas -->
        <div class="p-6 bg-gray-50/50 border-b border-gray-100 flex justify-between items-start">
            <div>
                <h4 class="text-xl font-black text-[#1A3636] uppercase tracking-tighter">{{ $jadwal->komoditas->nama_komoditas ?? 'Semua Komoditas' }}</h4>
                <p class="text-[10px] font-bold text-gray-400 tracking-widest">{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->translatedFormat('F Y') }}</p>
            </div>
            <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                <button onclick="openEditModal({{ json_encode($jadwal) }})" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                </button>
                <button onclick="openDeleteModal('{{ $jadwal->jadwal_id }}', '{{ $jadwal->komoditas->nama_komoditas ?? 'Semua Komoditas' }} ({{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->translatedFormat('F Y') }})')" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                </button>
            </div>
        </div>

        <!-- Card Body: Date Range -->
        <div class="p-6 space-y-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-[#4F6F52]/10 rounded-2xl flex flex-col items-center justify-center text-[#4F6F52]">
                    <span class="text-lg font-black leading-none">{{ date('d', strtotime($jadwal->tanggal_mulai)) }}</span>
                    <span class="text-[8px] font-bold uppercase tracking-widest">{{ date('M', strtotime($jadwal->tanggal_mulai)) }}</span>
                </div>
                <div class="flex-1">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Mulai Pelaporan</p>
                    <p class="text-sm font-bold text-gray-700">{{ date('l, d F Y', strtotime($jadwal->tanggal_mulai)) }}</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-red-50 rounded-2xl flex flex-col items-center justify-center text-red-600">
                    <span class="text-lg font-black leading-none">{{ date('d', strtotime($jadwal->tanggal_selesai)) }}</span>
                    <span class="text-[8px] font-bold uppercase tracking-widest">{{ date('M', strtotime($jadwal->tanggal_selesai)) }}</span>
                </div>
                <div class="flex-1">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Batas Akhir</p>
                    <p class="text-sm font-bold text-gray-700">{{ date('l, d F Y', strtotime($jadwal->tanggal_selesai)) }}</p>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-[8px] font-black uppercase">
                        {{ substr($jadwal->userKphl->nama ?? 'K', 0, 1) }}
                    </div>
                    <span class="text-[10px] text-gray-500 font-medium">{{ $jadwal->userKphl->nama ?? 'Admin' }}</span>
                </div>

                @php
                    $today = date('Y-m-d');
                    $isActive = ($today >= $jadwal->tanggal_mulai && $today <= $jadwal->tanggal_selesai);
                    $isPast = ($today > $jadwal->tanggal_selesai);
                @endphp

                @if($isActive)
                    <span class="px-2 py-1 bg-green-100 text-green-600 rounded text-[9px] font-black uppercase tracking-widest animate-pulse">Sedang Berjalan</span>
                @elseif($isPast)
                    <span class="px-2 py-1 bg-gray-100 text-gray-400 rounded text-[9px] font-black uppercase tracking-widest">Berakhir</span>
                @else
                    <span class="px-2 py-1 bg-blue-100 text-blue-600 rounded text-[9px] font-black uppercase tracking-widest">Mendatang</span>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-20 bg-white rounded-3xl border border-dashed border-gray-200 text-center">
        <div class="w-16 h-16 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
        </div>
        <p class="text-gray-400 font-medium">Belum ada jadwal pelaporan yang dibuat.</p>
    </div>
    @endforelse
</div>

<!-- Modal Container -->
<div id="modalOverlay" class="fixed inset-0 z-[60] hidden transition-opacity duration-300 bg-black/40 backdrop-blur-[2px]"></div>

<!-- Modal Tambah -->
<div id="modalTambah" class="fixed inset-0 z-[70] hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0 modal-content">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-[#1A3636]">Buat Jadwal Baru</h3>
            <button onclick="closeModal('modalTambah')" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 transition-colors text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('jadwal.store') }}" method="POST" class="p-8 space-y-5">
            @csrf

            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Komoditas</label>
                <select name="komoditas_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-[#4F6F52]/10 outline-none transition-all">
                    <option value="">Semua Komoditas (Umum)</option>
                    @foreach($komoditas as $k)
                        <option value="{{ $k->komoditas_id }}">{{ $k->nama_komoditas }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Petugas KPHL Penanggung Jawab</label>
                <select name="user_id_kphl" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-[#4F6F52]/10 outline-none transition-all">
                    @foreach($kphls as $k)
                        <option value="{{ $k->user_id }}">{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-[#4F6F52]/10 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-[#4F6F52]/10 outline-none transition-all">
                </div>
            </div>

            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeModal('modalTambah')" class="flex-1 px-6 py-3 border border-gray-100 text-gray-400 font-bold rounded-2xl hover:bg-gray-50 transition-all">Batal</button>
                <button type="submit" class="flex-1 px-6 py-3 bg-[#4F6F52] text-white font-bold rounded-2xl hover:bg-[#1A3636] transition-all shadow-lg shadow-[#4F6F52]/20">Simpan Jadwal</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="modalEdit" class="fixed inset-0 z-[70] hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0 modal-content">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-[#1A3636]">Edit Jadwal</h3>
            <button onclick="closeModal('modalEdit')" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 transition-colors text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <form id="formEdit" action="" method="POST" class="p-8 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Komoditas</label>
                <select name="komoditas_id" id="edit_komoditas_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                    <option value="">Semua Komoditas (Umum)</option>
                    @foreach($komoditas as $k)
                        <option value="{{ $k->komoditas_id }}">{{ $k->nama_komoditas }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Petugas KPHL Penanggung Jawab</label>
                <select name="user_id_kphl" id="edit_user_id_kphl" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                    @foreach($kphls as $k)
                        <option value="{{ $k->user_id }}">{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" id="edit_tanggal_mulai" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" id="edit_tanggal_selesai" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                </div>
            </div>

            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeModal('modalEdit')" class="flex-1 px-6 py-3 border border-gray-100 text-gray-400 font-bold rounded-2xl hover:bg-gray-50 transition-all">Batal</button>
                <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/20">Update Jadwal</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Delete -->
<div id="modalDelete" class="fixed inset-0 z-[70] hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0 modal-content">
        <div class="p-8 text-center">
            <div class="w-20 h-20 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Hapus Jadwal?</h3>
            <p class="text-gray-500 text-sm mb-8 px-4">Anda akan menghapus jadwal <span id="delete_name" class="font-bold text-gray-800"></span>.</p>

            <form id="formDelete" action="" method="POST" class="flex gap-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeModal('modalDelete')" class="flex-1 px-6 py-3 border border-gray-100 text-gray-400 font-bold rounded-2xl hover:bg-gray-50 transition-all">Batal</button>
                <button type="submit" class="flex-1 px-6 py-3 bg-red-600 text-white font-bold rounded-2xl hover:bg-red-700 transition-all shadow-lg shadow-red-600/20">Ya, Hapus</button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openModal(id) {
        const overlay = document.getElementById('modalOverlay');
        const modal = document.getElementById(id);
        const content = modal.querySelector('.modal-content');

        overlay.classList.remove('hidden');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        setTimeout(() => {
            overlay.classList.add('opacity-100');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeModal(id) {
        const overlay = document.getElementById('modalOverlay');
        const modal = document.getElementById(id);
        const content = modal.querySelector('.modal-content');

        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        overlay.classList.remove('opacity-100');
        overlay.classList.add('opacity-0');

        setTimeout(() => {
            overlay.classList.add('hidden');
            overlay.classList.remove('opacity-0');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    function openEditModal(jadwal) {
        const form = document.getElementById('formEdit');
        form.action = `/admin/jadwal/${jadwal.jadwal_id}`;

        document.getElementById('edit_komoditas_id').value = jadwal.komoditas_id || '';
        document.getElementById('edit_user_id_kphl').value = jadwal.user_id_kphl;
        document.getElementById('edit_tanggal_mulai').value = jadwal.tanggal_mulai;
        document.getElementById('edit_tanggal_selesai').value = jadwal.tanggal_selesai;

        openModal('modalEdit');
    }

    function openDeleteModal(id, name) {
        const form = document.getElementById('formDelete');
        form.action = `/admin/jadwal/${id}`;
        document.getElementById('delete_name').innerText = name;
        openModal('modalDelete');
    }

    window.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            ['modalTambah', 'modalEdit', 'modalDelete'].forEach(id => {
                const m = document.getElementById(id);
                if (m && !m.classList.contains('hidden')) closeModal(id);
            });
        }
    });
</script>
@endpush
