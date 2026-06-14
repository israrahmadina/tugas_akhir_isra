@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')

<div class="max-w-3xl mx-auto">

    <!-- HEADER -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-[#1A3636]">Tambah Produk</h2>
        <p class="text-sm text-gray-500">Isi data produk kelompok binaan</p>
    </div>

    <!-- FORM -->
    <div class="bg-white rounded-2xl shadow p-6">
       <form action="{{ route('kelompok.produk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- GAMBAR -->
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Gambar Produk</label>
                <input type="file" name="gambar_produk"
                    class="w-full border border-gray-300 rounded-lg p-2">
            </div>

            <!-- NAMA PRODUK -->
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Nama Produk / Usaha</label>
                <input type="text" name="nama_usaha"
                    class="w-full border border-gray-300 rounded-lg p-3 focus:ring focus:ring-green-200"
                    placeholder="Masukkan nama produk/usaha" required>
            </div>

            <!-- KOMODITAS -->
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Komoditas</label>
                <select name="komoditas_id" required
                    class="w-full border border-gray-300 rounded-lg p-3 focus:ring focus:ring-green-200">
                    <option value="">-- Pilih Komoditas --</option>
                    @foreach($komoditas as $item)
                        <option value="{{ $item->komoditas_id }}">
                            {{ $item->nama_komoditas }} ({{ $item->kategori->nama_kategori }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- DESKRIPSI -->
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Deskripsi</label>
                <textarea name="deskripsi" rows="4"
                    class="w-full border border-gray-300 rounded-lg p-3 focus:ring focus:ring-green-200"
                    placeholder="Tulis deskripsi produk"></textarea>
            </div>

            <!-- ALAMAT (API WILAYAH) -->
            <div class="border-t pt-6 mt-6 mb-6">
                <h3 class="text-lg font-bold text-[#1A3636] mb-4">Alamat Usaha / Produksi</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Provinsi</label>
                        <select id="produk_provinsi" name="provinsi" required
                            class="w-full border border-gray-300 rounded-lg p-3 focus:ring focus:ring-green-200 text-sm">
                            <option value="">Pilih Provinsi</option>
                        </select>
                        <input type="hidden" name="provinsi_nama" id="produk_provinsi_nama">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2">Kabupaten</label>
                        <select id="produk_kabupaten" name="kabupaten" required
                            class="w-full border border-gray-300 rounded-lg p-3 focus:ring focus:ring-green-200 text-sm">
                            <option value="">Pilih Kabupaten</option>
                        </select>
                        <input type="hidden" name="kabupaten_nama" id="produk_kabupaten_nama">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Kecamatan</label>
                        <select id="produk_kecamatan" name="kecamatan" required
                            class="w-full border border-gray-300 rounded-lg p-3 focus:ring focus:ring-green-200 text-sm">
                            <option value="">Pilih Kecamatan</option>
                        </select>
                        <input type="hidden" name="kecamatan_nama" id="produk_kecamatan_nama">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2">Desa / Kelurahan</label>
                        <select id="produk_desa" name="desa" required
                            class="w-full border border-gray-300 rounded-lg p-3 focus:ring focus:ring-green-200 text-sm">
                            <option value="">Pilih Desa</option>
                        </select>
                        <input type="hidden" name="desa_nama" id="produk_desa_nama">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2">Detail Alamat</label>
                    <textarea name="alamat_detail" id="produk_alamat_detail" rows="3" required
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring focus:ring-green-200 text-sm"
                        placeholder="Nama jalan, nomor rumah, RT/RW, dll."></textarea>
                </div>
            </div>

            <!-- BUTTON -->
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('kelompok.produk') }}"
                   class="px-5 py-2 border rounded-lg text-gray-600 hover:bg-gray-100">
                   Batal
                </a>

                <button type="submit"
                    class="px-6 py-2 bg-[#4F6F52] text-white rounded-lg hover:bg-[#1A3636]">
                    Simpan
                </button>
            </div>

        </form>
    </div>

</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const savedAddress = @json(auth()->user()->kelompokBinaan->alamat_detail) || { detail: @json(auth()->user()->kelompokBinaan->alamat_lengkap) };
        initRegionSelects('produk', savedAddress);
    });

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
</script>
@endpush