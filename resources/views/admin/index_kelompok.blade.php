@extends('layouts.app')

@section('title', 'Manajemen Kelompok Binaan')

@section('content')

<!-- Header Section -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h3 class="text-2xl font-bold text-[#1A3636]">Data Kelompok Binaan</h3>
        <p class="text-sm text-gray-500 mt-1">Kelola data kelompok tani dan perizinan perhutanan.</p>
    </div>

    <button onclick="openModal('modalTambah')"
       class="bg-[#4F6F52] text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#1A3636] transition-all duration-300 shadow-lg shadow-[#4F6F52]/20 flex items-center gap-2 group">
        <div class="bg-white/20 p-1 rounded-lg group-hover:bg-white/30 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
        </div>
        Tambah Kelompok
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
                    <th class="py-5 px-8">Nama Kelompok</th>
                    <th class="py-5 px-8">Ketua / Email</th>
                    <th class="py-5 px-8">Penyuluh</th>
                    <th class="py-5 px-8">Izin</th>
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
                                src="{{ $user->foto_profil 
                                    ? asset('storage/' . $user->foto_profil) 
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($user->kelompokBinaan->nama_kelompok ?? $user->nama) . '&background=4F6F52&color=fff' }}"
                                class="w-full h-full object-cover"
                                alt="Foto {{ $user->nama }}"
                            >
                        </div>
                    </td>
                    <td class="py-5 px-8">
                        <button type="button" onclick="openDetailModal({{ json_encode($user) }}, {{ json_encode($user->kelompokBinaan) }})" class="text-left font-bold text-gray-700 hover:text-[#4F6F52] transition-colors focus:outline-none">
                            {{ $user->kelompokBinaan->nama_kelompok ?? '-' }}
                        </button>
                    </td>
                    <td class="py-5 px-8">
                        <div class="flex flex-col">
                            <span class="font-bold text-gray-700 text-xs">{{ $user->nama }}</span>
                            <span class="text-[9px] text-gray-400 font-mono tracking-tight">{{ $user->email }}</span>
                        </div>
                    </td>
                    <td class="py-5 px-8">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-[8px] uppercase">
                                {{ substr($user->kelompokBinaan->penyuluh->user->nama ?? 'B', 0, 1) }}
                            </div>
                            <span class="text-gray-600 text-xs">{{ $user->kelompokBinaan->penyuluh->user->nama ?? 'Belum Ditentukan' }}</span>
                        </div>
                    </td>
                    <td class="py-5 px-8">
                       <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider shadow-sm border 
                            {{ ($user->kelompokUsaha->skema->jenis_kelompok_binaan ?? '') === 'KPS'
                                     ? 'bg-orange-50 text-orange-600 border-orange-100'
                                     : 'bg-indigo-50 text-indigo-600 border-indigo-100' }}">
                                  {{ $user->kelompokUsaha->skema->jenis_kelompok_binaan ?? '-' }}
                            </span>
                    </td>
                    <td class="py-5 px-8">
                        <div class="flex justify-center gap-3">
                            <button onclick="openEditModal({{ json_encode($user) }}, {{ json_encode($user->kelompokBinaan) }})"
                               class="w-9 h-9 flex items-center justify-center text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white rounded-xl transition-all duration-300 shadow-sm" title="Edit Data">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                            </button>
                            <button onclick="openDeleteModal('{{ $user->user_id }}', '{{ $user->kelompokBinaan->nama_kelompok ?? $user->nama }}')"
                               class="w-9 h-9 flex items-center justify-center text-red-600 bg-red-50 hover:bg-red-600 hover:text-white rounded-xl transition-all duration-300 shadow-sm" title="Hapus Data">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-20 text-gray-400 italic">Belum ada data kelompok binaan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Container -->
<div id="modalOverlay" class="fixed inset-0 z-[60] hidden transition-opacity duration-300 bg-black/40 backdrop-blur-[2px]"></div>

<!-- Modal Tambah -->
<div id="modalTambah" class="hidden fixed inset-0 z-[60]  justify-center items-center p-4">
    <div class="bg-white w-full max-w-5xl max-h-[90vh] rounded-3xl shadow-2xl overflow-y-auto transform transition-all duration-300 scale-95 opacity-0 modal-content">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-[#1A3636]">Registrasi Kelompok Baru</h3>
            <button onclick="closeModal('modalTambah')" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 transition-colors text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('kelompok.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            
            {{-- Foto Profil Circular Upload --}}
            <div class="flex flex-col items-center justify-center pb-4 border-b border-gray-50">
                <div class="relative group">
                    <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-[#4F6F52]/20 shadow-md">
                        <img id="tambah-foto-preview" src="https://ui-avatars.com/api/?name=Kelompok&background=4F6F52&color=fff" alt="Foto Profil" class="w-full h-full object-cover">
                    </div>
                    <label for="tambah_foto_profil_input" class="absolute inset-0 rounded-full flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                        </svg>
                    </label>
                </div>
                <input type="file" id="tambah_foto_profil_input" name="foto_profil" accept="image/jpg,image/jpeg,image/png,image/webp" class="hidden">
                <span class="text-[10px] text-gray-400 mt-2">Klik foto untuk upload (Opsional, JPG, PNG, WEBP, maks 2MB)</span>
            </div>

            <div class="grid grid-cols-2 gap-8 mb-6">
                <!-- Col 1 -->
                <div class="space-y-4">
                    <h4 class="text-[10px] font-black text-[#4F6F52] uppercase tracking-[0.2em] mb-2">Akses Akun Ketua</h4>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nama Ketua</label>
                        <input type="text" name="nama" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-[#4F6F52] outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Email</label>
                        <input type="email" name="email" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-[#4F6F52] outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Password</label>
                        <input type="password" name="password" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-[#4F6F52] outline-none text-sm">
                    </div>
                </div>
                <!-- Col 2 -->
                <div class="space-y-4">
                    <h4 class="text-[10px] font-black text-[#4F6F52] uppercase tracking-[0.2em] mb-2">Informasi Kelompok</h4>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nama Kelompok</label>
                        <input type="text" name="nama_kelompok" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-[#4F6F52] outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Penyuluh Pendamping</label>
                        <select name="penyuluh_id" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-[#4F6F52] outline-none text-sm">
                            <option value="">Pilih Penyuluh</option>
                            @foreach($penyuluhs as $p)
                                <option value="{{ $p->penyuluh_id }}">{{ $p->user->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Jenis Izin</label>
                            <select name="jenis_izin" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-[#4F6F52] outline-none text-sm">
                                    <option value="Perhutanan sosial(PS)">PS</option>
                                    <option value="Hutan Kemasyarakatan (HKm)">HKm</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nomor SK</label>
                            <input type="text" name="nomor" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-[#4F6F52] outline-none text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4 mb-8">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Status Kawasan</label>
                    <select name="status_kawasan" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-[#4F6F52] outline-none text-sm">
                        <option value="">Pilih Status Kawasan</option>
                        <option value="Hutan Lindung  (HL)">Hutan Lindung (HL)</option>
                        <option value="Hutan Produksi (HP)">Hutan Produksi (HP)</option>
                        <option value="Hutan Produki yang dapat Dikonversi (HPK)">Hutan Produksi yang dapat Dikonversi (HPK)</option>
                        <option value="Luar Kawasan Hutan/Areal Penggunaan Lain (APL)">Luar Kawasan Hutan (APL)</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Provinsi</label>
                            <select id="create_provinsi" name="provinsi" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-[#4F6F52] outline-none text-xs">
                                <option value="">Pilih Provinsi</option>
                            </select>
                            <input type="hidden" name="provinsi_nama" id="create_provinsi_nama">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Kabupaten</label>
                            <select id="create_kabupaten" name="kabupaten" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-[#4F6F52] outline-none text-xs">
                                <option value="">Pilih Kabupaten</option>
                            </select>
                            <input type="hidden" name="kabupaten_nama" id="create_kabupaten_nama">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Kecamatan</label>
                            <select id="create_kecamatan" name="kecamatan" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-[#4F6F52] outline-none text-xs">
                                <option value="">Pilih Kecamatan</option>
                            </select>
                            <input type="hidden" name="kecamatan_nama" id="create_kecamatan_nama">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Desa</label>
                            <select id="create_desa" name="desa" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-[#4F6F52] outline-none text-xs">
                                <option value="">Pilih Desa</option>
                            </select>
                            <input type="hidden" name="desa_nama" id="create_desa_nama">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Detail Alamat</label>
                        <textarea name="alamat_detail" id="create_alamat_detail" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-[#4F6F52] outline-none text-sm h-20" placeholder="Nama jalan, nomor rumah, RT/RW, dll."></textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Legalitas Perizinan</label>
                        <textarea name="legalitas_perizinan" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-[#4F6F52] outline-none text-sm h-20"></textarea>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeModal('modalTambah')" class="flex-1 px-4 py-2.5 border border-gray-100 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition-all text-xs">Batal</button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-[#4F6F52] text-white font-bold rounded-xl hover:bg-[#1A3636] transition-all shadow-lg shadow-[#4F6F52]/20 text-xs">Daftarkan Kelompok</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail -->
<div id="modalDetail" class="fixed inset-0 z-[70] hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0 modal-content">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-[#1A3636]">Detail Profil Kelompok Binaan</h3>
            <button onclick="closeModal('modalDetail')" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 transition-colors text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        
        {{-- Foto Profil Preview --}}
        <div class="p-6 flex flex-col items-center border-b border-gray-50 bg-gray-50/20">
            <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-[#4F6F52]/20 shadow-md">
                <img id="detail_foto" src="" alt="Foto Profil" class="w-full h-full object-cover">
            </div>
        </div>

        <div class="p-8 grid grid-cols-2 gap-6 text-sm text-gray-600">
            <div class="space-y-4">
                <div>
                    <p class="text-[10px] uppercase font-bold tracking-[0.2em] text-gray-400">Nama Kelompok</p>
                    <p id="detail_nama_kelompok" class="font-bold text-gray-800"></p>
                </div>
                <div>
                    <p class="text-[10px] uppercase font-bold tracking-[0.2em] text-gray-400">Ketua</p>
                    <p id="detail_nama" class="font-medium text-gray-700"></p>
                </div>
                <div>
                    <p class="text-[10px] uppercase font-bold tracking-[0.2em] text-gray-400">Email Ketua</p>
                    <p id="detail_email" class="font-medium text-gray-700"></p>
                </div>
                <div>
                    <p class="text-[10px] uppercase font-bold tracking-[0.2em] text-gray-400">Penyuluh Pendamping</p>
                    <p id="detail_penyuluh" class="font-medium text-gray-700"></p>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <p class="text-[10px] uppercase font-bold tracking-[0.2em] text-gray-400">Jenis Izin</p>
                    <p id="detail_jenis_izin" class="font-medium text-gray-700"></p>
                </div>
                <div>
                    <p class="text-[10px] uppercase font-bold tracking-[0.2em] text-gray-400">Nomor SK</p>
                    <p id="detail_nomor" class="font-medium text-gray-700"></p>
                </div>
                <div>
                    <p class="text-[10px] uppercase font-bold tracking-[0.2em] text-gray-400">Status Kawasan</p>
                    <p id="detail_status_kawasan" class="font-medium text-gray-700"></p>
                </div>
                <div>
                    <p class="text-[10px] uppercase font-bold tracking-[0.2em] text-gray-400">Alamat Lengkap</p>
                    <p id="detail_alamat_lengkap" class="font-medium text-gray-700 whitespace-pre-line text-xs"></p>
                </div>
                <div>
                    <p class="text-[10px] uppercase font-bold tracking-[0.2em] text-gray-400">Legalitas Perizinan</p>
                    <p id="detail_legalitas_perizinan" class="font-medium text-gray-700 whitespace-pre-line text-xs"></p>
                </div>
            </div>
        </div>
        <div class="p-8 border-t border-gray-100 flex gap-3">
            <button type="button" onclick="closeModal('modalDetail'); openEditModal(window.detailUser, window.detailKelompok);" class="flex-1 px-6 py-3 bg-[#4F6F52] text-white font-bold rounded-2xl hover:bg-[#1A3636] transition-all shadow-lg shadow-[#4F6F52]/20 text-xs">Edit Profil</button>
            <button type="button" onclick="closeModal('modalDetail')" class="flex-1 px-6 py-3 border border-gray-100 text-gray-500 font-bold rounded-2xl hover:bg-gray-50 transition-all text-xs">Tutup</button>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div id="modalEdit" class="fixed inset-0 z-[70] hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0 modal-content">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-[#1A3636]">Edit Data Kelompok</h3>
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
                        <img id="edit-foto-preview" src="https://ui-avatars.com/api/?name=Kelompok&background=2563eb&color=fff" alt="Foto Profil" class="w-full h-full object-cover">
                    </div>
                    <label for="edit_foto_profil_input" class="absolute inset-0 rounded-full flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                        </svg>
                    </label>
                </div>
                <input type="file" id="edit_foto_profil_input" name="foto_profil" accept="image/jpg,image/jpeg,image/png,image/webp" class="hidden">
                <input type="hidden" name="hapus_foto" id="edit_hapus_foto_input" value="0">
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-[10px] text-gray-400">Klik foto untuk ganti</span>
                    <button type="button" id="btn-hapus-foto-edit" class="text-[10px] text-red-500 hover:text-red-700 font-semibold hidden">| Hapus Foto</button>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8 mb-6">
                <!-- Col 1 -->
                <div class="space-y-4">
                    <h4 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] mb-2">Update Akun Ketua</h4>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nama Ketua</label>
                        <input type="text" name="nama" id="edit_nama" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Email</label>
                        <input type="email" name="email" id="edit_email" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Password Baru (Opsional)</label>
                        <input type="password" name="password" placeholder="Kosongkan jika tidak diubah" class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>
                </div>
                <!-- Col 2 -->
                <div class="space-y-4">
                    <h4 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] mb-2">Update Data Kelompok</h4>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nama Kelompok</label>
                        <input type="text" name="nama_kelompok" id="edit_nama_kelompok" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Penyuluh Pendamping</label>
                        <select name="penyuluh_id" id="edit_penyuluh_id" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                            @foreach($penyuluhs as $p)
                                <option value="{{ $p->penyuluh_id }}">{{ $p->user->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Jenis Izin</label>
                            <select name="jenis_izin" id="edit_jenis_izin" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                                <option value="Perhutanan sosial(PS)">PS</option>
                                <option value="Hutan Kemasyarakatan (HKm)">HKm</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nomor SK</label>
                            <input type="text" name="nomor" id="edit_nomor" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4 mb-8">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Status Kawasan</label>
                    <select name="status_kawasan" id="edit_status_kawasan" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                        <option value="Hutan Lindung  (HL)">Hutan Lindung (HL)</option>
                        <option value="Hutan Produksi (HP)">Hutan Produksi (HP)</option>
                        <option value="Hutan Produki yang dapat Dikonversi (HPK)">Hutan Produksi yang dapat Dikonversi (HPK)</option>
                        <option value="Luar Kawasan Hutan/Areal Penggunaan Lain (APL)">Luar Kawasan Hutan (APL)</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-6">
                   <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Provinsi</label>
                            <select id="edit_provinsi" name="provinsi" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-xs">
                                <option value="">Pilih Provinsi</option>
                            </select>
                            <input type="hidden" name="provinsi_nama" id="edit_provinsi_nama">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Kabupaten</label>
                            <select id="edit_kabupaten" name="kabupaten" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-xs">
                                <option value="">Pilih Kabupaten</option>
                            </select>
                            <input type="hidden" name="kabupaten_nama" id="edit_kabupaten_nama">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Kecamatan</label>
                            <select id="edit_kecamatan" name="kecamatan" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-xs">
                                <option value="">Pilih Kecamatan</option>
                            </select>
                            <input type="hidden" name="kecamatan_nama" id="edit_kecamatan_nama">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Desa</label>
                            <select id="edit_desa" name="desa" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-xs">
                                <option value="">Pilih Desa</option>
                            </select>
                            <input type="hidden" name="desa_nama" id="edit_desa_nama">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Detail Alamat</label>
                        <textarea name="alamat_detail" id="edit_alamat_detail" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm h-20" placeholder="Nama jalan, nomor rumah, RT/RW, dll."></textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Legalitas Perizinan</label>
                        <textarea name="legalitas_perizinan" id="edit_legalitas_perizinan" required class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm h-20"></textarea>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeModal('modalEdit')" class="flex-1 px-4 py-2.5 border border-gray-100 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition-all text-xs">Batal</button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/20 text-xs">Update Kelompok</button>
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
            <h3 class="text-xl font-bold text-gray-800 mb-2">Hapus Kelompok Binaan?</h3>
            <p class="text-gray-500 text-sm mb-8 px-4">Seluruh data kelompok <span id="delete_name" class="font-bold text-gray-800"></span> akan dihapus dari sistem.</p>
            
            <form id="formDelete" action="" method="POST" class="flex gap-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeModal('modalDelete')" class="flex-1 px-6 py-3 border border-gray-100 text-gray-400 font-bold rounded-2xl hover:bg-gray-50 transition-all">Batal</button>
                <button type="submit" class="flex-1 px-6 py-3 bg-red-600 text-white font-bold rounded-2xl hover:bg-red-700 transition-all shadow-lg shadow-red-600/20">Konfirmasi Hapus</button>
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
        if (confirm('Yakin ingin menghapus foto profil kelompok ini?')) {
            const namaKelompok = document.getElementById('edit_nama_kelompok').value || 'Kelompok';
            editFotoPreview.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(namaKelompok)}&background=2563eb&color=fff`;
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

    function openDetailModal(user, kelompok) {
        window.detailUser = user;
        window.detailKelompok = kelompok;

        document.getElementById('detail_nama_kelompok').innerText = kelompok?.nama_kelompok ?? '-';
        document.getElementById('detail_nama').innerText = user.nama;
        document.getElementById('detail_email').innerText = user.email;
        document.getElementById('detail_penyuluh').innerText = kelompok?.penyuluh?.user?.nama ?? '-';
        document.getElementById('detail_jenis_izin').innerText = kelompok?.jenis_izin ?? '-';
        document.getElementById('detail_nomor').innerText = kelompok?.nomor ?? '-';
        document.getElementById('detail_status_kawasan').innerText = kelompok?.status_kawasan ?? '-';
        document.getElementById('detail_legalitas_perizinan').innerText = kelompok?.legalitas_perizinan ?? '-';

        // Tampilkan foto profil
        const detailFoto = document.getElementById('detail_foto');
        if (user.foto_profil) {
            detailFoto.src = `/storage/${user.foto_profil}`;
        } else {
            detailFoto.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(kelompok?.nama_kelompok ?? user.nama)}&background=4F6F52&color=fff`;
        }

        // Format alamat lengkap agar readable
        let addressText = '-';
        if (kelompok && kelompok.alamat_lengkap) {
            try {
                const addr = typeof kelompok.alamat_lengkap === 'string' ? JSON.parse(kelompok.alamat_lengkap) : kelompok.alamat_lengkap;
                addressText = `${addr.detail ?? ''}\nDesa: ${addr.desa?.nama ?? ''}, Kec: ${addr.kecamatan?.nama ?? ''}\nKab: ${addr.kabupaten?.nama ?? ''}, Prov: ${addr.provinsi?.nama ?? ''}`;
            } catch(e) {
                addressText = kelompok.alamat_lengkap;
            }
        }
        document.getElementById('detail_alamat_lengkap').innerText = addressText;

        openModal('modalDetail');
    }

    function openEditModal(user, kelompok) {
        const form = document.getElementById('formEdit');
        form.action = `/admin/kelompok/${user.user_id}`;
        
        document.getElementById('edit_nama').value = user.nama;
        document.getElementById('edit_email').value = user.email;
        
        // Reset file input dan hapus_foto state
        editFotoInput.value = '';
        editHapusFotoInput.value = '0';
        
        // Atur preview foto
        if (user.foto_profil) {
            editFotoPreview.src = `/storage/${user.foto_profil}`;
            btnHapusFotoEdit.classList.remove('hidden');
        } else {
            editFotoPreview.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(kelompok?.nama_kelompok ?? user.nama)}&background=2563eb&color=fff`;
            btnHapusFotoEdit.classList.add('hidden');
        }
        
        if (kelompok) {
            document.getElementById('edit_nama_kelompok').value = kelompok.nama_kelompok;
            document.getElementById('edit_penyuluh_id').value = kelompok.penyuluh_id;
            document.getElementById('edit_jenis_izin').value = kelompok.jenis_izin;
            document.getElementById('edit_nomor').value = kelompok.nomor;
            document.getElementById('edit_status_kawasan').value = kelompok.status_kawasan;
            document.getElementById('edit_legalitas_perizinan').value = kelompok.legalitas_perizinan;
            
            let savedAddress = null;
            if (kelompok.alamat_lengkap) {
                try {
                    savedAddress = typeof kelompok.alamat_lengkap === 'string' ? JSON.parse(kelompok.alamat_lengkap) : kelompok.alamat_lengkap;
                } catch(e) {
                    savedAddress = { detail: kelompok.alamat_lengkap };
                }
            }
            initRegionSelects('edit', savedAddress);
        }
        
        openModal('modalEdit');
    }

    function initRegionSelects(prefix, savedData = null) {
        const provSelect = document.getElementById(`${prefix}_provinsi`);
        const kabSelect = document.getElementById(`${prefix}_kabupaten`);
        const kecSelect = document.getElementById(`${prefix}_kecamatan`);
        const desSelect = document.getElementById(`${prefix}_desa`);
        const detailTextarea = document.getElementById(`${prefix}_alamat_detail`);
        
        const provNamaInput = document.getElementById(`${prefix}_provinsi_nama`);
        const kabNamaInput = document.getElementById(`${prefix}_kabupaten_nama`);
        const kecNamaInput = document.getElementById(`${prefix}_kecamatan_nama`);
        const desNamaInput = document.getElementById(`${prefix}_desa_nama`);

        function resetSelect(select, placeholder) {
            select.innerHTML = `<option value="">${placeholder}</option>`;
        }

        // Load Provinsi
        fetch('/wilayah/provinsi')
            .then(res => res.json())
            .then(data => {
                resetSelect(provSelect, 'Pilih Provinsi');
                data.forEach(p => {
                    const opt = document.createElement('option');
                    opt.value = p.code;
                    opt.textContent = p.name;
                    provSelect.appendChild(opt);
                });

                if (savedData && savedData.provinsi) {
                    provSelect.value = savedData.provinsi.id;
                    if (provNamaInput) provNamaInput.value = savedData.provinsi.nama;
                    loadKabupaten(savedData.provinsi.id, savedData.kabupaten);
                }
            })
            .catch(err => console.error('Gagal mengambil data provinsi:', err));

        provSelect.onchange = function() {
            const val = this.value;
            const txt = this.options[this.selectedIndex].text;
            if (provNamaInput) provNamaInput.value = val ? txt : '';

            resetSelect(kabSelect, 'Pilih Kabupaten');
            resetSelect(kecSelect, 'Pilih Kecamatan');
            resetSelect(desSelect, 'Pilih Desa');
            if (val) {
                loadKabupaten(val);
            }
        };

        function loadKabupaten(provId, savedKab = null) {
            fetch(`/wilayah/kabupaten/${provId}`)
                .then(res => res.json())
                .then(data => {
                    resetSelect(kabSelect, 'Pilih Kabupaten');
                    data.forEach(k => {
                        const opt = document.createElement('option');
                        opt.value = k.code;
                        opt.textContent = k.name;
                        kabSelect.appendChild(opt);
                    });

                    if (savedKab) {
                        kabSelect.value = savedKab.id;
                        if (kabNamaInput) kabNamaInput.value = savedKab.nama;
                        loadKecamatan(savedKab.id, savedData.kecamatan);
                    }
                })
                .catch(err => console.error('Gagal mengambil data kabupaten:', err));
        }

        kabSelect.onchange = function() {
            const val = this.value;
            const txt = this.options[this.selectedIndex].text;
            if (kabNamaInput) kabNamaInput.value = val ? txt : '';

            resetSelect(kecSelect, 'Pilih Kecamatan');
            resetSelect(desSelect, 'Pilih Desa');
            if (val) {
                loadKecamatan(val);
            }
        };

        function loadKecamatan(kabId, savedKec = null) {
            fetch(`/wilayah/kecamatan/${kabId}`)
                .then(res => res.json())
                .then(data => {
                    resetSelect(kecSelect, 'Pilih Kecamatan');
                    data.forEach(k => {
                        const opt = document.createElement('option');
                        opt.value = k.code;
                        opt.textContent = k.name;
                        kecSelect.appendChild(opt);
                    });

                    if (savedKec) {
                        kecSelect.value = savedKec.id;
                        if (kecNamaInput) kecNamaInput.value = savedKec.nama;
                        loadDesa(savedKec.id, savedData.desa);
                    }
                })
                .catch(err => console.error('Gagal mengambil data kecamatan:', err));
        }

        kecSelect.onchange = function() {
            const val = this.value;
            const txt = this.options[this.selectedIndex].text;
            if (kecNamaInput) kecNamaInput.value = val ? txt : '';

            resetSelect(desSelect, 'Pilih Desa');
            if (val) {
                loadDesa(val);
            }
        };

        function loadDesa(kecId, savedDesa = null) {
            fetch(`/wilayah/desa/${kecId}`)
                .then(res => res.json())
                .then(data => {
                    resetSelect(desSelect, 'Pilih Desa');
                    data.forEach(d => {
                        const opt = document.createElement('option');
                        opt.value = d.code;
                        opt.textContent = d.name;
                        desSelect.appendChild(opt);
                    });

                    if (savedDesa) {
                        desSelect.value = savedDesa.id;
                        if (desNamaInput) desNamaInput.value = savedDesa.nama;
                    }
                })
                .catch(err => console.error('Gagal mengambil data desa:', err));
        }

        desSelect.onchange = function() {
            const val = this.value;
            const txt = this.options[this.selectedIndex].text;
            if (desNamaInput) desNamaInput.value = val ? txt : '';
        };

        if (savedData && savedData.detail && detailTextarea) {
            detailTextarea.value = savedData.detail;
        } else if (detailTextarea) {
            detailTextarea.value = '';
        }
    }

    function openDeleteModal(id, name) {
        const form = document.getElementById('formDelete');
        form.action = `/admin/kelompok/${id}`;
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

    document.addEventListener('DOMContentLoaded', function() {
        initRegionSelects('create');
    });
</script>
@endpush
