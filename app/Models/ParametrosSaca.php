<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParametrosSaca extends Model
{
    protected $connection = 'conn_dinamica';
    
    protected $table = 'param_sistema';

    protected $primaryKey = 'codigo';

    protected $fillable = [
        'id',
        'codigo',
        'nombre',
        'descripcion',
        'label',
        'tipo_dato',
        'valor',
        'estatus',
        'origen',
        'ayuda',
        'val_seleccion',
        'text_seleccion',
    ];

    public $timestamps = false;
}
