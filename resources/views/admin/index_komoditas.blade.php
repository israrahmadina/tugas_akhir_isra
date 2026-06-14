@extends('layouts.app')

@section('title', 'Manajemen Komoditas')

@section('content')

<!-- Header Section -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h3 class="text-2xl font-bold text-[#1A3636]">Kelola Komoditas</h3>
        <p class="text-sm text-gray-500 mt-1">Kelola daftar komoditas yang dapat dipilih oleh kelompok binaan.</p>
    </div>

    <button onclick="openModal('modalTambah')"
       class="bg-[#4F6F52] text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#1A3636] transition-all duration-300 shadow-lg shadow-[#4F6F52]/20 flex items-center gap-2 group">
        <div class="bg-white/20 p-1 rounded-lg group-hover:bg-white/30 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
        </div>
        Tambah Komoditas
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

<!-- Table Card -->
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-500 hover:shadow-md">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-gray-50/50 text-gray-400 font-bold uppercase tracking-widest text-[10px] border-b border-gray-100">
                    <th class="py-5 px-8">No</th>
                    <th class="py-5 px-8">Nama Komoditas</th>
                    <th class="py-5 px-8">Kategori</th>
                    <th class="py-5 px-8 text-center">Gunakan Target</th>
                    <th class="py-5 px-8 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($komoditas as $index => $item)
                <tr class="hover:bg-gray-50/80 transition-all duration-200 group">
                    <td class="py-5 px-8 text-gray-400 font-medium">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                    <td class="py-5 px-8">
                        <span class="font-bold text-gray-700 group-hover:text-[#4F6F52] transition-colors">{{ $item->nama_komoditas }}</span>
                    </td>
                    <td class="py-5 px-8">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $item->kategori->nama_kategori === 'Produk' ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-green-50 text-green-600 border border-green-100' }}">
                            {{ $item->kategori->nama_kategori }}
                        </span>
                    </td>
                    <td class="py-5 px-8 text-center">
                        @if($item->menggunakan_target)
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-600 border border-amber-100">✓ Ya</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-gray-100 text-gray-400">Tidak</span>
                        @endif
                    </td>
                    <td class="py-5 px-8">
                        <div class="flex justify-center gap-3">
                            <button onclick="openEditModal({{ json_encode($item) }})"
                               class="w-9 h-9 flex items-center justify-center text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white rounded-xl transition-all duration-300 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                            </button>
                            <button onclick="openDeleteModal('{{ $item->komoditas_id }}', '{{ $item->nama_komoditas }}')"
                               class="w-9 h-9 flex items-center justify-center text-red-600 bg-red-50 hover:bg-red-600 hover:text-white rounded-xl transition-all duration-300 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-20 text-gray-400">Belum ada data komoditas</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Container -->
<div id="modalOverlay" class="fixed inset-0 z-[60] hidden transition-opacity duration-300 bg-black/40 backdrop-blur-[2px]"></div>

<!-- Modal Tambah -->
<div id="modalTambah" class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0 modal-content">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-[#1A3636]">Tambah Komoditas</h3>
            <button onclick="closeModal('modalTambah')" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 transition-colors text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('komoditas.store') }}" method="POST" class="p-8 space-y-5">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Kategori Komoditas</label>
                <select name="kategori_id" required class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-[#4F6F52]/10 outline-none transition-all">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategori as $kat)
                        <option value="{{ $kat->kategori_id }}">{{ $kat->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Nama Komoditas</label>
                <input type="text" name="nama_komoditas" required placeholder="Contoh: Madu Hutan, Kopi, Ekowisata" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-[#4F6F52]/10 outline-none transition-all">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Pengaturan Target Pelaporan</label>
                <label class="flex items-center gap-3 p-4 bg-amber-50 border border-amber-100 rounded-2xl cursor-pointer hover:bg-amber-100 transition-colors">
                    <input type="checkbox" name="menggunakan_target" value="1" class="w-4 h-4 rounded accent-amber-500">
                    <div>
                        <span class="block text-sm font-bold text-amber-800">Komoditas ini menggunakan Target Capaian</span>
                        <span class="text-[10px] text-amber-600">Jika dicentang, KPHL wajib mengisi target saat membuat jadwal untuk komoditas ini.</span>
                    </div>
                </label>
            </div>
            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeModal('modalTambah')" class="flex-1 px-6 py-3 border border-gray-100 text-gray-400 font-bold rounded-2xl hover:bg-gray-50 transition-all">Batal</button>
                <button type="submit" class="flex-1 px-6 py-3 bg-[#4F6F52] text-white font-bold rounded-2xl hover:bg-[#1A3636] transition-all shadow-lg shadow-[#4F6F52]/20">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="modalEdit" class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0 modal-content">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-[#1A3636]">Edit Komoditas</h3>
            <button onclick="closeModal('modalEdit')" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 transition-colors text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <form id="formEdit" action="" method="POST" class="p-8 space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Kategori Komoditas</label>
                <select name="kategori_id" id="edit_kategori_id" required class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                    @foreach($kategori as $kat)
                        <option value="{{ $kat->kategori_id }}">{{ $kat->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Nama Komoditas</label>
                <input type="text" name="nama_komoditas" id="edit_nama_komoditas" required class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Pengaturan Target Pelaporan</label>
                <label class="flex items-center gap-3 p-4 bg-amber-50 border border-amber-100 rounded-2xl cursor-pointer hover:bg-amber-100 transition-colors">
                    <input type="checkbox" name="menggunakan_target" id="edit_menggunakan_target" value="1" class="w-4 h-4 rounded accent-amber-500">
                    <div>
                        <span class="block text-sm font-bold text-amber-800">Komoditas ini menggunakan Target Capaian</span>
                        <span class="text-[10px] text-amber-600">Jika dicentang, KPHL wajib mengisi target saat membuat jadwal untuk komoditas ini.</span>
                    </div>
                </label>
            </div>
            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeModal('modalEdit')" class="flex-1 px-6 py-3 border border-gray-100 text-gray-400 font-bold rounded-2xl hover:bg-gray-50 transition-all">Batal</button>
                <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/20">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Delete -->
<div id="modalDelete" class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0 modal-content">
        <div class="p-8 text-center">
            <div class="w-20 h-20 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Hapus Komoditas?</h3>
            <p class="text-gray-500 text-sm mb-8 px-4">Anda akan menghapus komoditas <span id="delete_name" class="font-bold text-gray-800"></span>.</p>
            
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
            modal.classList.add('hidden');
        }, 300);
    }

    function openEditModal(item) {
        const form = document.getElementById('formEdit');
        form.action = `/admin/komoditas/${item.komoditas_id}`;
        
        document.getElementById('edit_kategori_id').value = item.kategori_id;
        document.getElementById('edit_nama_komoditas').value = item.nama_komoditas;
        document.getElementById('edit_menggunakan_target').checked = !!item.menggunakan_target;
        
        openModal('modalEdit');
    }

    function openDeleteModal(id, name) {
        const form = document.getElementById('formDelete');
        form.action = `/admin/komoditas/${id}`;
        document.getElementById('delete_name').innerText = name;
        openModal('modalDelete');
    }
</script>
@endpush
