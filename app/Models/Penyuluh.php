<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyuluh extends Model
{
    use HasFactory;

    protected $table = 'penyuluh';
    protected $primaryKey = 'nip_penyuluh';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->nip_penyuluh) {
                $lastId = static::orderBy('nip_penyuluh', 'desc')->first();
                if (!$lastId) {
                    $model->nip_penyuluh = 'PNY001';
                } else {
                    $number = (int) substr($lastId->nip_penyuluh, 3);
                    $model->nip_penyuluh = 'PNY' . str_pad($number + 1, 3, '0', STR_PAD_LEFT);
                }
            }
        });
    }

    const UPDATED_AT = null;

    protected $fillable = [
        'nip_penyuluh',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function lembagas()
    {
        return $this->hasMany(Lembaga::class, 'nip_penyuluh', 'nip_penyuluh');
    }

    public function kelompokUsahas()
    {
        return $this->hasMany(KelompokUsaha::class, 'nip_penyuluh', 'nip_penyuluh');
    }
}
