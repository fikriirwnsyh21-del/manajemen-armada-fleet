<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    protected $table = 'kendaraan';

    protected $fillable = [
        'nopol',
        'merk',
        'tipe',
        'tahun',
        'status',
    ];

    public function rute()
    {
        return $this->hasMany(Rute::class, 'kendaraan_id');
    }

    public function perawatan()
    {
        return $this->hasMany(Perawatan::class, 'kendaraan_id');
    }

    public function bahanBakar()
    {
        return $this->hasMany(BahanBakar::class, 'kendaraan_id');
    }
}
