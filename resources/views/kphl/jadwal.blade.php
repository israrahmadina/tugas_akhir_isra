@extends('layouts.app')

@section('title', 'Jadwal Pelaporan')

@section('content')

<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-[#1A3636]">
            Jadwal Pelaporan
        </h1>
        <p class="text-gray-500 mt-1">
            Kelola jadwal pelaporan per jenis produk.
        </p>
    </div>

    <button onclick="openModal('modalTambah')"
        class="bg-[#4F6F52] hover:bg-[#1A3636] text-white px-5 py-3 rounded-2xl font-semibold transition-all duration-300 shadow-lg justify-center item-center">
        + Tambah Jadwal
    </button>
</div>

@if($errors->any())
<div class="mb-6 bg-red-100 border border-red-300 text-red-700 px-5 py-4 rounded-2xl">
    <ul class="list-disc ml-5 font-semibold">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(session('success'))
<div class="mb-6 bg-green-100 border border-green-300 text-green-700 px-5 py-4 rounded-2xl">
    {{ session('success') }}
</div>
@endif

<!-- TABLE -->
<div class="bg-white rounded-3xl shadow-sm overflow-hidden border border-gray-100">

    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-[#F5F7F6] text-gray-500 uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-6 py-5 text-left">No</th>
                    <th class="px-6 py-5 text-left">Jenis Produk</th>
                    <th class="px-6 py-5 text-left">Periode</th>
                    <th class="px-6 py-5 text-left">Tanggal Mulai</th>
                    <th class="px-6 py-5 text-left">Tanggal Selesai</th>
                    <th class="px-6 py-5 text-left">Target</th>
                    <th class="px-6 py-5 text-center">Status</th>
                    <th class="px-6 py-5 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">

                @forelse($jadwals as $index => $jadwal)

                <tr class="hover:bg-gray-50 transition">

                    <td class="px-6 py-5">
                        {{ $index + 1 }}
                    </td>

                    <td class="px-6 py-5 font-semibold text-[#1A3636]">
                        {{ $jadwal->komoditas->nama_komoditas ?? 'N/A' }}
                    </td>

                    <td class="px-6 py-5 font-medium text-[#1A3636]">
                        {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->translatedFormat('F Y') }}
                    </td>

                    <td class="px-6 py-5">
                        {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d M Y') }}
                    </td>

                    <td class="px-6 py-5">
                        {{ \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('d M Y') }}
                    </td>

                    <td class="px-6 py-5">
                        {{ $jadwal->target_capaian ?? '-' }}
                    </td>

                    <td class="px-6 py-5 text-center">

                        @if(now()->between($jadwal->tanggal_mulai, $jadwal->tanggal_selesai))
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                Aktif
                            </span>
                        @else
                            <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-semibold">
                                Selesai
                            </span>
                        @endif

                    </td>

                    <td class="px-6 py-5">

                        <div class="flex justify-center gap-2">

                            <!-- EDIT -->
                            <button
                                onclick="openEditModal(
                                    '{{ $jadwal->jadwal_id }}',
                                    '{{ $jadwal->komoditas_id }}',
                                    '{{ $jadwal->tanggal_mulai }}',
                                    '{{ $jadwal->tanggal_selesai }}',
                                    '{{ $jadwal->target_capaian }}'
                                )"
                                class="bg-blue-100 hover:bg-blue-500 hover:text-white text-blue-600 p-2 rounded-xl transition">

                                ✏️

                            </button>

                            <!-- DELETE -->
                            <button
                                onclick="openDeleteModal('{{ $jadwal->jadwal_id }}')"
                                class="bg-red-100 hover:bg-red-500 hover:text-white text-red-600 p-2 rounded-xl transition">

                                🗑️

                            </button>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="8" class="text-center py-20 text-gray-400">
                        Belum ada jadwal pelaporan
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<!-- OVERLAY -->
<div id="overlay"
    class="hidden fixed inset-0 bg-black/40 z-40">
</div>

<!-- MODAL TAMBAH -->
<div id="modalTambah"
    class="hidden fixed inset-0 z-50 flex justify-center items-center p-4">

    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl p-8">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-[#1A3636]">
                Tambah Jadwal
            </h2>

            <button onclick="closeModal('modalTambah')">
                ✖
            </button>
        </div>

        <form action="{{ route('kphl.jadwal.store') }}" method="POST">

            @csrf

            <div class="space-y-5">

                <!-- KOMODITAS -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-600">
                        Komoditas
                    </label>

                    <select name="komoditas_id" id="tambah_komoditas"
                        onchange="toggleTarget('tambah_target_container', this)"
                        class="w-full border border-gray-200 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#4F6F52]">

                        <option value="">-- Pilih Komoditas --</option>

                        @foreach($jenisProduks as $kom)
                            <option value="{{ $kom->komoditas_id }}"
                                data-target="{{ $kom->menggunakan_target ? '1' : '0' }}">
                                {{ $kom->nama_komoditas }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <!-- TANGGAL -->
                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-600">
                            Tanggal Mulai
                        </label>

                        <input type="date"
                            name="tanggal_mulai"
                            class="w-full border border-gray-200 rounded-2xl px-4 py-3">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-600">
                            Tanggal Selesai
                        </label>

                        <input type="date"
                            name="tanggal_selesai"
                            class="w-full border border-gray-200 rounded-2xl px-4 py-3">
                    </div>

                </div>

                <!-- TARGET (muncul jika komoditas butuh target) -->
                <div id="tambah_target_container" style="display:none">
                    <label class="block mb-2 text-sm font-semibold text-gray-600">
                        Target Capaian <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center gap-2 p-3 bg-amber-50 border border-amber-100 rounded-2xl mb-2">
                        <span class="text-[10px] text-amber-600 font-bold">⚠ Komoditas ini memerlukan target capaian pelaporan.</span>
                    </div>
                    <input type="number" name="target_capaian" min="0"
                        placeholder="Masukkan target capaian"
                        class="w-full border border-gray-200 rounded-2xl px-4 py-3">
                </div>

            </div>

            <div class="flex justify-end gap-3 mt-8">

                <button type="button"
                    onclick="closeModal('modalTambah')"
                    class="px-5 py-3 rounded-2xl border border-gray-200">

                    Batal

                </button>

                <button type="submit"
                    class="px-5 py-3 rounded-2xl bg-[#4F6F52] text-white hover:bg-[#1A3636] transition">

                    Simpan

                </button>

            </div>

        </form>

    </div>

</div>

<!-- MODAL EDIT -->
<div id="modalEdit"
    class="hidden fixed inset-0 z-50 items-center justify-center p-4">

    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl p-8">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-[#1A3636]">
                Edit Jadwal
            </h2>

            <button onclick="closeModal('modalEdit')">
                ✖
            </button>
        </div>

        <form id="formEdit" method="POST">

            @csrf
            @method('PUT')

            <div class="space-y-5">

                <div>
                    <label class="block mb-2 text-sm font-semibold">
                        Komoditas
                    </label>

                    <select name="komoditas_id"
                        id="edit_komoditas"
                        onchange="toggleTarget('edit_target_container', this)"
                        class="w-full border border-gray-200 rounded-2xl px-4 py-3">

                        <option value="">-- Pilih Komoditas --</option>

                        @foreach($jenisProduks as $kom)
                            <option value="{{ $kom->komoditas_id }}"
                                data-target="{{ $kom->menggunakan_target ? '1' : '0' }}">
                                {{ $kom->nama_komoditas }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="block mb-2 text-sm font-semibold">
                            Tanggal Mulai
                        </label>

                        <input type="date"
                            name="tanggal_mulai"
                            id="edit_mulai"
                            class="w-full border border-gray-200 rounded-2xl px-4 py-3">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-semibold">
                            Tanggal Selesai
                        </label>

                        <input type="date"
                            name="tanggal_selesai"
                            id="edit_selesai"
                            class="w-full border border-gray-200 rounded-2xl px-4 py-3">
                    </div>

                </div>

                <!-- TARGET (muncul jika komoditas butuh target) -->
                <div id="edit_target_container" style="display:none">
                    <label class="block mb-2 text-sm font-semibold">
                        Target Capaian <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center gap-2 p-3 bg-amber-50 border border-amber-100 rounded-2xl mb-2">
                        <span class="text-[10px] text-amber-600 font-bold">⚠ Komoditas ini memerlukan target capaian pelaporan.</span>
                    </div>
                    <input type="number" name="target_capaian" id="edit_target" min="0"
                        class="w-full border border-gray-200 rounded-2xl px-4 py-3">
                </div>

            </div>

            <div class="flex justify-end gap-3 mt-8">

                <button type="button"
                    onclick="closeModal('modalEdit')"
                    class="px-5 py-3 border border-gray-200 rounded-2xl">

                    Batal

                </button>

                <button type="submit"
                    class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl">

                    Update

                </button>

            </div>

        </form>

    </div>

</div>

<script>

    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.getElementById('overlay').classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.getElementById('overlay').classList.add('hidden');
    }

    function toggleTarget(containerId, selectEl) {
        const selected = selectEl.options[selectEl.selectedIndex];
        const usesTarget = selected && selected.dataset.target === '1';
        document.getElementById(containerId).style.display = usesTarget ? 'block' : 'none';
    }

    function openEditModal(id, komoditas, mulai, selesai, target) {

        document.getElementById('formEdit').action =
            `/kphl/jadwal/${id}`;

        document.getElementById('edit_komoditas').value = komoditas;
        document.getElementById('edit_mulai').value = mulai;
        document.getElementById('edit_selesai').value = selesai;

        // Trigger toggle to show/hide target
        const editSelect = document.getElementById('edit_komoditas');
        toggleTarget('edit_target_container', editSelect);

        if (document.getElementById('edit_target_container').style.display !== 'none') {
            document.getElementById('edit_target').value = target || '';
        }

        openModal('modalEdit');
    }

    function openDeleteModal(id) {

        if(confirm('Yakin ingin menghapus jadwal ini?')) {

            let form = document.createElement('form');

            form.method = 'POST';
            form.action = `/kphl/jadwal/${id}`;

            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;

            document.body.appendChild(form);

            form.submit();
        }
    }

</script>

@endsection