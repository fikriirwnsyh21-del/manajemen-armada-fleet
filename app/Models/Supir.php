<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supir extends Model
{
    protected $table = 'supir';

    protected $fillable = [
        'nama',
        'sim',
        'no_hp',
        'status',
    ];

    public function rute()
    {
        return $this->hasMany(Rute::class, 'supir_id');
    }
}
