<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';
    protected $primaryKey = 'notifikasi_id';
    public $incrementing = false;
    protected $keyType = 'string';

    const UPDATED_AT = null;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->notifikasi_id) {
                $last = static::orderBy('notifikasi_id', 'desc')->first();
                $lastNumber = $last ? (int) substr($last->notifikasi_id, 3) : 0;
                $model->notifikasi_id = 'NTF' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    protected $fillable = [
        'notifikasi_id',
        'user_id',
        'riwayat_id',
        'pesan',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function riwayatVerifikasi()
    {
        return $this->belongsTo(RiwayatVerifikasi::class, 'riwayat_id', 'riwayat_id');
    }
}
