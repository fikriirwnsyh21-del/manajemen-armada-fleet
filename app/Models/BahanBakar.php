<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BahanBakar extends Model
{
    protected $table = 'bahan_bakar';

    protected $fillable = [
        'kendaraan_id',
        'tanggal',
        'liter',
        'biaya',
    ];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }
}
