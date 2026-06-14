@extends('layouts.app')

@section('title', 'Pelaporan Saya')

@section('content')

<!-- Header Section -->
<div class="mb-8">
    <h3 class="text-3xl font-black text-[#1A3636] tracking-tighter italic uppercase">Pelaporan Kelompok</h3>
    <p class="text-sm text-gray-500 mt-1 font-medium">Kirimkan laporan sesuai dengan jadwal yang telah ditentukan oleh KPHL.</p>
</div>

@if($errors->any())
<div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-8 rounded-xl shadow-sm animate-in slide-in-from-top duration-300">
    <ul class="list-disc ml-5 text-sm font-bold">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

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

<!-- Atribut Pelaporan Info Section -->
@if(isset($atributByKomoditas) && $atributByKomoditas->count() > 0)
<div class="mb-12">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-2 h-8 bg-blue-500 rounded-full"></div>
        <h4 class="text-sm font-black text-[#1A3636] uppercase tracking-widest">Field Laporan yang Harus Diisi</h4>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($atributByKomoditas as $komoditasNama => $atributs)
        <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden group">
            <!-- Decorative element -->
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-full -mr-10 -mt-10 opacity-50 group-hover:scale-125 transition-transform duration-500"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M8 13h8"/><path d="M8 17h8"/></svg>
                    </div>
                    <div>
                        <h5 class="text-xs font-black text-[#1A3636] uppercase tracking-widest">{{ $komoditasNama }}</h5>
                        <p class="text-[10px] font-bold text-gray-300 uppercase tracking-tighter">{{ $atributs->count() }} Field</p>
                    </div>
                </div>

                <div class="space-y-2">
                    @foreach($atributs as $atribut)
                    <div class="flex items-center gap-2 p-2.5 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="w-1.5 h-1.5 bg-blue-400 rounded-full shrink-0"></div>
                        <span class="text-xs font-bold text-gray-600">{{ $atribut->nama_atribut }}</span>
                        <span class="ml-auto px-2 py-0.5 rounded-full text-[8px] font-bold uppercase tracking-wider bg-blue-50 text-blue-500 border border-blue-100">
                            {{ $atribut->tipe_atribut }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Task Section -->
<div class="mb-12">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-2 h-8 bg-[#4F6F52] rounded-full"></div>
        <h4 class="text-sm font-black text-[#1A3636] uppercase tracking-widest">Tugas Pelaporan Anda</h4>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($tugasPelaporan as $tugas)
            @php
                $j = $tugas['jadwal'];
                $p = $tugas['produk'];
                $status = $tugas['status'];
                $laporan = $tugas['laporan'];
            @endphp
            <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md transition-all duration-300 relative overflow-hidden group">
                @if($status === 'Selesai' && $laporan)
                    <div class="absolute top-0 right-0 p-4">
                        <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-tighter {{ $laporan->status_verifikasi === 'diverifikasi' ? 'bg-green-100 text-green-600' : ($laporan->status_verifikasi === 'ditolak' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600') }}">
                            {{ $laporan->status_verifikasi }}
                        </span>
                    </div>
                @endif

                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-[#1A3636] font-black text-[10px] uppercase">
                            {{ \Carbon\Carbon::parse($j->tanggal_mulai)->format('M') }}
                        </div>
                        <div>
                            <h5 class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ \Carbon\Carbon::parse($j->tanggal_mulai)->translatedFormat('F Y') }}</h5>
                            <p class="text-[10px] font-bold text-gray-300 uppercase tracking-tighter">Batas: {{ date('d M Y', strtotime($j->tanggal_selesai)) }}</p>
                        </div>
                    </div>

                    <h3 class="text-lg font-black text-[#1A3636] mb-1 tracking-tight">{{ $p->nama_usaha }}</h3>
                    <span class="inline-block px-2 py-0.5 bg-[#4F6F52]/5 text-[#4F6F52] text-[9px] font-bold rounded-lg border border-[#4F6F52]/10 uppercase tracking-widest">
                        {{ $p->komoditas->nama_komoditas ?? 'Umum' }}
                    </span>

                    @if($j->target_capaian && $p->komoditas && $p->komoditas->menggunakan_target)
                    <div class="mt-3 flex items-center gap-2 p-2.5 bg-amber-50 border border-amber-100 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-amber-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                        <span class="text-[10px] font-bold text-amber-700">Target: {{ number_format($j->target_capaian) }}</span>
                    </div>
                    @endif
                </div>

                <div class="mt-8 flex gap-2">
                    @if($status === 'Selesai' && $laporan)
                        <button disabled class="flex-1 py-3 bg-gray-50 text-gray-400 text-[10px] font-black uppercase tracking-widest rounded-2xl cursor-not-allowed border border-gray-100">
                            Terkirim
                        </button>
                        @if($laporan->status_verifikasi !== 'diverifikasi')
                        <button onclick="openReportModal({{ json_encode($p) }}, {{ json_encode($j) }}, {{ json_encode($laporan) }})" class="flex-1 py-3 bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-blue-600 hover:text-white transition-all">
                            Edit
                        </button>
                        @endif
                    @elseif($status === 'Aktif')
                        <button onclick="openReportModal({{ json_encode($p) }}, {{ json_encode($j) }})" class="w-full py-3 bg-[#4F6F52] text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-[#1A3636] transition-all shadow-lg shadow-[#4F6F52]/20">
                            Kirim Laporan
                        </button>
                    @else
                        <button disabled class="w-full py-3 bg-gray-50 text-gray-300 text-[10px] font-black uppercase tracking-widest rounded-2xl cursor-not-allowed border border-dashed border-gray-200">
                            Belum Dimulai
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 bg-gray-50 rounded-[2rem] border-2 border-dashed border-gray-200 text-center">
                <p class="text-sm text-gray-400 font-bold uppercase tracking-widest">Tidak ada jadwal pelaporan yang cocok dengan produk Anda.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- History Section -->
<div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50 flex justify-between items-center">
        <h3 class="text-sm font-black text-[#1A3636] uppercase tracking-[0.2em] italic">Riwayat Laporan</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-gray-50/50 text-gray-400 font-bold uppercase tracking-widest text-[10px] border-b border-gray-100">
                    <th class="py-6 px-8">No</th>
                    <th class="py-6 px-8">Produk</th>
                    <th class="py-6 px-8">Komoditas</th>
                    <th class="py-6 px-8">Periode</th>
                    <th class="py-6 px-8 text-center">Status</th>
                    <th class="py-6 px-8">Nilai Atribut</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($pelaporans as $index => $p)
                <tr class="hover:bg-gray-50/80 transition-all duration-300 group">
                    <td class="py-6 px-8 text-gray-400 font-medium">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                    <td class="py-6 px-8">
                        <span class="font-bold text-gray-700">{{ $p->produk->nama_usaha ?? '-' }}</span>
                    </td>
                    <td class="py-6 px-8">
                        <span class="px-2 py-0.5 bg-[#4F6F52]/5 text-[#4F6F52] text-[9px] font-bold rounded-lg border border-[#4F6F52]/10 uppercase tracking-widest">
                            {{ $p->produk->komoditas->nama_komoditas ?? '-' }}
                        </span>
                    </td>
                    <td class="py-6 px-8">
                        <span class="text-xs font-bold text-gray-500 uppercase">
                            {{ \Carbon\Carbon::parse($p->jadwal->tanggal_mulai)->translatedFormat('F Y') }}
                        </span>
                    </td>
                    <td class="py-6 px-8 text-center">
                        <span class="px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider {{ $p->status_verifikasi === 'diverifikasi' ? 'bg-green-50 text-green-600 border border-green-100' : ($p->status_verifikasi === 'ditolak' ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-blue-50 text-blue-600 border border-blue-100') }}">
                            {{ $p->status_verifikasi }}
                        </span>
                    </td>
                    <td class="py-6 px-8">
                        <div class="flex flex-wrap gap-2">
                            @foreach($p->nilais as $n)
                                @php
                                    $bukti = $p->buktis->firstWhere('atribut_id', $n->atribut_id);
                                @endphp
                                <span class="px-2 py-0.5 bg-gray-100 text-[9px] font-bold text-gray-500 rounded border border-gray-200 inline-flex items-center gap-1">
                                    {{ $n->atribut->nama_atribut ?? '-' }}: {{ $n->value }}
                                    @if($bukti)
                                        <a href="{{ asset('storage/' . $bukti->foto_bukti) }}" target="_blank" class="text-blue-500 hover:text-blue-700 underline font-semibold ml-1">
                                            (Lihat Foto)
                                        </a>
                                    @endif
                                </span>
                            @endforeach
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-20 text-center text-gray-400 italic">Belum ada riwayat laporan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Container -->
<div id="modalOverlay" class="fixed inset-0 z-[60] hidden transition-opacity duration-300 bg-black/40 backdrop-blur-[2px]"></div>

<!-- Modal Report -->
<div id="modalReport" class="fixed inset-0 z-[70] hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0 modal-content">
        <div class="p-8 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <div>
                <h3 id="modalTitle" class="text-2xl font-black text-[#1A3636] uppercase tracking-tighter">Formulir Pelaporan</h3>
                <p id="modalSubtitle" class="text-xs text-gray-400 font-bold tracking-widest uppercase mt-1"></p>
            </div>
            <button onclick="closeModal('modalReport')" class="w-10 h-10 flex items-center justify-center rounded-2xl hover:bg-white hover:shadow-md transition-all text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        
        <form id="formReport" action="{{ route('pelaporan.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar">
            @csrf
            <div id="methodField"></div>
            <input type="hidden" name="usaha_id" id="input_usaha_id">
            <input type="hidden" name="jadwal_id" id="input_jadwal_id">

            <div class="grid grid-cols-1 gap-6">
              </div>
            <!-- Dynamic Attributes -->
            <div id="attributesContainer">
                <div class="flex items-center gap-4 mb-4">
                    <div class="h-[1px] flex-1 bg-gray-100"></div>
                    <h5 class="text-[10px] font-black text-gray-300 uppercase tracking-[0.3em]">Detail Nilai Atribut</h5>
                    <div class="h-[1px] flex-1 bg-gray-100"></div>
                </div>
                <div id="attributesList" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Loaded via AJAX -->
                </div>
            </div>

            <div class="pt-6 flex gap-3">
                <button type="button" onclick="closeModal('modalReport')" class="flex-1 px-8 py-4 border border-gray-100 text-gray-400 font-black uppercase tracking-widest text-xs rounded-2xl hover:bg-gray-50 transition-all">Batal</button>
                <button type="submit" id="btnSubmit" class="flex-[2] px-8 py-4 bg-[#4F6F52] text-white font-black uppercase tracking-widest text-xs rounded-2xl hover:bg-[#1A3636] transition-all shadow-xl shadow-[#4F6F52]/20">Kirim Laporan</button>
            </div>
        </form>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 10px; }
</style>

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
        setTimeout(() => {
            overlay.classList.add('hidden');
            modal.classList.add('hidden');
        }, 300);
    }

    async function openReportModal(produk, jadwal, laporan = null) {
        const form = document.getElementById('formReport');
        const methodField = document.getElementById('methodField');
        const btnSubmit = document.getElementById('btnSubmit');
        const modalTitle = document.getElementById('modalTitle');

        document.getElementById('input_usaha_id').value = produk.usaha_id;
        document.getElementById('input_jadwal_id').value = jadwal.jadwal_id;
        document.getElementById('modalSubtitle').innerText = `${produk.nama_usaha} - Periode ${jadwal.bulan} ${jadwal.tahun}`;
        
        if (laporan) {
            modalTitle.innerText = 'Edit Laporan';
            form.action = `/pelaporan/${laporan.laporan_id}`;
            methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            btnSubmit.innerText = 'Perbarui Laporan';
            btnSubmit.classList.replace('bg-[#4F6F52]', 'bg-blue-600');
        } else {
            modalTitle.innerText = 'Formulir Pelaporan';
            form.action = "{{ route('pelaporan.store') }}";
            methodField.innerHTML = '';
            btnSubmit.innerText = 'Kirim Laporan';
            btnSubmit.classList.replace('bg-blue-600', 'bg-[#4F6F52]');
        }

        const list = document.getElementById('attributesList');
        const isEditMode = !!laporan;
        list.innerHTML = '<div class="col-span-full text-center py-4"><span class="text-xs text-gray-400 animate-pulse italic">Memuat atribut...</span></div>';
        
        openModal('modalReport');

        try {
            const url = laporan 
                ? `/pelaporan/get-atribut?usaha_id=${produk.usaha_id}&laporan_id=${laporan.laporan_id}` 
                : `/pelaporan/get-atribut?usaha_id=${produk.usaha_id}`;
            const response = await fetch(url);
            const data = await response.json();


            list.innerHTML = '';
            if (data.atributs.length === 0) {
                list.innerHTML = '<div class="col-span-full p-4 bg-gray-50 rounded-2xl text-center"><p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tidak ada atribut tambahan</p></div>';
            } else {
                data.atributs.forEach(attr => {
                    const val = data.nilaiExist ? (data.nilaiExist[attr.atribut_id] || '') : '';
                    const buktiPath = data.buktiExist ? (data.buktiExist[attr.atribut_id] || '') : '';
                    const div = document.createElement('div');
                    
                    if (attr.jenis_field === 'image') {
                        let oldBuktiHtml = '';
                        if (buktiPath) {
                            oldBuktiHtml = `
                                <div class="mt-2 text-[10px] text-gray-500 font-bold">
                                    Bukti saat ini: 
                                    <a href="/storage/${buktiPath}" target="_blank" class="text-blue-500 hover:text-blue-700 underline font-semibold">Lihat Foto</a>
                                </div>
                            `;
                        }
                        div.innerHTML = `
                            <label class="block text-[10px] font-black text-[#4F6F52] uppercase tracking-widest mb-2 ml-1">${attr.nama_atribut}</label>
                            <input type="hidden" name="atribut[${attr.atribut_id}]" value="Foto Terunggah">
                            <input type="file" name="bukti[${attr.atribut_id}]" accept="image/*" ${(isEditMode && buktiPath) ? '' : 'required'} class="w-full text-sm text-gray-700 file:border-0 file:bg-[#4F6F52] file:text-white file:px-4 file:py-2 file:rounded-2xl" />
                            ${oldBuktiHtml}
                        `;
                    } else if (attr.jenis_field === 'number') {
                        const labelText = attr.satuan ? `${attr.nama_atribut} (${attr.satuan})` : attr.nama_atribut;
                        div.innerHTML = `
                            <label class="block text-[10px] font-black text-[#4F6F52] uppercase tracking-widest mb-2 ml-1">${labelText}</label>
                            <input type="number" name="atribut[${attr.atribut_id}]" value="${val}" required placeholder="Masukkan angka" class="w-full px-5 py-4 bg-[#4F6F52]/5 border border-[#4F6F52]/10 rounded-2xl focus:ring-4 focus:ring-[#4F6F52]/10 outline-none transition-all font-bold text-gray-700">
                        `;
                    } else {
                        div.innerHTML = `
                            <label class="block text-[10px] font-black text-[#4F6F52] uppercase tracking-widest mb-2 ml-1">${attr.nama_atribut}</label>
                            <input type="text" name="atribut[${attr.atribut_id}]" value="${val}" required placeholder="Masukkan nilai" class="w-full px-5 py-4 bg-[#4F6F52]/5 border border-[#4F6F52]/10 rounded-2xl focus:ring-4 focus:ring-[#4F6F52]/10 outline-none transition-all font-bold text-gray-700">
                        `;
                    }
                    list.appendChild(div);
                });
            }
        } catch (error) {
            list.innerHTML = '<p class="text-xs text-center text-red-400 italic">Gagal memuat atribut.</p>';
        }
    }
</script>
@endpush
