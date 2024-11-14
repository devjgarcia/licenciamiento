<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerfilSaca extends Model
{
    protected $connection = 'conn_dinamica';
    
    protected $table = 'tblperfil';

    protected $primaryKey = 'cod_per';

    protected $fillable = [
        'cod_per',
        'desc_per',
        'estatus_per',
        'incide',
        'asistencia',
        'administracion',
        'tab_generales',
    ];

    public $timestamps = false;
}
