@extends('layouts.app')

@section('title', 'Dashboard KPHL')

@section('content')

<!-- TOOLBAR -->
<div class="flex flex-wrap items-center gap-2 mb-5">
    <h2 class="text-lg font-medium text-gray-800 flex-1">Dashboard KPHL</h2>

    <!-- SEARCH -->
    <div class="flex items-center gap-2 bg-white border border-gray-100 rounded-lg px-3 h-8">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
        </svg>
        <input type="text" placeholder="Cari kelompok, laporan..."
            class="bg-transparent text-xs text-gray-700 placeholder-gray-400 outline-none w-40" />
    </div>

    <!-- SORT TYPE -->
    <select id="sortType" onchange="updatePeriodSelects(this.value); updateCharts()"
        class="h-8 text-xs border border-gray-100 rounded-lg px-2 bg-white text-gray-700 outline-none cursor-pointer">
        <option value="triwulan">Triwulan</option>
        <option value="tahunan">Tahunan</option>
    </select>

    <!-- SORT VALUE -->
    <select id="sortVal" onchange="updateCharts()"
        class="h-8 text-xs border border-gray-100 rounded-lg px-2 bg-white text-gray-700 outline-none cursor-pointer">
        <option value="tw1-2026">TW I 2026</option>
        <option value="tw2-2026">TW II 2026</option>
        <option value="tw3-2026">TW III 2026</option>
        <option value="tw4-2026">TW IV 2026</option>
    </select>

    <!-- DOWNLOAD -->
    <button onclick="downloadLaporan()"
        class="flex items-center gap-1.5 h-8 px-3 bg-[#3B6D11] hover:bg-[#27500A] text-white text-xs font-medium rounded-lg transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 3v13m-4-4l4 4 4-4"/>
        </svg>
        Unduh Laporan
    </button>
</div>

<!-- CARD STATISTIK -->
<div class="grid grid-cols-3 gap-3 mb-5">

    <div class="bg-white px-4 py-3 rounded-xl border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-xs text-gray-500 mb-1">Total Kelompok</p>
            <h2 class="text-2xl font-medium text-gray-800">25</h2>
        </div>
        <div class="w-9 h-9 rounded-lg bg-green-50 flex items-center justify-center text-green-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
            </svg>
        </div>
    </div>

    <div class="bg-white px-4 py-3 rounded-xl border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-xs text-gray-500 mb-1">Total Penyuluh</p>
            <h2 class="text-2xl font-medium text-gray-800">10</h2>
        </div>
        <div class="w-9 h-9 rounded-lg bg-teal-50 flex items-center justify-center text-teal-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
    </div>

    <div class="bg-white px-4 py-3 rounded-xl border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-xs text-gray-500 mb-1">Laporan Periode Ini</p>
            <h2 class="text-2xl font-medium text-gray-800" id="cLaporan">50</h2>
        </div>
        <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center text-blue-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
    </div>

</div>

<!-- DUA GRAFIK -->
<div class="grid grid-cols-2 gap-4">

    <!-- GRAFIK PRODUK -->
    <div class="bg-white p-4 rounded-xl border border-gray-100">
        <h3 class="text-sm font-medium text-gray-800 mb-0.5">Grafik Laporan Produk</h3>
        <p class="text-xs text-gray-400 mb-3">Jumlah laporan produk per periode.</p>
        <div class="flex gap-3 mb-3">
            <span class="flex items-center gap-1.5 text-xs text-gray-500">
                <span class="w-2 h-2 rounded-sm bg-[#3B6D11] inline-block"></span>Produk Hutan
            </span>
            <span class="flex items-center gap-1.5 text-xs text-gray-500">
                <span class="w-2 h-2 rounded-sm bg-[#97C459] inline-block"></span>Produk Olahan
            </span>
        </div>
        <div style="position:relative; height:160px;">
            <canvas id="chartProduk"></canvas>
        </div>
    </div>

    <!-- GRAFIK WISATA -->
    <div class="bg-white p-4 rounded-xl border border-gray-100">
        <h3 class="text-sm font-medium text-gray-800 mb-0.5">Grafik Laporan Wisata</h3>
        <p class="text-xs text-gray-400 mb-3">Jumlah laporan wisata per periode.</p>
        <div class="flex gap-3 mb-3">
            <span class="flex items-center gap-1.5 text-xs text-gray-500">
                <span class="w-2 h-2 rounded-sm bg-[#185FA5] inline-block"></span>Wisata Alam
            </span>
            <span class="flex items-center gap-1.5 text-xs text-gray-500">
                <span class="w-2 h-2 rounded-sm bg-[#85B7EB] inline-block"></span>Wisata Edukasi
            </span>
        </div>
        <div style="position:relative; height:160px;">
            <canvas id="chartWisata"></canvas>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const dataTriwulan = {
    labels: ['TW I', 'TW II', 'TW III', 'TW IV'],
    produk: [[18,9],[22,11],[15,8],[20,10]],
    wisata: [[12,6],[16,8],[10,5],[14,7]],
};
const dataTahunan = {
    labels: ['2022','2023','2024','2025','2026'],
    produk: [[45,22],[60,30],[72,38],[85,44],[50,28]],
    wisata: [[30,15],[40,20],[55,25],[65,30],[38,18]],
};

const baseOpts = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
        x: { stacked: true, grid: { display: false }, border: { display: false }, ticks: { color: '#9ca3af', font: { size: 11 } } },
        y: { stacked: true, grid: { color: 'rgba(0,0,0,0.04)' }, border: { display: false }, ticks: { color: '#9ca3af', font: { size: 11 } } }
    }
};

const chartP = new Chart(document.getElementById('chartProduk'), {
    type: 'bar', options: baseOpts,
    data: { labels: [], datasets: [
        { label: 'Produk Hutan', data: [], backgroundColor: '#3B6D11', borderRadius: 4, borderSkipped: false },
        { label: 'Produk Olahan', data: [], backgroundColor: '#97C459', borderRadius: 4, borderSkipped: false }
    ]}
});

const chartW = new Chart(document.getElementById('chartWisata'), {
    type: 'bar', options: baseOpts,
    data: { labels: [], datasets: [
        { label: 'Wisata Alam', data: [], backgroundColor: '#185FA5', borderRadius: 4, borderSkipped: false },
        { label: 'Wisata Edukasi', data: [], backgroundColor: '#85B7EB', borderRadius: 4, borderSkipped: false }
    ]}
});

function updatePeriodSelects(type) {
    const sel = document.getElementById('sortVal');
    sel.innerHTML = '';
    if (type === 'triwulan') {
        [['tw1-2026','TW I 2026'],['tw2-2026','TW II 2026'],['tw3-2026','TW III 2026'],['tw4-2026','TW IV 2026'],
         ['tw1-2025','TW I 2025'],['tw2-2025','TW II 2025'],['tw3-2025','TW III 2025'],['tw4-2025','TW IV 2025']]
        .forEach(([v,l]) => sel.add(new Option(l, v)));
    } else {
        ['2026','2025','2024','2023','2022'].forEach(y => sel.add(new Option(y, y)));
    }
}

function updateCharts() {
    const type = document.getElementById('sortType').value;
    const src = type === 'triwulan' ? dataTriwulan : dataTahunan;

    chartP.data.labels = src.labels;
    chartP.data.datasets[0].data = src.produk.map(d => d[0]);
    chartP.data.datasets[1].data = src.produk.map(d => d[1]);
    chartP.update();

    chartW.data.labels = src.labels;
    chartW.data.datasets[0].data = src.wisata.map(d => d[0]);
    chartW.data.datasets[1].data = src.wisata.map(d => d[1]);
    chartW.update();

    const total = src.produk.flat().concat(src.wisata.flat()).reduce((a, b) => a + b, 0);
    document.getElementById('cLaporan').textContent = total;
}

function downloadLaporan() {
    const type = document.getElementById('sortType').value;
    const val = document.getElementById('sortVal').value;
    const src = type === 'triwulan' ? dataTriwulan : dataTahunan;

    let csv = 'Periode,Produk Hutan,Produk Olahan,Wisata Alam,Wisata Edukasi\n';
    src.labels.forEach((l, i) => {
        csv += [l, src.produk[i][0], src.produk[i][1], src.wisata[i][0], src.wisata[i][1]].join(',') + '\n';
    });

    const a = document.createElement('a');
    a.href = URL.createObjectURL(new Blob([csv], { type: 'text/csv' }));
    a.download = 'laporan_kphl_' + type + '_' + val + '.csv';
    a.click();
}

updatePeriodSelects('triwulan');
updateCharts();
</script>
@endpush