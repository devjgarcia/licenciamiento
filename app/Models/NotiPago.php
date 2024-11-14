<?php

namespace App\Models;

use App\Enums\EstadoNotiPago;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as HasAudit;

class NotiPago extends Model implements Auditable
{
    use HasAudit;

    protected $table = 'np_pagos';

    //protected $primaryKey = 'codnotipago';

    protected $fillable = [
        'id',
        'codnotipago',
        'sm_codigo',
        'correo_lice',
        'fecha_noti',
        'fecha_pago',
        'tipo_pago',
        'n_doc',
        'monto',
        'comprobante',
        'estatus',
        'coment_usu',
        'coment_admin',
        'np_cuentas',
        'usu_aprob',
        'fecha_aprob',
    ];

    public $timestamps = false;

    protected $appends = [
        'estado_pago',
        'class_estado',
        'fecha_noti_format',
        'monto_format',
    ];

    public function notiTipoPago() : BelongsTo
    {
        return $this->belongsTo( NotiTipoPago::class, 'tipo_pago', 'id' );
    }

    public function notiCuenta() : BelongsTo 
    {
        return $this->belongsTo( NotiCuenta::class, 'np_cuentas', 'codigo' )->with('notiMoneda');
    }

    /**
     * Get the estatus descrip.
     *
     * @return string
     */
    public function getEstadoPagoAttribute()
    {
        return EstadoNotiPago::getEstado( $this->estatus );
    }

    /**
     * Get the fecha_noti format d-m-Y H:M.
     *
     * @return Datetime
     */
    public function getFechaNotiFormatAttribute()
    {
        return Carbon::parse($this->fecha_noti)->format('d-m-Y H:i');
    }

    /**
     * Get the class bootstrap for estatus.
     *
     * @return string
     */
    public function getClassEstadoAttribute()
    {
        return EstadoNotiPago::getClassEstado( $this->estatus );
    }

    /**
     * Get the monto format 12.345,00.
     *
     * @return float
     */
    public function getMontoFormatAttribute()
    {
        return number_format($this->monto, 2, ',', '.');
    }
}
