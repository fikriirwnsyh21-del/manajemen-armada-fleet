<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perawatan extends Model
{
    protected $table = 'perawatan';

    protected $fillable = [
        'kendaraan_id',
        'tanggal',
        'jenis',
        'biaya',
    ];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }
}
