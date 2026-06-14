<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriProduk extends Model
{
    use HasFactory;

    protected $table = 'kategori_produk';
    protected $primaryKey = 'kategori_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->kategori_id) {
                $lastId = static::orderBy('kategori_id', 'desc')->first();
                if (!$lastId) {
                    $model->kategori_id = 'KTG001';
                } else {
                    $number = (int) substr($lastId->kategori_id, 3);
                    $model->kategori_id = 'KTG' . str_pad($number + 1, 3, '0', STR_PAD_LEFT);
                }
            }
        });
    }

    protected $fillable = [
        'kategori_id',
        'nama_kategori',
    ];

    public function produks()
    {
        return $this->hasMany(Produk::class, 'kategori_id', 'kategori_id');
    }
}
