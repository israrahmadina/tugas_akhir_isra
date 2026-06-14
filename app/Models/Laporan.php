<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporan';
    protected $primaryKey = 'laporan_id';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'update_at';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->laporan_id) {
                $last = static::orderBy('laporan_id', 'desc')->first();
                $lastNumber = $last ? (int) substr($last->laporan_id, 3) : 0;
                $model->laporan_id = 'LPR' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    protected $fillable = [
        'laporan_id',
        'jadwal_id',
        'kelompok_id',
        'jumlah_produksi',
        'jumlah_stup',
        'status_verifikasi_lembaga',
        'status_verifikasi_penyuluh',
        'status_validasi_seksi',
    ];

    protected $casts = [
        'jumlah_produksi' => 'decimal:2',
        'jumlah_stup'     => 'integer',
    ];

    public function kelompokUsaha()
    {
        return $this->belongsTo(KelompokUsaha::class, 'kelompok_id', 'kelompok_id');
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalPelaporan::class, 'jadwal_id', 'jadwal_id');
    }

    public function buktis()
    {
        return $this->hasMany(PelaporanBukti::class, 'laporan_id', 'laporan_id');
    }

    public function riwayatVerifikasis()
    {
        return $this->hasMany(RiwayatVerifikasi::class, 'laporan_id', 'laporan_id');
    }
}
