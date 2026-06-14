@extends('layouts.app')

@section('title', 'Manajemen Penyuluh')

@section('content')

<!-- Header Section -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h3 class="text-2xl font-bold text-[#1A3636]">Data Penyuluh</h3>
        <p class="text-sm text-gray-500 mt-1">Kelola akun penyuluh dan profil NIP mereka.</p>
    </div>

    <button onclick="openModal('modalTambah')"
       class="bg-[#4F6F52] text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#1A3636] transition-all duration-300 shadow-lg shadow-[#4F6F52]/20 flex items-center gap-2 group">
        <div class="bg-white/20 p-1 rounded-lg group-hover:bg-white/30 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
        </div>
        Tambah Penyuluh
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

@if ($errors->any())
<div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-8 rounded-xl shadow-sm animate-in slide-in-from-top duration-300">
    <div class="flex items-center gap-3 mb-2">
        <div class="bg-red-500 text-white p-1 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </div>
        <span class="text-sm font-bold">Terjadi kesalahan:</span>
    </div>
    <ul class="list-disc list-inside text-xs space-y-1 ml-7">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Table Card -->
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-500 hover:shadow-md">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-gray-50/50 text-gray-400 font-bold uppercase tracking-widest text-[10px] border-b border-gray-100">
                    <th class="py-5 px-8">No</th>
                    <th class="py-5 px-8">Foto</th>
                    <th class="py-5 px-8">Nama</th>
                    <th class="py-5 px-8">NIP</th>
                    <th class="py-5 px-8">Jabatan</th>
                    <th class="py-5 px-8">Email</th>
                    <th class="py-5 px-8">No. HP</th>
                    <th class="py-5 px-8 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $index => $user)
                <tr class="hover:bg-gray-50/80 transition-all duration-200 group">
                    <td class="py-5 px-8 text-gray-400 font-medium">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                    <td class="py-5 px-8">
                        <div class="relative w-10 h-10 rounded-full overflow-hidden border border-gray-100 shadow-sm transition-transform duration-300 hover:scale-105 hover:shadow-md">
                            <img 
                                src="{{ $user->foto_profile 
                                    ? asset('storage/' . $user->foto_profile) 
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($user->nama) . '&background=4F6F52&color=fff' }}"
                                class="w-full h-full object-cover"
                                alt="Foto {{ $user->nama }}"
                            >
                        </div>
                    </td>
                    <td class="py-5 px-8">
                        <button type="button" onclick="openDetailModal({{ json_encode($user) }}, {{ json_encode($user->penyuluh) }})" class="text-left font-bold text-gray-700 hover:text-[#4F6F52] transition-colors focus:outline-none">
                            {{ $user->nama }}
                        </button>
                    </td>
                    <td class="py-5 px-8 text-gray-600 font-medium">{{ $user->penyuluh->nip_penyuluh ?? '-' }}</td>
                    <td class="py-5 px-8 text-gray-500 text-xs">{{ $user->jabatan ?? '-' }}</td>
                    <td class="py-5 px-8 text-gray-500">{{ $user->email }}</td>
                    <td class="py-5 px-8 text-gray-500">{{ $user->contact_person ?? '-' }}</td>
                    <td class="py-5 px-8">
                        <div class="flex justify-center gap-3">
                            <button onclick="openEditModal({{ json_encode($user) }}, {{ json_encode($user->penyuluh) }})"
                               class="w-9 h-9 flex items-center justify-center text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white rounded-xl transition-all duration-300 shadow-sm" title="Edit Data">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                            </button>
                            <button onclick="openDeleteModal('{{ $user->user_id }}', '{{ $user->nama }}')"
                               class="w-9 h-9 flex items-center justify-center text-red-600 bg-red-50 hover:bg-red-600 hover:text-white rounded-xl transition-all duration-300 shadow-sm" title="Hapus Data">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-20 text-gray-400">Belum ada data penyuluh</td>
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
            <h3 class="font-bold text-[#1A3636]">Tambah Penyuluh Baru</h3>
            <button onclick="closeModal('modalTambah')" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 transition-colors text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('penyuluh.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            
            {{-- Foto Profil Circular Upload --}}
            <div class="flex flex-col items-center justify-center pb-4 border-b border-gray-50">
                <div class="relative group">
                    <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-[#4F6F52]/20 shadow-md">
                        <img id="tambah-foto-preview" src="https://ui-avatars.com/api/?name=Penyuluh&background=4F6F52&color=fff" alt="Foto Profil" class="w-full h-full object-cover">
                    </div>
                    <label for="tambah_foto_profil_input" class="absolute inset-0 rounded-full flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                        </svg>
                    </label>
                </div>
                <input type="file" id="tambah_foto_profil_input" name="foto_profile" accept="image/jpg,image/jpeg,image/png,image/webp" class="hidden">
                <span class="text-[10px] text-gray-400 mt-2">Klik foto untuk upload (JPG, PNG, WEBP, maks 2MB)</span>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nama Lengkap</label>
                <input type="text" name="nama" required placeholder="Nama lengkap penyuluh" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-4 focus:ring-[#4F6F52]/10 outline-none transition-all text-sm">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">NIP (Nomor Induk Pegawai)</label>
                <input type="text" name="nip_penyuluh" required placeholder="Masukkan NIP (Maks 10 karakter)" maxlength="10" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-4 focus:ring-[#4F6F52]/10 outline-none transition-all text-sm">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Jabatan</label>
                <input type="text" name="jabatan" required placeholder="Contoh: Penyuluh Kehutanan Ahli Pertama" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-4 focus:ring-[#4F6F52]/10 outline-none transition-all text-sm">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nomor HP / Contact Person</label>
                <input type="text" name="contact_person" placeholder="Contoh: 081234567890" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-4 focus:ring-[#4F6F52]/10 outline-none transition-all text-sm">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Email Address</label>
                <input type="email" name="email" required placeholder="email@contoh.com" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-4 focus:ring-[#4F6F52]/10 outline-none transition-all text-sm">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Password</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-4 focus:ring-[#4F6F52]/10 outline-none transition-all text-sm">
            </div>
            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeModal('modalTambah')" class="flex-1 px-4 py-2.5 border border-gray-100 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition-all text-xs">Batal</button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-[#4F6F52] text-white font-bold rounded-xl hover:bg-[#1A3636] transition-all shadow-lg shadow-[#4F6F52]/20 text-xs">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail -->
<div id="modalDetail" class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0 modal-content">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-[#1A3636]">Detail Profil Penyuluh</h3>
            <button onclick="closeModal('modalDetail')" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 transition-colors text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <div class="p-8 space-y-5 text-sm text-gray-600 flex flex-col items-center">
            {{-- Foto Profil Preview --}}
            <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-[#4F6F52]/20 shadow-md">
                <img id="detail_foto" src="" alt="Foto Profil" class="w-full h-full object-cover">
            </div>
            
            <div class="w-full space-y-4">
                <div>
                    <p class="text-[10px] uppercase font-bold tracking-[0.2em] text-gray-400">Nama</p>
                    <p id="detail_nama" class="font-bold text-gray-800 mt-0.5"></p>
                </div>
                <div>
                    <p class="text-[10px] uppercase font-bold tracking-[0.2em] text-gray-400">Email</p>
                    <p id="detail_email" class="font-medium text-gray-700 mt-0.5"></p>
                </div>
                <div>
                    <p class="text-[10px] uppercase font-bold tracking-[0.2em] text-gray-400">NIP</p>
                    <p id="detail_nip" class="font-medium text-gray-700 mt-0.5"></p>
                </div>
                <div>
                    <p class="text-[10px] uppercase font-bold tracking-[0.2em] text-gray-400">Jabatan</p>
                    <p id="detail_jabatan" class="font-medium text-gray-700 mt-0.5"></p>
                </div>
                <div>
                    <p class="text-[10px] uppercase font-bold tracking-[0.2em] text-gray-400">No. HP / Contact Person</p>
                    <p id="detail_contact_person" class="font-medium text-gray-700 mt-0.5"></p>
                </div>
            </div>
            <div class="flex gap-3 pt-4 w-full">
                <button type="button" onclick="closeModal('modalDetail'); openEditModal(window.detailUser, window.detailPenyuluh);" class="flex-1 px-6 py-3 bg-[#4F6F52] text-white font-bold rounded-2xl hover:bg-[#1A3636] transition-all shadow-lg shadow-[#4F6F52]/20 text-xs">Edit Profil</button>
                <button type="button" onclick="closeModal('modalDetail')" class="flex-1 px-6 py-3 border border-gray-100 text-gray-500 font-bold rounded-2xl hover:bg-gray-50 transition-all text-xs">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div id="modalEdit" class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0 modal-content">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-[#1A3636]">Edit Data Penyuluh</h3>
            <button onclick="closeModal('modalEdit')" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 transition-colors text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <form id="formEdit" action="" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            {{-- Foto Profil Circular Upload Edit --}}
            <div class="flex flex-col items-center justify-center pb-4 border-b border-gray-50">
                <div class="relative group">
                    <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-blue-500/20 shadow-md">
                        <img id="edit-foto-preview" src="https://ui-avatars.com/api/?name=Penyuluh&background=2563eb&color=fff" alt="Foto Profil" class="w-full h-full object-cover">
                    </div>
                    <label for="edit_foto_profil_input" class="absolute inset-0 rounded-full flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                        </svg>
                    </label>
                </div>
                <input type="file" id="edit_foto_profil_input" name="foto_profile" accept="image/jpg,image/jpeg,image/png,image/webp" class="hidden">
                <input type="hidden" name="hapus_foto" id="edit_hapus_foto_input" value="0">
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-[10px] text-gray-400">Klik foto untuk ganti</span>
                    <button type="button" id="btn-hapus-foto-edit" class="text-[10px] text-red-500 hover:text-red-700 font-semibold hidden">| Hapus Foto</button>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nama Lengkap</label>
                <input type="text" name="nama" id="edit_nama" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 outline-none transition-all text-sm">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">NIP</label>
                <input type="text" name="nip_penyuluh" id="edit_nip" required maxlength="10" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 outline-none transition-all text-sm">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Jabatan</label>
                <input type="text" name="jabatan" id="edit_jabatan" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 outline-none transition-all text-sm">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nomor HP / Contact Person</label>
                <input type="text" name="contact_person" id="edit_contact_person" placeholder="Contoh: 081234567890" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 outline-none transition-all text-sm">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Email Address</label>
                <input type="email" name="email" id="edit_email" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 outline-none transition-all text-sm">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Password Baru (Opsional)</label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak diubah" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 outline-none transition-all text-sm">
            </div>
            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeModal('modalEdit')" class="flex-1 px-4 py-2.5 border border-gray-100 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition-all text-xs">Batal</button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/20 text-xs">Update</button>
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
            <h3 class="text-xl font-bold text-gray-800 mb-2">Hapus Penyuluh?</h3>
            <p class="text-gray-500 text-sm mb-8 px-4">Anda akan menghapus data <span id="delete_name" class="font-bold text-gray-800"></span> secara permanen.</p>
            
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
    // Preview upload foto pada modal Tambah
    const tambahFotoInput = document.getElementById('tambah_foto_profil_input');
    const tambahFotoPreview = document.getElementById('tambah-foto-preview');
    tambahFotoInput?.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                tambahFotoPreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Preview upload foto pada modal Edit
    const editFotoInput = document.getElementById('edit_foto_profil_input');
    const editFotoPreview = document.getElementById('edit-foto-preview');
    const editHapusFotoInput = document.getElementById('edit_hapus_foto_input');
    const btnHapusFotoEdit = document.getElementById('btn-hapus-foto-edit');

    editFotoInput?.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                editFotoPreview.src = e.target.result;
                editHapusFotoInput.value = '0';
                btnHapusFotoEdit.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    btnHapusFotoEdit?.addEventListener('click', function () {
        if (confirm('Yakin ingin menghapus foto profil penyuluh ini?')) {
            const nama = document.getElementById('edit_nama').value;
            editFotoPreview.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(nama)}&background=2563eb&color=fff`;
            editFotoInput.value = '';
            editHapusFotoInput.value = '1';
            btnHapusFotoEdit.classList.add('hidden');
        }
    });

    function openModal(id) {
        const overlay = document.getElementById('modalOverlay');
        const modal = document.getElementById(id);
        const content = modal.querySelector('.modal-content');

        overlay.classList.remove('hidden');
        modal.classList.remove('hidden');
        
        setTimeout(() => {
            overlay.classList.remove('opacity-0');
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

    function openDetailModal(user, penyuluh) {
        window.detailUser = user;
        window.detailPenyuluh = penyuluh;

        document.getElementById('detail_nama').innerText = user.nama;
        document.getElementById('detail_email').innerText = user.email;
        document.getElementById('detail_nip').innerText = penyuluh?.nip_penyuluh ?? '-';
        document.getElementById('detail_jabatan').innerText = user?.jabatan ?? '-';
        document.getElementById('detail_contact_person').innerText = user?.contact_person ?? '-';

        const detailFoto = document.getElementById('detail_foto');
        if (user.foto_profile) {
            detailFoto.src = `/storage/${user.foto_profile}`;
        } else {
            detailFoto.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(user.nama)}&background=4F6F52&color=fff`;
        }

        openModal('modalDetail');
    }

    function openEditModal(user, penyuluh) {
        const form = document.getElementById('formEdit');
        form.action = `/admin/penyuluh/${user.user_id}`;
        
        document.getElementById('edit_nama').value = user.nama;
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_contact_person').value = user.contact_person || '';
        document.getElementById('edit_jabatan').value = user.jabatan || '';
        
        // Reset file input dan hapus_foto state
        editFotoInput.value = '';
        editHapusFotoInput.value = '0';
        
        // Atur preview foto
        if (user.foto_profile) {
            editFotoPreview.src = `/storage/${user.foto_profile}`;
            btnHapusFotoEdit.classList.remove('hidden');
        } else {
            editFotoPreview.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(user.nama)}&background=2563eb&color=fff`;
            btnHapusFotoEdit.classList.add('hidden');
        }
        
        if (penyuluh) {
            document.getElementById('edit_nip').value = penyuluh.nip_penyuluh;
        }
        
        openModal('modalEdit');
    }

    function openDeleteModal(id, name) {
        const form = document.getElementById('formDelete');
        form.action = `/admin/penyuluh/${id}`;
        document.getElementById('delete_name').innerText = name;
        openModal('modalDelete');
    }

    window.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            ['modalTambah', 'modalEdit', 'modalDelete', 'modalDetail'].forEach(id => {
                const modal = document.getElementById(id);
                if (modal && !modal.classList.contains('hidden')) {
                    closeModal(id);
                }
            });
        }
    });
</script>
@endpush
