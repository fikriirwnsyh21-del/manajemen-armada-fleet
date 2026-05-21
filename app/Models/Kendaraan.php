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
}
