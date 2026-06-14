<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelompokUsaha extends Model
{
    use HasFactory;

    protected $table = 'kelompok_usaha';
    protected $primaryKey = 'kelompok_id';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'update_at';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->kelompok_id) {
                $lastId = static::orderBy('kelompok_id', 'desc')->first();
                if (!$lastId) {
                    $model->kelompok_id = 'KLU001';
                } else {
                    $number = (int) substr($lastId->kelompok_id, 3);
                    $model->kelompok_id = 'KLU' . str_pad($number + 1, 3, '0', STR_PAD_LEFT);
                }
            }
        });
    }

    protected $fillable = [
        'kelompok_id',
        'user_id',
        'nip_penyuluh',
        'lembaga_id',
        'skema_id',
        'produk_id',
        'nama_usaha',
        'legalitas_perizinan',
        'deskripsi',
        'alamat_lengkap',
        'foto_produk',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function penyuluh()
    {
        return $this->belongsTo(Penyuluh::class, 'nip_penyuluh', 'nip_penyuluh');
    }

    public function lembaga()
    {
        return $this->belongsTo(Lembaga::class, 'lembaga_id', 'lembaga_id');
    }

    public function skema()
    {
        return $this->belongsTo(Skema::class, 'skema_id', 'skema_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'produk_id');
    }

    public function laporans()
    {
        return $this->hasMany(Laporan::class, 'kelompok_id', 'kelompok_id');
    }
}
