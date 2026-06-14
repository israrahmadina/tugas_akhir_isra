<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';

    const UPDATED_AT = 'updated_at';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->user_id) {
                $lastId = static::orderBy('user_id', 'desc')->first();
                if (!$lastId) {
                    $model->user_id = 'USR001';
                } else {
                    $number = (int) substr($lastId->user_id, 3);
                    $model->user_id = 'USR' . str_pad($number + 1, 3, '0', STR_PAD_LEFT);
                }
            }
        });
    }

    protected $fillable = [
        'user_id',
        'role_id',
        'nama',
        'email',
        'password',
        'jabatan',
        'contact_person',
        'foto_profile',
        'token_registrasi',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function penyuluh()
    {
        return $this->hasOne(Penyuluh::class, 'user_id', 'user_id');
    }

    public function lembaga()
    {
        return $this->hasOne(Lembaga::class, 'user_id', 'user_id');
    }

    public function kelompokUsaha()
    {
        return $this->hasOne(KelompokUsaha::class, 'user_id', 'user_id');
    }

    public function jadwalPelaporans()
    {
        return $this->hasMany(JadwalPelaporan::class, 'user_id_seksi', 'user_id');
    }

    public function riwayatVerifikasis()
    {
        return $this->hasMany(RiwayatVerifikasi::class, 'user_id', 'user_id');
    }

    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class, 'user_id', 'user_id');
    }
}