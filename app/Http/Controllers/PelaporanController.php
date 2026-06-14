<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\UsahaKelompok;
use App\Models\AtributPelaporan;
use App\Models\PelaporanNilai;
use App\Models\PelaporanBukti;
use App\Models\JadwalPelaporan;
use App\Models\DetailLaporan;
use App\Models\Notifikasi;
use App\Models\Rkt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PelaporanController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role->role_name === 'Kelompok Binaan') {
            $kelompokId = $user->kelompokBinaan->kelompok_id;
            
            $usahas = UsahaKelompok::where('kelompok_id', $kelompokId)->with('komoditas.kategori')->get();
            
            $jadwals = JadwalPelaporan::orderBy('tanggal_mulai', 'asc')
                 ->get();

            $pelaporans = Laporan::where('kelompok_id', $kelompokId)
                ->with('jadwal', 'details.usaha.komoditas', 'details.nilais.atribut', 'produk.komoditas', 'buktis')
                ->orderBy('created_at', 'desc')
                ->get();

            // Build $tugasPelaporan: combination of schedules and products
            $tugasPelaporan = [];
            $today = date('Y-m-d');
            
            foreach ($jadwals as $jadwal) {
                foreach ($usahas as $usaha) {
                    // Check if laporan already exists for this jadwal and usaha
                    $laporan = Laporan::where('jadwal_id', $jadwal->jadwal_id)
                        ->whereHas('details', function ($q) use ($usaha) {
                            $q->where('usaha_id', $usaha->usaha_id);
                        })
                        ->first();
                    
                    // Determine status
                    $status = 'Belum Dimulai';
                    if ($laporan) {
                        $status = 'Selesai';
                    } elseif ($today >= $jadwal->tanggal_mulai && $today <= $jadwal->tanggal_selesai) {
                        $status = 'Aktif';
                    } elseif ($today > $jadwal->tanggal_selesai) {
                        $status = 'Selesai';
                    }
                    
                    $tugasPelaporan[] = [
                        'jadwal' => $jadwal,
                        'produk' => $usaha,
                        'status' => $status,
                        'laporan' => $laporan,
                    ];
                }
            }

            // Collect atribut pelaporan grouped by komoditas for the kelompok's usaha
            $komoditasIds = $usahas->pluck('komoditas_id')->unique()->filter();
            $atributByKomoditas = AtributPelaporan::with('komoditas')
                ->whereIn('komoditas_id', $komoditasIds)
                ->get()
                ->groupBy(function ($atribut) {
                    return $atribut->komoditas->nama_komoditas ?? 'Umum';
                });

            return view('kelompok.pelaporan', compact('pelaporans', 'usahas', 'jadwals', 'tugasPelaporan', 'atributByKomoditas'));
        }

        // Admin view
        $pelaporans = Laporan::with('kelompokBinaan', 'jadwal', 'produk.komoditas', 'nilais.atribut', 'buktis')->get();
        return view('admin.index_pelaporan', compact('pelaporans'));
    }

    public function create(Request $request)
    {
        $usahaId = $request->usaha_id;
        $usaha = UsahaKelompok::with('komoditas.kategori')->findOrFail($usahaId);
        
        $atributs = AtributPelaporan::where('komoditas_id', $usaha->komoditas_id)->get();

        // Jika ini request untuk EDIT, ambil juga nilainya
        $nilaiExist = [];
        $buktiExist = [];
        if ($request->laporan_id) {
            $laporan = Laporan::with('details.nilais', 'buktis')->findOrFail($request->laporan_id);
            foreach ($laporan->details as $detail) {
                foreach ($detail->nilais as $n) {
                    $nilaiExist[$n->atribut_id] = $n->value;
                }
            }
            foreach ($laporan->buktis as $b) {
                $buktiExist[$b->atribut_id] = $b->foto_bukti;
            }
        }

        return response()->json([
            'atributs' => $atributs,
            'nilaiExist' => $nilaiExist ?: null,
            'buktiExist' => $buktiExist ?: null,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'usaha_id' => 'required|exists:usaha_kelompok,usaha_id',
            'jadwal_id' => 'required|exists:jadwal_pelaporan,jadwal_id',
            'atribut' => 'required|array',
            'bukti' => 'nullable|array',
            'bukti.*' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $usaha = UsahaKelompok::findOrFail($request->usaha_id);
        $kelompokId = $usaha->kelompok_id;

        DB::beginTransaction();
        try {
            $jadwal = JadwalPelaporan::findOrFail($request->jadwal_id);

            $pelaporan = Laporan::create([
                'jadwal_id'          => $request->jadwal_id,
                'kelompok_id'        => $kelompokId,
                'target'             => $jadwal->target_capaian,
                'status_verifikasi'  => 'pending',
                'status_validasi'    => 'pending',
            ]);

            $detail = DetailLaporan::create([
                'laporan_id' => $pelaporan->laporan_id,
                'usaha_id' => $request->usaha_id,
            ]);

            foreach ($request->atribut as $atributId => $value) {
                PelaporanNilai::create([
                    'detail_id' => $detail->detail_id,
                    'atribut_id' => $atributId,
                    'value' => $value,
                ]);
            }

            if ($request->hasFile('bukti')) {
                foreach ($request->file('bukti') as $atributId => $file) {
                    if (!$file) {
                        continue;
                    }

                    $path = $file->store('pelaporan/bukti', 'public');
                    PelaporanBukti::create([
                        'laporan_id' => $pelaporan->laporan_id,
                        'atribut_id' => $atributId,
                        'foto_bukti' => $path,
                    ]);
                }
            }

            DB::commit();

            // Kirim notifikasi ke Penyuluh yang membina kelompok ini
            $usaha->load('kelompok.penyuluh.user');
            $kelompok = $usaha->kelompok;
            if ($kelompok && $kelompok->penyuluh && $kelompok->penyuluh->user) {
                $jadwal = JadwalPelaporan::find($request->jadwal_id);
                $periodeLabel = $jadwal  ? $jadwal->tanggal_mulai . ' s/d ' . $jadwal->tanggal_selesai : '-';
                Notifikasi::create([
                    'user_id'  => $kelompok->penyuluh->user->user_id,
                    'pesan'    => 'Laporan baru dari "' . $kelompok->nama_kelompok . '" untuk periode ' . $periodeLabel . ' telah dikirimkan dan menunggu verifikasi.',
                    'is_read'  => false,
                ]);
            }

            return redirect()->back()->with('success', 'Laporan berhasil dikirim');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal mengirim laporan: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $pelaporan = Laporan::findOrFail($id);

        if ($pelaporan->status_verifikasi === 'diverifikasi') {
            return redirect()->back()->withErrors(['error' => 'Laporan yang sudah diverifikasi tidak dapat diubah.']);
        }

        $request->validate([
    
            'atribut' => 'required|array',
            'bukti' => 'nullable|array',
            'bukti.*' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $pelaporan->update([
                'status_verifikasi' => 'pending',
                'status_validasi' => 'pending',
            ]);

            // Update nilai atribut melalui detail_laporan
            foreach ($pelaporan->details as $detail) {
                foreach ($request->atribut as $atributId => $value) {
                    PelaporanNilai::updateOrCreate(
                        ['detail_id' => $detail->detail_id, 'atribut_id' => $atributId],
                        ['value' => $value]
                    );
                }
            }

            if ($request->hasFile('bukti')) {
                foreach ($request->file('bukti') as $atributId => $file) {
                    if (!$file) {
                        continue;
                    }

                    $path = $file->store('pelaporan/bukti', 'public');
                    PelaporanBukti::updateOrCreate(
                        ['laporan_id' => $pelaporan->laporan_id, 'atribut_id' => $atributId],
                        ['foto_bukti' => $path]
                    );
                }
            }

            DB::commit();

            // Kirim notifikasi ke Penyuluh bahwa laporan diperbarui
            $detail = $pelaporan->details()->with('usaha.kelompok.penyuluh.user')->first();
            if ($detail && $detail->usaha && $detail->usaha->kelompok && $detail->usaha->kelompok->penyuluh && $detail->usaha->kelompok->penyuluh->user) {
                $kelompok = $detail->usaha->kelompok;
                $jadwal = $pelaporan->jadwal;
                $periodeLabel = $jadwal   ? $jadwal->tanggal_mulai . ' s/d ' . $jadwal->tanggal_selesai : '-';
                Notifikasi::create([
                    'user_id'  => $kelompok->penyuluh->user->user_id,
                    'pesan'    => 'Laporan dari "' . $kelompok->nama_kelompok . '" untuk periode ' . $periodeLabel . ' telah diperbarui dan memerlukan verifikasi ulang.',
                    'is_read'  => false,
                ]);
            }

            return redirect()->back()->with('success', 'Laporan berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal memperbarui laporan: ' . $e->getMessage()]);
        }
    }
}
