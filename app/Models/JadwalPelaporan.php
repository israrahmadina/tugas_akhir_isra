<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPelaporan extends Model
{
    use HasFactory;

    protected $table = 'jadwal_pelaporan';
    protected $primaryKey = 'jadwal_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->jadwal_id) {
                $last = static::orderBy('jadwal_id', 'desc')->first();
                $lastNumber = $last ? (int) substr($last->jadwal_id, 3) : 0;
                $model->jadwal_id = 'JDL' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    protected $fillable = [
        'jadwal_id',
        'user_id_seksi',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function userSeksi()
    {
        return $this->belongsTo(User::class, 'user_id_seksi', 'user_id');
    }

    public function laporans()
    {
        return $this->hasMany(Laporan::class, 'jadwal_id', 'jadwal_id');
    }
}