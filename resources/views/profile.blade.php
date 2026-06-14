@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
@php
    $role = auth()->user()->role->role_name;
    $kelompok = auth()->user()->kelompokBinaan ?? null;
    $penyuluh = auth()->user()->penyuluh ?? null;
@endphp

<div class="max-w-7xl mx-auto space-y-8 px-4">

    {{-- HEADER --}}
    <div class="flex items-center gap-4">
        <div>
            <h1 class="text-4xl font-black text-[#1A3636]">Edit Profil</h1>
           <p class="text-base text-gray-500 mt-2">Perbarui informasi akun dan foto profil Anda.</p>
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div id="alert-success" class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-green-500 text-white p-1 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <span class="text-sm font-bold">{{ session('success') }}</span>
            </div>
            <button onclick="document.getElementById('alert-success').remove()" class="text-green-500 hover:text-green-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="bg-red-500 text-white p-1 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </div>
                <span class="text-sm font-bold">Terjadi kesalahan:</span>
            </div>
            <ul class="list-disc list-inside text-xs space-y-1 ml-7">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- KOLOM KIRI: FOTO PROFIL --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex flex-col items-center text-center space-y-5">
                    <h3 class="text-sm font-black text-[#4F6F52] uppercase tracking-[0.2em] self-start">Foto Profil</h3>

                    {{-- Preview Foto --}}
                    <div class="relative group">
                        <div id="foto-preview-container" class="w-52 h-52 rounded-full overflow-hidden border-4 border-[#4F6F52]/20 shadow-lg">
                            @if($user->foto_profil)
                                <img id="foto-preview" src="{{ asset('storage/' . $user->foto_profil) }}"
                                     alt="Foto Profil"
                                     class="w-full h-full object-cover">
                            @else
                                <div id="foto-initial" class="w-full h-full bg-gradient-to-br from-[#4F6F52] to-[#1A3636] flex items-center justify-center text-white font-black text-4xl">
                                    {{ strtoupper(substr($user->nama, 0, 1)) }}
                                </div>
                                <img id="foto-preview" src="" alt="Foto Profil" class="w-full h-full object-cover hidden">
                            @endif
                        </div>

                        {{-- Overlay kamera --}}
                        <label for="foto_profil_input" class="absolute inset-0 rounded-full flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                            </svg>
                        </label>
                    </div>

                    <input type="file" id="foto_profil_input" name="foto_profil" accept="image/jpg,image/jpeg,image/png,image/webp" class="hidden">
                    <input type="hidden" name="hapus_foto" id="hapus_foto_input" value="0">

                    <p class="text-[11px] text-gray-400">Klik foto untuk ganti gambar.<br>Format: JPG, PNG, WEBP · Maks: 2MB</p>

                    {{-- Nama & Role Badge --}}
                    <div class="pt-2 border-t border-gray-50 w-full">
                        <p class="font-bold text-gray-800 text-xl">{{ $user->nama }}</p>
                        <span class="mt-1 inline-block px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider
                            @if($role === 'Penyuluh') bg-blue-50 text-blue-600 border border-blue-100
                            @elseif($role === 'Kelompok Binaan') bg-[#4F6F52]/10 text-[#4F6F52] border border-[#4F6F52]/20
                            @else bg-gray-100 text-gray-500 border border-gray-200 @endif">
                            {{ $role }}
                        </span>
                    </div>

                    @if($user->foto_profil)
                        <button type="button" id="btn-hapus-foto"
                            class="text-xs text-red-500 hover:text-red-700 font-semibold flex items-center gap-1.5 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                            Hapus Foto
                        </button>
                    @else
                        <button type="button" id="btn-hapus-foto" class="hidden text-xs text-red-500 hover:text-red-700 font-semibold flex items-center gap-1.5 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                            Hapus Foto
                        </button>
                    @endif
                </div>
            </div>

            {{-- KOLOM KANAN: FORM --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- INFORMASI AKUN --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 space-y-5">
                    <h3 class="text-sm font-black text-[#4F6F52] uppercase tracking-[0.2em]">Informasi Akun</h3>

                    <div>
                        <label class="block text-sm font-bold text-gray-400 uppercase mb-1.5">Nama Lengkap</label>
                        <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" required
                            class="w-full px-4 py-4 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-[#4F6F52] outline-none text-sm transition">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-400 uppercase mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full px-4 py-4 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-[#4F6F52] outline-none text-sm transition">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-400 uppercase mb-1.5">Password Baru <span class="text-gray-300">(Opsional)</span></label>
                            <input type="password"name="password" autocomplete="new-password"placeholder="Kosongkan jika tidak diubah"
                                class="w-full px-4 py-4 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-[#4F6F52] outline-none text-sm transition">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-400 uppercase mb-1.5">Konfirmasi Password</label>
                            <input type="password"name="password_confirmation"autocomplete="new-password" placeholder="Ulangi password baru"
                                class="w-full px-4 py-4 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-[#4F6F52] outline-none text-sm transition">
                        </div>
                    </div>
                </div>

                {{-- INFO TAMBAHAN: KELOMPOK BINAAN --}}
                @if($role === 'Kelompok Binaan' && $kelompok)
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 space-y-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-black text-[#4F6F52] uppercase tracking-[0.2em]">Informasi Kelompok</h3>
                        <span class="text-[9px] font-bold text-gray-400 bg-gray-50 px-2 py-1 rounded-lg border border-gray-100 uppercase tracking-wider">Hanya Baca</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-400 uppercase mb-1.5">Nama Kelompok</label>
                            <div class="w-full px-4 py-4 bg-gray-50/60 border border-gray-100 rounded-xl text-sm text-gray-600 font-medium">
                                {{ $kelompok->nama_kelompok ?? '-' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-400 uppercase mb-1.5">Penyuluh Pendamping</label>
                            <div class="w-full px-4 py-4 bg-gray-50/60 border border-gray-100 rounded-xl text-sm text-gray-600 font-medium">
                                {{ $kelompok->penyuluh->user->nama ?? '-' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-400 uppercase mb-1.5">Jenis Izin</label>
                            <div class="w-full px-4 py-4 bg-gray-50/60 border border-gray-100 rounded-xl text-sm text-gray-600 font-medium">
                                {{ $kelompok->jenis_izin ?? '-' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-400 uppercase mb-1.5">Nomor SK</label>
                            <div class="w-full px-4 py-4 bg-gray-50/60 border border-gray-100 rounded-xl text-sm text-gray-600 font-medium">
                                {{ $kelompok->nomor ?? '-' }}
                            </div>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-gray-400 uppercase mb-1.5">Alamat Lengkap</label>
                            <div class="w-full px-4 py-4 bg-gray-50/60 border border-gray-100 rounded-xl text-sm text-gray-600 font-medium leading-relaxed">
                                {{ $kelompok->alamat_lengkap ?? '-' }}
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 italic">Untuk mengubah informasi kelompok, hubungi Admin.</p>
                </div>
                @endif

                {{-- INFO TAMBAHAN: PENYULUH --}}
                @if($role === 'Penyuluh' && $penyuluh)
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 space-y-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-black text-blue-600 uppercase tracking-[0.2em]">Informasi Penyuluh</h3>
                        <span class="text-[9px] font-bold text-gray-400 bg-gray-50 px-2 py-1 rounded-lg border border-gray-100 uppercase tracking-wider">Hanya Baca</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-400 uppercase mb-1.5">NIP</label>
                            <div class="w-full px-4 py-4 bg-gray-50/60 border border-gray-100 rounded-xl text-sm text-gray-600 font-medium font-mono">
                                {{ $penyuluh->nip ?? '-' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-400 uppercase mb-1.5">Token Penyuluh</label>
                            <div class="w-full px-4 py-4 bg-gray-50/60 border border-gray-100 rounded-xl text-sm text-gray-600 font-mono tracking-widest">
                                {{ $penyuluh->token ?? '-' }}
                            </div>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-gray-400 uppercase mb-1.5">Jumlah Kelompok Binaan</label>
                            <div class="w-full px-4 py-4 bg-gray-50/60 border border-gray-100 rounded-xl text-sm text-gray-600 font-medium">
                                {{ $penyuluh->kelompokBinaans->count() ?? 0 }} Kelompok
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 italic">Untuk mengubah data penyuluh, hubungi Admin.</p>
                </div>
                @endif

                {{-- TOMBOL SIMPAN --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('dashboard') }}"
                        class="px-6 py-3 border border-gray-200 text-gray-500 font-bold rounded-2xl hover:bg-gray-50 transition-all text-sm">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-8 py-3 bg-[#4F6F52] text-white font-bold rounded-2xl hover:bg-[#1A3636] transition-all shadow-lg shadow-[#4F6F52]/20 text-sm flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Simpan Perubahan
                    </button>
                </div>

            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const fotoInput = document.getElementById('foto_profil_input');
    const fotoPreview = document.getElementById('foto-preview');
    const fotoInitial = document.getElementById('foto-initial');
    const hapusFotoInput = document.getElementById('hapus_foto_input');
    const btnHapusFoto = document.getElementById('btn-hapus-foto');

    // Preview saat file dipilih
    fotoInput?.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            fotoPreview.src = e.target.result;
            fotoPreview.classList.remove('hidden');
            if (fotoInitial) fotoInitial.classList.add('hidden');
            btnHapusFoto.classList.remove('hidden');
            hapusFotoInput.value = '0'; // Batalkan hapus jika ada foto baru
        };
        reader.readAsDataURL(file);
    });

    // Hapus foto
    btnHapusFoto?.addEventListener('click', function () {
        if (!confirm('Yakin ingin menghapus foto profil?')) return;

        fotoPreview.src = '';
        fotoPreview.classList.add('hidden');
        if (fotoInitial) fotoInitial.classList.remove('hidden');
        fotoInput.value = '';
        hapusFotoInput.value = '1';
        btnHapusFoto.classList.add('hidden');
    });
</script>
@endpush

@endsection
