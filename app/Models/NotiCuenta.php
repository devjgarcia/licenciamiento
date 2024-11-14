<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as HasAudit;

class NotiCuenta extends Model implements Auditable
{
    use HasAudit;

    protected $table = 'np_cuentas';

    protected $fillable = [
        'id',
        'codigo',
        'nombre',
        'tipo_moneda',
        'estatus'
    ];

    public $timestamps = false;

    public function notiMoneda() : HasOne
    {
        return $this->hasOne( NotiMoneda::class, 'codigo', 'tipo_moneda' );
    }
}
