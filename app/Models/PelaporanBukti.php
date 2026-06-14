<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelaporanBukti extends Model
{
    use HasFactory;

    protected $table = 'bukti_pelaporan';
    protected $primaryKey = 'bukti_id';
    public $incrementing = false;
    protected $keyType = 'string';

    const UPDATED_AT = null;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->bukti_id) {
                $last = static::orderBy('bukti_id', 'desc')->first();
                $lastNumber = $last ? (int) substr($last->bukti_id, 3) : 0;
                $model->bukti_id = 'BKT' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    protected $fillable = [
        'bukti_id',
        'laporan_id',
        'file_path',
        'keterangan',
    ];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_id', 'laporan_id');
    }
}
