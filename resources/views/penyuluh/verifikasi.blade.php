@extends('layouts.app')

@section('title', 'Verifikasi Pelaporan')

@section('content')

<div class="mb-8">
    <h3 class="text-3xl font-black text-[#1A3636] tracking-tighter uppercase italic">Verifikasi Pelaporan</h3>
    <p class="text-sm text-gray-500 mt-1 font-medium">Verifikasi laporan perkembangan yang dikirimkan oleh kelompok binaan Anda.</p>
</div>

@if(session('success'))
<div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-8 rounded-xl shadow-sm animate-in slide-in-from-top duration-300">
    <span class="text-sm font-bold">{{ session('success') }}</span>
</div>
@endif

<div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
        <h3 class="text-sm font-black text-[#1A3636] uppercase tracking-[0.2em] italic">Daftar Tunggu Verifikasi</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-gray-50/50 text-gray-400 font-bold uppercase tracking-widest text-[10px] border-b border-gray-100">
                    <th class="py-6 px-8">Kelompok</th>
                    <th class="py-6 px-8">Produk</th>
                    <th class="py-6 px-8">Periode</th>
                    <th class="py-6 px-8">Detail Laporan</th>
                    <th class="py-6 px-8 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($pelaporans as $p)
                <tr class="hover:bg-gray-50/80 transition-all duration-300 group">
                    <td class="py-6 px-8">
                        <div class="flex flex-col">
                            <span class="font-bold text-gray-700">{{ $p->produk->kelompok->nama_kelompok }}</span>
                            <span class="text-[9px] text-gray-400 font-black uppercase tracking-widest">{{ $p->produk->kelompok->jenis_izin }}</span>
                        </div>
                    </td>
                    <td class="py-6 px-8">
                        <span class="font-bold text-[#4F6F52]">{{ $p->produk->nama_produk }}</span>
                    </td>
                   <td class="py-6 px-8">
                         <span class="text-xs font-bold text-gray-500 uppercase">
                            {{ \Carbon\Carbon::parse($p->jadwal->tanggal_mulai)->translatedFormat('F Y') }}
                        </span>
                    </td>
                    <td class="py-6 px-8">
                        <div class="space-y-2">
                            <div class="flex flex-wrap gap-2">
                                @foreach($p->nilais as $n)
                                    @php
                                        $bukti = $p->buktis->firstWhere('atribut_id', $n->atribut_id);
                                    @endphp
                                    <div class="flex flex-col gap-1 p-2 bg-gray-50 rounded border border-gray-200">
                                        <span class="text-[9px] font-bold text-gray-500">
                                            @if($n->atribut && $n->atribut->jenis_field === 'image')
                                                {{ $n->atribut->nama_atribut }}: [Gambar]
                                            @else
                                                {{ $n->atribut->nama_atribut }}: {{ $n->value }}
                                            @endif
                                        </span>
                                        @if($bukti)
                                            <a href="{{ asset('storage/' . $bukti->foto_bukti) }}" target="_blank" class="mt-1">
                                                <img src="{{ asset('storage/' . $bukti->foto_bukti) }}" class="w-20 h-12 object-cover rounded border border-gray-300 hover:scale-105 transition-transform" />
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </td>
                    <td class="py-6 px-8">
                        <div class="flex justify-center">
                            <button onclick="openVerifyDetailModal({{ json_encode($p) }})" class="px-4 py-2 bg-[#4F6F52] hover:bg-[#1A3636] text-white text-[10px] font-black uppercase tracking-widest rounded-2xl transition-all shadow-md shadow-[#4F6F52]/10 flex items-center gap-1.5 mx-auto">
                                🔍 Periksa Laporan
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-24 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 10-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 10"/><path d="M22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6Z"/></svg>
                            </div>
                            <p class="text-sm text-gray-400 font-bold uppercase tracking-widest">Tidak ada laporan yang perlu diverifikasi</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Detail & Verifikasi -->
<div id="modalOverlay" class="fixed inset-0 z-[60] hidden transition-opacity duration-300 bg-black/40 backdrop-blur-[2px]"></div>
<div id="modalVerifyDetail" class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0 modal-content">
        <div class="p-8 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <div>
                <h3 class="text-2xl font-black text-[#1A3636] uppercase tracking-tighter">Periksa & Verifikasi Laporan</h3>
                <p id="verifySubtitle" class="text-xs text-gray-400 font-bold tracking-widest uppercase mt-1"></p>
            </div>
            <button onclick="closeModal('modalVerifyDetail')" class="w-10 h-10 flex items-center justify-center rounded-2xl hover:bg-white hover:shadow-md transition-all text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        
        <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar">
            <!-- Info Section -->
            <div class="bg-gray-50 rounded-3xl p-6 border border-gray-100 space-y-3">
                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Detail Laporan Kelompok</h4>
                <div class="grid grid-cols-2 gap-4 text-xs font-bold text-gray-700">
                    <div>
                        <span class="text-[9px] text-gray-400 block uppercase tracking-wider">Kelompok Binaan</span>
                        <span id="detailKelompok" class="text-[#1A3636]">-</span>
                    </div>
                    <div>
                        <span class="text-[9px] text-gray-400 block uppercase tracking-wider">Komoditas & Produk</span>
                        <span id="detailProduk" class="text-[#4F6F52]">-</span>
                    </div>
                </div>
            </div>

            <!-- Dynamic Attributes Section -->
            <div class="space-y-4">
                <div class="flex items-center gap-4">
                    <div class="h-[1px] flex-1 bg-gray-100"></div>
                    <h5 class="text-[10px] font-black text-gray-300 uppercase tracking-[0.3em]">Nilai Atribut & Bukti</h5>
                    <div class="h-[1px] flex-1 bg-gray-100"></div>
                </div>
                <div id="detailAttributesList" class="grid grid-cols-2 gap-3">
                    <!-- Dynamic -->
                </div>
            </div>

            <!-- Verification Action Form -->
            <form id="formVerifyDetail" method="POST" class="border-t border-gray-100 pt-6 space-y-6">
                @csrf
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Keputusan Verifikasi</label>
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Setujui Option -->
                        <label class="relative flex items-center justify-between p-4 bg-gray-50 rounded-2xl border-2 border-gray-100 cursor-pointer hover:border-green-500/30 transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-green-50 flex items-center justify-center text-green-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                </div>
                                <div class="text-left">
                                    <span class="block text-xs font-black text-gray-700 uppercase tracking-wider">Setujui</span>
                                    <span class="text-[9px] text-gray-400 font-bold uppercase tracking-tight">Laporan VALID</span>
                                </div>
                            </div>
                            <input type="radio" name="status" value="approved" checked required onclick="toggleVerificationCatatan(false)" class="h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500">
                        </label>
                        <!-- Tolak Option -->
                        <label class="relative flex items-center justify-between p-4 bg-gray-50 rounded-2xl border-2 border-gray-100 cursor-pointer hover:border-red-500/30 transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-red-50 flex items-center justify-center text-red-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                </div>
                                <div class="text-left">
                                    <span class="block text-xs font-black text-gray-700 uppercase tracking-wider">Tolak</span>
                                    <span class="text-[9px] text-gray-400 font-bold uppercase tracking-tight">Butuh Perbaikan</span>
                                </div>
                            </div>
                            <input type="radio" name="status" value="rejected" required onclick="toggleVerificationCatatan(true)" class="h-4 w-4 text-red-600 border-gray-300 focus:ring-red-500">
                        </label>
                    </div>
                </div>

                <!-- Notes / Reason -->
                <div id="wrapperCatatanVerify">
                    <label id="labelCatatanVerify" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Catatan Verifikasi (Opsional)</label>
                    <textarea name="catatan" id="catatanVerify" rows="3" placeholder="Tambahkan catatan jika diperlukan..." class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-[#4F6F52]/10 outline-none transition-all font-medium text-gray-700"></textarea>
                </div>

                <!-- Submit -->
                <button type="submit" id="btnSubmitVerifyDetail" class="w-full py-4 bg-[#4F6F52] hover:bg-[#1A3636] text-white font-black uppercase tracking-widest text-xs rounded-2xl transition-all shadow-xl shadow-[#4F6F52]/20">
                    Kirim Keputusan Verifikasi
                </button>
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
        setTimeout(() => {
            overlay.classList.add('hidden');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    function toggleVerificationCatatan(isRequired) {
        const label = document.getElementById('labelCatatanVerify');
        const textarea = document.getElementById('catatanVerify');
        const btn = document.getElementById('btnSubmitVerifyDetail');
        
        if (isRequired) {
            label.innerText = 'Alasan Penolakan / Tidak Diverifikasi (Wajib)';
            label.classList.remove('text-gray-400');
            label.classList.add('text-red-500');
            textarea.setAttribute('required', 'required');
            textarea.placeholder = 'Jelaskan alasan penolakan secara detail...';
            btn.className = 'w-full py-4 bg-red-600 hover:bg-red-700 text-white font-black uppercase tracking-widest text-xs rounded-2xl transition-all shadow-xl shadow-red-600/20';
        } else {
            label.innerText = 'Catatan Verifikasi (Opsional)';
            label.classList.remove('text-red-500');
            label.classList.add('text-gray-400');
            textarea.removeAttribute('required');
            textarea.placeholder = 'Tambahkan catatan jika diperlukan...';
            btn.className = 'w-full py-4 bg-[#4F6F52] hover:bg-[#1A3636] text-white font-black uppercase tracking-widest text-xs rounded-2xl transition-all shadow-xl shadow-[#4F6F52]/20';
        }
    }

    function openVerifyDetailModal(pelaporan) {
        document.getElementById('verifySubtitle').innerText =`Periode ${pelaporan.jadwal.tanggal_mulai} s/d ${pelaporan.jadwal.tanggal_selesai}`;
        document.getElementById('detailKelompok').innerText = pelaporan.produk && pelaporan.produk.kelompok ? pelaporan.produk.kelompok.nama_kelompok : '-';
       document.getElementById('detailProduk').innerText =pelaporan.produk && pelaporan.produk.nama_produk? pelaporan.produk.nama_produk :'-';
        // Set action form
        document.getElementById('formVerifyDetail').action = `/penyuluh/verifikasi/${pelaporan.laporan_id}`;

        // Reset radio & notes
        const radios = document.getElementsByName('status');
        radios[0].checked = true; // approved
        toggleVerificationCatatan(false);
        document.getElementById('catatanVerify').value = '';

        // Dynamic Attributes & Proofs
        const list = document.getElementById('detailAttributesList');
        list.innerHTML = '';

        if (pelaporan.nilais.length === 0) {
            list.innerHTML = '<div class="col-span-full text-center py-4 text-xs text-gray-400">Tidak ada atribut data</div>';
        } else {
            pelaporan.nilais.forEach(nilai => {
                const div = document.createElement('div');
               div.className = 'flex flex-col gap-1 p-3 bg-gray-50 rounded-xl border border-gray-100';
                
                // Find proof
                const bukti = pelaporan.buktis ? pelaporan.buktis.find(b => b.atribut_id === nilai.atribut_id) : null;
                let buktiHtml = '';
                if (bukti) {
                    buktiHtml = `
                        <div class="mt-2">
                            <span class="text-[8px] text-gray-400 block uppercase tracking-wider mb-1">Bukti Foto</span>
                            <a href="/storage/${bukti.foto_bukti}" target="_blank" class="inline-block">
                               <img src="/storage/${bukti.foto_bukti}" class="w-24 h-16 object-cover rounded-lg border border-gray-200 hover:scale-105 transition-transform" />
                            </a>
                        </div>
                    `;
                }

                let valueHtml = `<span class="text-sm font-bold text-[#4F6F52]">${nilai.value}</span>`;
                if (nilai.atribut && nilai.atribut.jenis_field === 'image') {
                    valueHtml = `<span class="text-[10px] font-bold text-purple-600 bg-purple-50 border border-purple-100 px-2 py-0.5 rounded-full uppercase">Gambar</span>`;
                }

                div.innerHTML = `
                    <div class="flex justify-between items-center w-full">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">${nilai.atribut ? nilai.atribut.nama_atribut : '-'}</span>
                        ${valueHtml}
                    </div>
                    ${buktiHtml}
                `;
                list.appendChild(div);
            });
        }

        openModal('modalVerifyDetail');
    }
</script>
@endpush
