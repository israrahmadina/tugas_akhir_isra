@extends('layouts.app')

@section('title', 'Manajemen Atribut Pelaporan')

@section('content')

<!-- Header Section -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h3 class="text-2xl font-bold text-[#1A3636]">Pengaturan Field Laporan</h3>
        <p class="text-sm text-gray-500 mt-1">Tentukan pertanyaan atau kolom data apa saja yang harus diisi oleh kelompok binaan saat mereka mengirimkan laporan rutin.</p>
    </div>

    <button onclick="openModal('modalTambah')"
       class="bg-[#4F6F52] text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#1A3636] transition-all duration-300 shadow-lg shadow-[#4F6F52]/20 flex items-center gap-2 group">
        <div class="bg-white/20 p-1 rounded-lg group-hover:bg-white/30 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
        </div>
        Tambah Field Baru
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
    <button onclick="document.getElementById('alert-success').remove()" class="text-green-500 hover:text-green-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
    </button>
</div>
@endif

<!-- Table Card -->
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-500 hover:shadow-md">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-gray-50/50 text-gray-400 font-bold uppercase tracking-widest text-[10px] border-b border-gray-100">
                    <th class="py-5 px-8">No</th>
                    <th class="py-5 px-8">Nama Field (Pertanyaan Laporan)</th>
                    <th class="py-5 px-8">Satuan</th>
                    <th class="py-5 px-8">Tipe Field</th>
                    <th class="py-5 px-8">Berlaku Untuk Jenis</th>
                    <th class="py-5 px-8">Kategori Utama</th>
                    <th class="py-5 px-8 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($atributs as $index => $atribut)
                <tr class="hover:bg-gray-50/80 transition-all duration-200 group">
                    <td class="py-5 px-8 text-gray-400 font-medium">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                    <td class="py-5 px-8">
                        <span class="font-bold text-gray-700 group-hover:text-[#4F6F52] transition-colors">{{ $atribut->nama_atribut }}</span>
                    </td>
                    <td class="py-5 px-8 text-gray-600 font-medium">{{ $atribut->satuan ?? '-' }}</td>
                    <td class="py-5 px-8">
                           <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                          {{ $atribut->jenis_field == 'text' ? 'bg-blue-50 text-blue-600 border border-blue-100' : '' }}
                                           {{ $atribut->jenis_field == 'number' ? 'bg-green-50 text-green-600 border border-green-100' : '' }}
                                          {{ $atribut->jenis_field == 'image' ? 'bg-purple-50 text-purple-600 border border-purple-100' : '' }}">
                            {{ $atribut->jenis_field }}
                        </span>
                    </td>

                    <td class="py-5 px-8 text-gray-600 font-medium">{{ $atribut->komoditas->nama_komoditas ?? 'Semua Komoditas' }}</td>
                    <td class="py-5 px-8">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $atribut->tipe_atribut === 'produk' ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-orange-50 text-orange-600 border border-orange-100' }}">
                            {{ $atribut->tipe_atribut }}
                        </span>
                    </td>
                    <td class="py-5 px-8">
                        <div class="flex justify-center gap-3">
                            <button onclick="openEditModal({{ json_encode($atribut) }})"
                               class="w-9 h-9 flex items-center justify-center text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white rounded-xl transition-all duration-300 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                            </button>
                            <button onclick="openDeleteModal('{{ $atribut->atribut_id }}', '{{ $atribut->nama_atribut }}')"
                               class="w-9 h-9 flex items-center justify-center text-red-600 bg-red-50 hover:bg-red-600 hover:text-white rounded-xl transition-all duration-300 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-20 text-gray-400">Belum ada data atribut</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Container -->
<div id="modalOverlay" class="fixed inset-0 z-[60] hidden transition-opacity duration-300 bg-black/40 backdrop-blur-[2px]"></div>

<!-- Modal Tambah -->
<div id="modalTambah" class="fixed inset-0 z-[70] hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0 modal-content">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-[#1A3636]">Buat Field Pertanyaan Laporan</h3>
            <button onclick="closeModal('modalTambah')" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 transition-colors text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('atribut.store') }}" method="POST" class="p-8 space-y-5">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Nama Field / Label Input</label>
                <input type="text" name="nama_atribut" required placeholder="Contoh: Jumlah Produksi (Kg), Omzet Penjualan (Rp)" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-[#4F6F52]/10 outline-none transition-all">
                <p class="text-[9px] text-gray-400 mt-1">*Teks ini akan muncul sebagai label saat kelompok mengisi form laporan.</p>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Komoditas</label>
                <select name="komoditas_id" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-[#4F6F52]/10 outline-none transition-all">
                    <option value="">Semua Komoditas (Umum)</option>
                    @foreach($komoditas as $kom)
                        <option value="{{ $kom->komoditas_id }}">{{ $kom->nama_komoditas }}</option>
                    @endforeach
                </select>
            </div>
                <div>
             <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">
        Tipe Field
    </label>

    <select
        name="jenis_field"
        id="jenis_field"
        onchange="toggleSatuan(this.value)"
        class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-[#4F6F52]/10 outline-none transition-all">

        <option value="text">Text</option>
        <option value="number">Angka</option>
        <option value="image">Foto / Gambar</option>

    </select>
</div>
            
 <div id="satuan-container" style="display:none">
    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Satuan</label>
     <select name="satuan" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-[#4F6F52]/10 outline-none transition-all">
        <option value="">-- Pilih Satuan --</option>
        <option value="kg">Kg</option>
        <option value="ton">Ton</option>
        <option value="liter">Liter</option>
        <option value="gram">Gram</option>
        <option value="unit">PCS</option>
        <option value="orang">Ikat</option>

    </select>
</div>
          
            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeModal('modalTambah')" class="flex-1 px-6 py-3 border border-gray-100 text-gray-400 font-bold rounded-2xl hover:bg-gray-50 transition-all">Batal</button>
                <button type="submit" class="flex-1 px-6 py-3 bg-[#4F6F52] text-white font-bold rounded-2xl hover:bg-[#1A3636] transition-all shadow-lg shadow-[#4F6F52]/20">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="modalEdit" class="fixed inset-0 z-[70] hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0 modal-content">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-[#1A3636]">Edit Atribut</h3>
            <button onclick="closeModal('modalEdit')" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 transition-colors text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <form id="formEdit" action="" method="POST" class="p-8 space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Nama Atribut</label>
                <input type="text" name="nama_atribut" id="edit_nama" required class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Komoditas</label>
                <select name="komoditas_id" id="edit_komoditas_id" class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                    <option value="">Semua Komoditas (Umum)</option>
                    @foreach($komoditas as $kom)
                        <option value="{{ $kom->komoditas_id }}">{{ $kom->nama_komoditas }}</option>
                    @endforeach
                </select>
            </div>

  <div>
    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">
        Tipe Field
    </label>

    <select
        name="jenis_field"
        id="edit_jenis_field"
        onchange="toggleEditSatuan(this.value)"
        class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-2xl">

        <option value="text">Text</option>
        <option value="number">Angka</option>
        <option value="image">Foto / Gambar</option>

    </select>
</div>
            
 <div id="edit-satuan-container" style="display:none">
    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Satuan</label>

    <select name="satuan" id="edit_satuan" class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
        <option value="">-- Pilih Satuan --</option>
        <option value="kg">Kg</option>
        <option value="ton">Ton</option>
        <option value="liter">Liter</option>
        <option value="gram">Gram</option>
        <option value="unit">PCS</option>
        <option value="orang">Ikat</option>
        
    </select>
</div>
            
            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeModal('modalEdit')" class="flex-1 px-6 py-3 border border-gray-100 text-gray-400 font-bold rounded-2xl hover:bg-gray-50 transition-all">Batal</button>
                <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/20">Update</button>
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
            <h3 class="text-xl font-bold text-gray-800 mb-2">Hapus Atribut?</h3>
            <p class="text-gray-500 text-sm mb-8 px-4">Anda akan menghapus atribut <span id="delete_name" class="font-bold text-gray-800"></span>.</p>
            
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

    function openEditModal(atribut) {
        const form = document.getElementById('formEdit');
        form.action = `/admin/atribut/${atribut.atribut_id}`;
        
        document.getElementById('edit_nama').value = atribut.nama_atribut;
        document.getElementById('edit_komoditas_id').value = atribut.komoditas_id || '';
        document.getElementById('edit_satuan').value = atribut.satuan || '';
        document.getElementById('edit_jenis_field').value = atribut.jenis_field || 'text';
        
        toggleEditSatuan(atribut.jenis_field || 'text');
        
        openModal('modalEdit');
    }

    function openDeleteModal(id, name) {
        const form = document.getElementById('formDelete');
        form.action = `/admin/atribut/${id}`;
        document.getElementById('delete_name').innerText = name;
        openModal('modalDelete');
    }

    window.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            ['modalTambah', 'modalEdit', 'modalDelete'].forEach(id => {
                if (!document.getElementById(id).classList.contains('hidden')) {
                    closeModal(id);
                }
            });
        }
    });


    function toggleSatuan(value) {
        const satuan = document.getElementById('satuan-container');
        satuan.style.display = (value === 'number') ? 'block' : 'none';
    }

    function toggleEditSatuan(value) {
        const satuan = document.getElementById('edit-satuan-container');
        satuan.style.display = (value === 'number') ? 'block' : 'none';
    }
</script>
@endpush
