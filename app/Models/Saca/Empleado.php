<?php

namespace App\Models\Saca;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $primaryKey = 'co_empleado';

    protected $connection = 'conn_sm';

    protected $table = 'tg016_empleado';

    protected $fillable = [
        'co_empleado',
        'nb_empleado',
        'co_turno',
        'co_empresas',
        'co_dpto',
        'co_cargo',
        'nu_cedula',
        'nu_telefono'
    ];

    protected $appends = [
        'tipo_empleado'
    ];

    /**
     * Get the status descrip.
     *
     * @return string
     */
    public function getTipoEmpleadoAttribute()
    {
        return ($this->tipemple == 1 ) ? 'Supervisor' : 'Empleado General';
    }

    public $timestamps = false;
}
