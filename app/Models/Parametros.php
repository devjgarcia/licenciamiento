<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as HasAudit;

class Parametros extends Model implements Auditable
{
    use HasAudit;

    protected $table = 'param_sistema';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'valor',
        'estatus',
    ];
}
