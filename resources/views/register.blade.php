<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Kelompok Usaha</title>
    @vite(['resources/js/app.js'])
</head>

<body class="min-h-screen flex items-center justify-center py-6 px-4 bg-[#f0f4f0]">

<form method="POST"
      action="{{ route('register.post') }}"
      enctype="multipart/form-data"
      class="bg-white w-full max-w-5xl rounded-2xl shadow-lg overflow-hidden">
    @csrf

    <!-- HEADER -->
    <div class="px-8 py-5 flex items-center justify-between bg-[#1C3A2A]">
        <div>
            <h2 class="text-base font-semibold text-white">Registrasi Kelompok Usaha</h2>
            <p class="text-xs text-white/50 mt-0.5">Lengkapi semua informasi di bawah ini</p>
        </div>
        <a href="{{ route('login') }}" class="text-xs text-white/50 hover:text-white/80 transition-colors">
            Sudah punya akun? <span class="text-white/80 font-medium underline underline-offset-2">Masuk</span>
        </a>
    </div>

    <div class="px-8 py-6 space-y-6 overflow-y-auto max-h-[calc(100vh-140px)]">

        <!-- ERROR -->
        @if ($errors->any())
            <div class="p-3 bg-red-50 border border-red-100 text-red-600 rounded-xl text-xs">
                <ul class="space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="p-3 bg-green-50 border border-green-100 text-green-700 rounded-xl text-xs">
                {{ session('success') }}
            </div>
        @endif

        <!-- ROW 1: Akun + Foto + Info Usaha -->
        <div class="grid grid-cols-3 gap-5">

            <!-- AKSES AKUN KETUA -->
            <div class="space-y-3">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-4 rounded-full bg-[#3B6D11]"></div>
                    <p class="text-xs font-semibold text-gray-400 tracking-widest uppercase">Akses Akun Ketua</p>
                </div>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" placeholder="Nama Ketua"
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg outline-none focus:border-[#3B6D11] focus:ring-1 focus:ring-[#3B6D11]/20 transition-all bg-gray-50">
                <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="Email"
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg outline-none focus:border-[#3B6D11] focus:ring-1 focus:ring-[#3B6D11]/20 transition-all bg-gray-50">
                <input type="password" name="password" id="password" placeholder="Password (min. 6 karakter)"
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg outline-none focus:border-[#3B6D11] focus:ring-1 focus:ring-[#3B6D11]/20 transition-all bg-gray-50">
            </div>

            <!-- FOTO PROFIL -->
            <div class="flex flex-col items-center gap-3">
                <div class="flex items-center gap-2 justify-center w-full">
                    <div class="w-1 h-4 rounded-full bg-[#3B6D11]"></div>
                    <p class="text-xs font-semibold text-gray-400 tracking-widest uppercase">Foto Profil</p>
                </div>
                <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-gray-200 flex-shrink-0 shadow-sm">
                    <img id="previewFoto" src="https://placehold.co/200x200" class="w-full h-full object-cover">
                </div>
                <input type="file" id="foto_profile" name="foto_profile" accept="image/jpeg,image/png,image/webp"
                    class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-gray-100 file:text-gray-600 hover:file:bg-gray-200 file:font-medium transition-all">
                <p class="text-xs text-gray-400">JPG, PNG, WEBP (Maks 2MB)</p>
            </div>

            <!-- INFO KELOMPOK USAHA -->
            <div class="space-y-3">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-4 rounded-full bg-[#3B6D11]"></div>
                    <p class="text-xs font-semibold text-gray-400 tracking-widest uppercase">Informasi Kelompok Usaha</p>
                </div>
                <input type="text" name="nama_usaha" id="nama_usaha" value="{{ old('nama_usaha') }}" placeholder="Nama Kelompok Usaha"
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg outline-none focus:border-[#3B6D11] focus:ring-1 focus:ring-[#3B6D11]/20 transition-all bg-gray-50">
                <input type="text" name="legalitas" id="legalitas" value="{{ old('legalitas') }}" placeholder="Legalitas Perizinan (opsional)"
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg outline-none focus:border-[#3B6D11] focus:ring-1 focus:ring-[#3B6D11]/20 transition-all bg-gray-50">
                <textarea name="deskripsi" id="deskripsi" placeholder="Deskripsi usaha (opsional)"
                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg outline-none focus:border-[#3B6D11] focus:ring-1 focus:ring-[#3B6D11]/20 resize-none h-[82px] transition-all bg-gray-50">{{ old('deskripsi') }}</textarea>
            </div>

        </div>

        <!-- DIVIDER -->
        <div class="relative">
            <div class="border-t border-gray-100"></div>
            <div class="absolute left-0 top-0 w-12 border-t-2 border-[#3B6D11]/40 -mt-px"></div>
        </div>

        <!-- ROW 2: Alamat -->
        <div>
            <div class="flex items-center gap-2 mb-3">
                <div class="w-1 h-4 rounded-full bg-[#3B6D11]"></div>
                <p class="text-xs font-semibold text-gray-400 tracking-widest uppercase">Alamat Kelompok Usaha</p>
            </div>
            <div class="grid grid-cols-4 gap-3 mb-3">
                <div>
                    <select id="register_provinsi" name="provinsi" required
                        class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg outline-none focus:border-[#3B6D11] bg-gray-50 text-gray-700">
                        <option value="">Provinsi</option>
                    </select>
                    <input type="hidden" name="provinsi_nama" id="register_provinsi_nama" value="{{ old('provinsi_nama') }}">
                </div>
                <div>
                    <select id="register_kabupaten" name="kabupaten" required
                        class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg outline-none focus:border-[#3B6D11] bg-gray-50 text-gray-700">
                        <option value="">Kabupaten</option>
                    </select>
                    <input type="hidden" name="kabupaten_nama" id="register_kabupaten_nama" value="{{ old('kabupaten_nama') }}">
                </div>
                <div>
                    <select id="register_kecamatan" name="kecamatan" required
                        class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg outline-none focus:border-[#3B6D11] bg-gray-50 text-gray-700">
                        <option value="">Kecamatan</option>
                    </select>
                    <input type="hidden" name="kecamatan_nama" id="register_kecamatan_nama" value="{{ old('kecamatan_nama') }}">
                </div>
                <div>
                    <select id="register_desa" name="desa" required
                        class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg outline-none focus:border-[#3B6D11] bg-gray-50 text-gray-700">
                        <option value="">Desa</option>
                    </select>
                    <input type="hidden" name="desa_nama" id="register_desa_nama" value="{{ old('desa_nama') }}">
                </div>
            </div>
            <textarea name="alamat_detail" id="register_alamat_detail" placeholder="Detail alamat (jalan, RT/RW, dll.)" required
                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg outline-none focus:border-[#3B6D11] focus:ring-1 focus:ring-[#3B6D11]/20 h-20 resize-none transition-all bg-gray-50">{{ old('alamat_detail') }}</textarea>
        </div>

        <!-- INFO -->
        <div class="p-3 bg-blue-50 border border-blue-100 rounded-xl text-xs text-blue-700">
            <strong>Catatan:</strong> Setelah registrasi, data kelompok usaha Anda akan diverifikasi oleh admin. Skema dan produk usaha akan dilengkapi setelah verifikasi.
        </div>

    </div>

    <!-- FOOTER -->
    <div class="px-8 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50/60">
        <a href="{{ route('login') }}"
            class="px-5 py-2.5 text-sm rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-100 transition-colors">
            Batal
        </a>
        <button type="submit"
            class="px-5 py-2.5 text-sm rounded-lg bg-[#3B6D11] hover:bg-[#27500A] text-white font-medium transition-colors shadow-sm">
            Daftarkan Kelompok Usaha
        </button>
    </div>

</form>

<script>
    document.getElementById('foto_profile').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = ev => document.getElementById('previewFoto').src = ev.target.result;
            reader.readAsDataURL(file);
        }
    });

    function initRegionSelects(prefix, savedData = null) {
        const provSelect = document.getElementById(`${prefix}_provinsi`);
        const kabSelect  = document.getElementById(`${prefix}_kabupaten`);
        const kecSelect  = document.getElementById(`${prefix}_kecamatan`);
        const desSelect  = document.getElementById(`${prefix}_desa`);
        const detailTextarea = document.getElementById(`${prefix}_alamat_detail`);
        const provNamaInput  = document.getElementById(`${prefix}_provinsi_nama`);
        const kabNamaInput   = document.getElementById(`${prefix}_kabupaten_nama`);
        const kecNamaInput   = document.getElementById(`${prefix}_kecamatan_nama`);
        const desNamaInput   = document.getElementById(`${prefix}_desa_nama`);

        function resetSelect(select, placeholder) {
            select.innerHTML = `<option value="">${placeholder}</option>`;
        }

        fetch('/wilayah/provinsi')
            .then(res => res.json())
            .then(data => {
                resetSelect(provSelect, 'Provinsi');
                data.forEach(p => {
                    const opt = document.createElement('option');
                    opt.value = p.code; opt.textContent = p.name;
                    provSelect.appendChild(opt);
                });
                if (savedData?.provinsi?.id) {
                    provSelect.value = savedData.provinsi.id;
                    if (provNamaInput) provNamaInput.value = savedData.provinsi.nama;
                    loadKabupaten(savedData.provinsi.id, savedData.kabupaten);
                }
            });

        provSelect.onchange = function() {
            const val = this.value;
            if (provNamaInput) provNamaInput.value = val ? this.options[this.selectedIndex].text : '';
            resetSelect(kabSelect, 'Kabupaten');
            resetSelect(kecSelect, 'Kecamatan');
            resetSelect(desSelect, 'Desa');
            if (val) loadKabupaten(val);
        };

        function loadKabupaten(provId, savedKab = null) {
            fetch(`/wilayah/kabupaten/${provId}`).then(res => res.json()).then(data => {
                resetSelect(kabSelect, 'Kabupaten');
                data.forEach(k => { const o = document.createElement('option'); o.value = k.code; o.textContent = k.name; kabSelect.appendChild(o); });
                if (savedKab?.id) { kabSelect.value = savedKab.id; if (kabNamaInput) kabNamaInput.value = savedKab.nama; loadKecamatan(savedKab.id, savedData.kecamatan); }
            });
        }

        kabSelect.onchange = function() {
            const val = this.value;
            if (kabNamaInput) kabNamaInput.value = val ? this.options[this.selectedIndex].text : '';
            resetSelect(kecSelect, 'Kecamatan'); resetSelect(desSelect, 'Desa');
            if (val) loadKecamatan(val);
        };

        function loadKecamatan(kabId, savedKec = null) {
            fetch(`/wilayah/kecamatan/${kabId}`).then(res => res.json()).then(data => {
                resetSelect(kecSelect, 'Kecamatan');
                data.forEach(k => { const o = document.createElement('option'); o.value = k.code; o.textContent = k.name; kecSelect.appendChild(o); });
                if (savedKec?.id) { kecSelect.value = savedKec.id; if (kecNamaInput) kecNamaInput.value = savedKec.nama; loadDesa(savedKec.id, savedData.desa); }
            });
        }

        kecSelect.onchange = function() {
            const val = this.value;
            if (kecNamaInput) kecNamaInput.value = val ? this.options[this.selectedIndex].text : '';
            resetSelect(desSelect, 'Desa');
            if (val) loadDesa(val);
        };

        function loadDesa(kecId, savedDesa = null) {
            fetch(`/wilayah/desa/${kecId}`).then(res => res.json()).then(data => {
                resetSelect(desSelect, 'Desa');
                data.forEach(d => { const o = document.createElement('option'); o.value = d.code; o.textContent = d.name; desSelect.appendChild(o); });
                if (savedDesa?.id) { desSelect.value = savedDesa.id; if (desNamaInput) desNamaInput.value = savedDesa.nama; }
            });
        }

        desSelect.onchange = function() {
            const val = this.value;
            if (desNamaInput) desNamaInput.value = val ? this.options[this.selectedIndex].text : '';
        };

        if (savedData?.detail && detailTextarea) detailTextarea.value = savedData.detail;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const savedAddress = {
            provinsi:  { id: @json(old('provinsi')),  nama: @json(old('provinsi_nama'))  },
            kabupaten: { id: @json(old('kabupaten')), nama: @json(old('kabupaten_nama')) },
            kecamatan: { id: @json(old('kecamatan')), nama: @json(old('kecamatan_nama')) },
            desa:      { id: @json(old('desa')),      nama: @json(old('desa_nama'))      },
            detail: @json(old('alamat_detail'))
        };
        initRegionSelects('register', savedAddress);
    });
</script>

</body>
</html>