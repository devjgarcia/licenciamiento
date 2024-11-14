<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CorrsacaSm extends Model
{
    protected $table = 'corrsaca_sm';

    protected $fillable = [
        'codsm',
        'correo',
        'corrsaca_id',
        'estatus',
        'estatus_admin',
        'ult_proce',
        'henvio'
    ];

    public $timestamps = false;
}
