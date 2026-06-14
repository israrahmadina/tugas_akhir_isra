<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lembaga extends Model
{
    use HasFactory;

    protected $table = 'lembaga';
    protected $primaryKey = 'lembaga_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->lembaga_id) {
                $lastId = static::orderBy('lembaga_id', 'desc')->first();
                if (!$lastId) {
                    $model->lembaga_id = 'LBG001';
                } else {
                    $number = (int) substr($lastId->lembaga_id, 3);
                    $model->lembaga_id = 'LBG' . str_pad($number + 1, 3, '0', STR_PAD_LEFT);
                }
            }
        });
    }

    protected $fillable = [
        'lembaga_id',
        'user_id',
        'nip_penyuluh',
        'nama_lembaga',
        'ketua',
        'kode_lembaga',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function penyuluh()
    {
        return $this->belongsTo(Penyuluh::class, 'nip_penyuluh', 'nip_penyuluh');
    }

    public function kelompokUsahas()
    {
        return $this->hasMany(KelompokUsaha::class, 'lembaga_id', 'lembaga_id');
    }
}
