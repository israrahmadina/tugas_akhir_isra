@extends('layouts.app')

@section('title', 'Daftar Pelaporan Kelompok')

@section('content')

<!-- Header Section -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h3 class="text-2xl font-bold text-[#1A3636]">Daftar Pelaporan</h3>
        <p class="text-sm text-gray-500 mt-1">Pantau semua laporan yang masuk dari berbagai kelompok binaan.</p>
    </div>
</div>

<!-- Table Card -->
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-500 hover:shadow-md">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-gray-50/50 text-gray-400 font-bold uppercase tracking-widest text-[10px] border-b border-gray-100">
                    <th class="py-5 px-8">No</th>
                    <th class="py-5 px-8">Kelompok</th>
                    <th class="py-5 px-8">Produk</th>
                    <th class="py-5 px-8">Periode</th>
                    <th class="py-5 px-8">Target</th>
                    <th class="py-5 px-8 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($pelaporans as $index => $pelaporan)
                <tr class="hover:bg-gray-50/80 transition-all duration-200 group">
                    <td class="py-5 px-8 text-gray-400 font-medium">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                    <td class="py-5 px-8">
                        <span class="font-bold text-gray-700">{{ $pelaporan->kelompokBinaan->nama_kelompok ?? '-' }}</span>
                    </td>
                    <td class="py-5 px-8">
                        <div class="flex flex-col">
                            <span class="text-gray-700 font-medium">{{ $pelaporan->produk->nama_usaha ?? '-' }}</span>
                            <span class="text-[10px] text-gray-400 uppercase tracking-tighter">{{ $pelaporan->produk->komoditas->nama_komoditas ?? '-' }}</span>
                        </div>
                    </td>
                    <td class="py-5 px-8">
                        <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-[11px] font-bold">
                            {{ \Carbon\Carbon::parse($pelaporan->jadwal->tanggal_mulai)->translatedFormat('F Y') }}
                        </span>
                    </td>
                    <td class="py-5 px-8 text-gray-600 font-medium">{{ $pelaporan->target }}</td>
                    <td class="py-5 px-8">
                        <div class="flex justify-center gap-3">
                            <button onclick="viewDetail({{ json_encode($pelaporan->load('nilais.atribut')) }})"
                               class="px-4 py-2 text-xs font-bold text-white bg-[#4F6F52] hover:bg-[#1A3636] rounded-xl transition-all shadow-sm">
                                Detail
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-20 text-gray-400">Belum ada laporan yang masuk</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Detail -->
<div id="modalOverlay" class="fixed inset-0 z-[60] hidden transition-opacity duration-300 bg-black/40 backdrop-blur-[2px]"></div>
<div id="modalDetail" class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0 modal-content">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-[#1A3636]">Detail Atribut Pelaporan</h3>
            <button onclick="closeModal('modalDetail')" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 transition-colors text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <div class="p-8">
            <div id="atributList" class="space-y-4">
                <!-- Content will be injected by JS -->
            </div>
            <div class="mt-8 pt-6 border-t border-gray-100">
                <button onclick="closeModal('modalDetail')" class="w-full px-6 py-3 bg-[#4F6F52] text-white font-bold rounded-2xl hover:bg-[#1A3636] transition-all">Tutup</button>
            </div>
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

    function viewDetail(pelaporan) {
        const list = document.getElementById('atributList');
        list.innerHTML = '';
        
        if (pelaporan.nilais.length === 0) {
            list.innerHTML = '<p class="text-center text-gray-400 py-4">Tidak ada data atribut.</p>';
        } else {
            pelaporan.nilais.forEach(nilai => {
                const item = document.createElement('div');
                item.className = 'flex flex-col gap-2 p-4 bg-gray-50 rounded-2xl border border-gray-100';
                
                // Find matching bukti
                const bukti = pelaporan.buktis ? pelaporan.buktis.find(b => b.atribut_id === nilai.atribut_id) : null;
                let buktiHtml = '';
                if (bukti) {
                    buktiHtml = `
                        <div class="mt-2">
                            <a href="/storage/${bukti.foto_bukti}" target="_blank" class="inline-block">
                                <img src="/storage/${bukti.foto_bukti}" class="w-32 h-20 object-cover rounded-xl border border-gray-200 hover:scale-105 transition-transform" />
                            </a>
                        </div>
                    `;
                }

                let valueHtml = `<span class="text-lg font-bold text-[#4F6F52]">${nilai.value}</span>`;
                if (nilai.atribut && nilai.atribut.jenis_field === 'image') {
                    valueHtml = `<span class="text-[10px] font-bold text-purple-600 bg-purple-50 border border-purple-100 px-2 py-0.5 rounded-full uppercase">Gambar</span>`;
                }

                item.innerHTML = `
                    <div class="flex justify-between items-center w-full">
                        <span class="text-sm font-bold text-gray-500 uppercase tracking-wider">${nilai.atribut ? nilai.atribut.nama_atribut : '-'}</span>
                        ${valueHtml}
                    </div>
                    ${buktiHtml}
                `;
                list.appendChild(item);
            });
        }
        
        openModal('modalDetail');
    }
</script>
@endpush
