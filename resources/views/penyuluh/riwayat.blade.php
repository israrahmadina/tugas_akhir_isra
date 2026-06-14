@extends('layouts.app')

@section('title', 'Riwayat Verifikasi')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h3 class="text-3xl font-black text-[#1A3636] tracking-tighter uppercase italic">Riwayat Verifikasi</h3>
        <p class="text-sm text-gray-500 mt-1 font-medium">Daftar lengkap verifikasi laporan yang telah diproses.</p>
    </div>

    @if($riwayats->isEmpty())
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-8 text-center">
            <p class="text-sm text-gray-400 font-bold uppercase tracking-widest">Belum ada riwayat verifikasi</p>
        </div>
    @else
        <!-- Riwayat Verifikasi Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gradient-to-r from-[#1A3636] to-[#2A4E4E] text-white">
                        <tr>
                            <th class="px-4 py-3 text-left font-black uppercase tracking-wide">Kelompok</th>
                            <th class="px-4 py-3 text-left font-black uppercase tracking-wide">Periode</th>
                            <th class="px-4 py-3 text-left font-black uppercase tracking-wide">Status</th>
                            <th class="px-4 py-3 text-left font-black uppercase tracking-wide">Tanggal </th>
                            <th class="px-4 py-3 text-left font-black uppercase tracking-wide">Keterangan</th>
                            <th class="px-4 py-3 text-center font-black uppercase tracking-wide">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($riwayats as $riwayat)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <!-- Kelompok -->
                                <td class="px-4 py-3">
                                    <div class="font-bold text-gray-800">
                                        {{ optional($riwayat->pelaporan->kelompokBinaan)->nama_kelompok ?? 'N/A' }}
                                    </div>
                                </td>

                                <!-- Periode -->
                                <td class="px-4 py-3">
                                    <div class="text-xs text-gray-600">
                                        {{ optional($riwayat->pelaporan->jadwal)->periode_nama ?? 'N/A' }}
                                    </div>
                                </td>

                               <!-- Status -->
                                 <td class="px-4 py-3">
                                   @if($riwayat->status_validasi === 'diterima')
                                         <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-100 text-blue-700">
                                            ✓ Divalidasi
                                         </span>
                                    @elseif($riwayat->status_validasi === 'ditolak')
                                         <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-red-100 text-red-700">
                                            ✗ Ditolak KPHL
                                         </span>

                                    @elseif($riwayat->status_verifikasi === 'diterima')
                                         <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-green-100 text-green-700">
                                            ✓ Diverifikasi Penyuluh
                                         </span>

                                    @elseif($riwayat->status_verifikasi === 'ditolak')
                                         <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-red-100 text-red-700">
                                             ✗ Ditolak Penyuluh
                                         </span>

                                    @else
                                         <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-yellow-100 text-yellow-700">
                                            ⏳ Pending
                                         </span>
                                    @endif
                                    </td>

                                <!-- Tanggal Verifikasi -->
                                <td class="px-4 py-3">
                                    <div class="text-xs text-gray-600 font-semibold">

                                       @if($riwayat->status_validasi === 'diterima' || $riwayat->status_validasi === 'ditolak')

                                           {{ \Carbon\Carbon::parse($riwayat->updated_at)->format('d/m/Y H:i') }}

                                       @else

                                           {{ \Carbon\Carbon::parse($riwayat->tanggal)->format('d/m/Y H:i') }}

                                               @endif

                                    </div>
                                </td>

                           


                                <!-- Catatan Verifikasi -->
                                <td class="px-4 py-3">
                                    <div class="text-xs text-gray-700 max-w-xs truncate" title="{{ $riwayat->catatan_verifikasi ?? '-' }}">
                                        @if($riwayat->catatan_verifikasi)
                                            <span class="italic">{{ Str::limit($riwayat->catatan_verifikasi, 50) }}</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Aksi -->
                                <td class="px-4 py-3 text-center">
                                    <button onclick="showDetailRiwayat({{ $riwayat->toJson() }})" 
                                        class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold rounded transition">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($riwayats->hasPages())
                <div class="px-4 py-4 border-t flex justify-center">
                    {{ $riwayats->links() }}
                </div>
            @endif
        </div>
    @endif
</div>

<!-- Modal Detail Riwayat -->
<div id="modalDetailRiwayat" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg max-w-2xl w-full mx-4 max-h-96 overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-[#1A3636] to-[#2A4E4E] text-white px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-black uppercase tracking-tight">Detail Riwayat Verifikasi</h3>
            <button onclick="closeDetailRiwayat()" class="text-2xl font-bold hover:text-gray-200">&times;</button>
        </div>
        
        <div class="p-6">
            <div id="detailContent"></div>
            
            <div class="mt-6 flex justify-end gap-3">
                <button onclick="closeDetailRiwayat()" 
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold rounded transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showDetailRiwayat(riwayat) {
    const modal = document.getElementById('modalDetailRiwayat');
    const content = document.getElementById('detailContent');
    
    const tanggal = new Date(riwayat.tanggal).toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });

    let statusBadge = '';
    let statusColor = '';
    
    if (riwayat.status_validasi === 'diterima') {

    statusBadge = '✓ DIVALIDASI KPHL';
    statusColor = 'bg-blue-100 text-blue-700';

} else if (riwayat.status_validasi === 'ditolak') {

    statusBadge = '✗ DITOLAK KPHL';
    statusColor = 'bg-red-100 text-red-700';

} else if (riwayat.status_verifikasi === 'diterima') {

    statusBadge = '✓ DIVERIFIKASI PENYULUH';
    statusColor = 'bg-green-100 text-green-700';

} else if (riwayat.status_verifikasi === 'ditolak') {

    statusBadge = '✗ DITOLAK PENYULUH';
    statusColor = 'bg-red-100 text-red-700';

} else {

    statusBadge = '⏳ PENDING';
    statusColor = 'bg-yellow-100 text-yellow-700';

}

    content.innerHTML = `
        <div class="space-y-4">
            <div>
                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Status Verifikasi</label>
                <span class="px-3 py-1 rounded-full text-sm font-bold uppercase tracking-wider ${statusColor}">
                    ${statusBadge}
                </span>
            </div>
            
            <div>
                <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Tanggal Verifikasi</label>
                <p class="text-sm text-gray-700">${tanggal}</p>
            </div>

            ${
             riwayat.status_validasi === 'diterima' ||
             riwayat.status_validasi === 'ditolak'
                 ? `
             <div>
            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">
                Tanggal Validasi
                </label>
                <p class="text-sm text-gray-700">
            ${new Date(riwayat.updated_at).toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            })}
        </p>
    </div>
    `
    : ''
    }

            ${riwayat.catatan_verifikasi ? `
                <div>
                    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">
                        ${riwayat.status_verifikasi === 'ditolak' ? 'Alasan Penolakan' : 'Catatan'}
                    </label>
                    <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded border border-gray-200">
                        ${riwayat.catatan_verifikasi}
                    </p>
                </div>
            ` : ''}

           <div class="pt-4 border-t space-y-4">

    <div>
        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">
            Diverifikasi Oleh Penyuluh
        </label>
       <p class="text-sm text-gray-700">
    ${riwayat.pelaporan?.kelompokBinaan?.penyuluh?.user?.name ?? 'Nama Penyuluh Tidak Ada'}
        </p>
    </div>

   <div>
    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">
             Divalidasi Oleh KPHL
              </label>

              <p class="text-sm text-gray-700">
                   ${
                     riwayat.status_validasi === 'diterima'
                      ? (riwayat.user?.name ?? 'KPHL')
                      : 'Belum divalidasi'
                    }
              </p>
           </div>

         ${
        riwayat.catatan_validasi ? `
        <div>
            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">
                Catatan Validasi KPHL
            </label>

            <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded border border-gray-200">
                ${riwayat.catatan_validasi}
            </p>
        </div>
        ` : ''
    }

</div>
    `;

    modal.classList.remove('hidden');
}

function closeDetailRiwayat() {
    document.getElementById('modalDetailRiwayat').classList.add('hidden');
}
</script>

@endsection
