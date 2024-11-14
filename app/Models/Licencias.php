<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\EstadoLicencia;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as HasAudit;

class Licencias extends Model implements Auditable
{
    use HasAudit;

    protected $table = 'lice_sm';

    protected $fillable = [
        'id',
        'prefijo',
        'codigo',
        'frkcliente',
        'frkproducto',
        'frkcanal',
        'inicio',
        'vencimiento',
        'status',
        'creacion',
        'actualizacion',
        'empresa',
        'rsocial',
        'rif',
        'tipo_producto',
        'correo',
        'telefono',
        'codigo_pais',
        'nombre_pais'
    ];

    protected $guarded = [
        'id',
    ];

    public $timestamps = false;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'inicio' => 'date:d-m-Y',
        'actualizacion' => 'date:d-m-Y',
        'creacion' => 'date:d-m-Y',
    ];

    protected $appends = [
        'estado_licencia',
        'class_estado',
        'vencimiento_format',
        'span_servicorreo'
    ];

    /**
     * Get the status descrip.
     *
     * @return string
     */
    public function getEstadoLicenciaAttribute()
    {
        return EstadoLicencia::getEstado( $this->status );
    }

    /**
     * Get the status servicorreo descrip.
     *
     * @return string
     */
    public function getSpanServicorreoAttribute()
    {
        $text  = ( !empty($this->servicorreo) && $this->servicorreo == 1 ) ? 'Activo' : 'Inactivo';
        $class = ( !empty($this->servicorreo) && $this->servicorreo == 1 ) ? 'badge-success' : 'badge-secondary';

        return '<span class="badge '.$class.'">'.$text.'</span>';
    }

    /**
     * Get the vencimiento format d-m-Y.
     *
     * @return string
     */
    public function getVencimientoFormatAttribute()
    {
        return Carbon::parse($this->vencimiento)->format('d-m-Y');
    }

    public function getClassEstadoAttribute()
    {
        return EstadoLicencia::getClassEstado( $this->status );
    }

    public function renovacionSm() : HasOne 
    {
        return  $this->hasOne( RenovacionSm::class, 'codigo', 'codigo' );
    }

    public function renovacionSmDetalle() : HasMany 
    {
        return  $this->hasMany( RenovacionSmDetalle::class, 'codigo', 'codigo' );
    }
}
