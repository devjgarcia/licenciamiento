<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudRecarga extends Model
{
    use HasFactory;

    protected $table = 'tbl_actlicparam_det';

    protected $fillable = [
        'codigo',
        'correo',
        'fechaproce',
        'costolicxemple',
        'totemple',
        'totalapagar',
        'tiporeg',
        'fecvenlicact',
        'item',
        'codnotipago',
    ];

    public $timestamps = false;
}
