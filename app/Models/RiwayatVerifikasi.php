<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatVerifikasi extends Model
{
    use HasFactory;

    protected $table = 'riwayat_verifikasi';
    protected $primaryKey = 'riwayat_id';
    public $incrementing = false;
    protected $keyType = 'string';

    const UPDATED_AT = null;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->riwayat_id) {
                $last = static::orderBy('riwayat_id', 'desc')->first();
                $lastNumber = $last ? (int) substr($last->riwayat_id, 3) : 0;
                $model->riwayat_id = 'RWT' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    protected $fillable = [
        'riwayat_id',
        'user_id',
        'laporan_id',
        'status_verifikasi_lembaga',
        'catatan_verifikasi_lembaga',
        'status_verifikasi_penyuluh',
        'catatan_verifikasi_penyuluh',
        'status_validasi_seksi',
        'catatan_validasi_seksi',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_id', 'laporan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class, 'riwayat_id', 'riwayat_id');
    }
}
