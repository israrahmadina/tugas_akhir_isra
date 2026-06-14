<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skema extends Model
{
    use HasFactory;

    protected $table = 'skema';
    protected $primaryKey = 'skema_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->skema_id) {
                $lastId = static::orderBy('skema_id', 'desc')->first();
                if (!$lastId) {
                    $model->skema_id = 'SKM001';
                } else {
                    $number = (int) substr($lastId->skema_id, 3);
                    $model->skema_id = 'SKM' . str_pad($number + 1, 3, '0', STR_PAD_LEFT);
                }
            }
        });
    }

    protected $fillable = [
        'skema_id',
        'nama_skema',
        'jenis_kelompok_binaan',
    ];

    public function kelompokUsahas()
    {
        return $this->hasMany(KelompokUsaha::class, 'skema_id', 'skema_id');
    }
}
