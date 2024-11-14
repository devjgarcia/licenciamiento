<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioSaca extends Model
{
    protected $primaryKey = 'co_usuario';

    protected $connection = 'conn_dinamica';

    protected $table = 'tg017_usuario';

    protected $fillable = [
        'co_empleado',
        'nb_usuario',
        'nb_nombres',
        'tx_mail',
        'tx_clave',
        'admin',
        'cod_per'
    ];

    public $timestamps = false;
}
