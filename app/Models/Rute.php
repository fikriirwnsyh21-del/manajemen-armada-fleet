<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rute extends Model
{
    protected $table = 'rute';

    protected $fillable = [
        'asal',
        'tujuan',
        'jarak',
        'kendaraan_id',
        'supir_id',
    ];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }

    public function supir()
    {
        return $this->belongsTo(Supir::class, 'supir_id');
    }
}
