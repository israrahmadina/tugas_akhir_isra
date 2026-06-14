<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'produk_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->produk_id) {
                $lastId = static::orderBy('produk_id', 'desc')->first();
                if (!$lastId) {
                    $model->produk_id = 'PRD001';
                } else {
                    $number = (int) substr($lastId->produk_id, 3);
                    $model->produk_id = 'PRD' . str_pad($number + 1, 3, '0', STR_PAD_LEFT);
                }
            }
        });
    }

    protected $fillable = [
        'produk_id',
        'kategori_id',
        'nama_produk',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class, 'kategori_id', 'kategori_id');
    }

    public function kelompokUsahas()
    {
        return $this->hasMany(KelompokUsaha::class, 'produk_id', 'produk_id');
    }
}
