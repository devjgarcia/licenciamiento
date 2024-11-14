<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Corrsaca extends Model
{
    //para gestionar tabla de Correos que se envian en Sacamovil
    protected $table = 'corrsaca';

    protected $fillable = [
        'descripcion',
        'estatus',
        'detalles'
    ];

    public $timestamps = false;
}
