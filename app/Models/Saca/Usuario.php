<?php

namespace App\Models\Saca;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as HasAudit;

class Usuario extends Model implements Auditable
{
    use HasAudit;

    protected $primaryKey = 'co_usuario';

    protected $connection = 'conn_sm';

    protected $table = 'tg017_usuario';

    public $timestamps = false;

    protected $fillable = [
        'co_usuario',
        'co_empleado',
        'nb_usuario',
        'nb_nombres',
        'tx_mail',
        'tx_clave',
        'admin',
        'cod_per',
        'Prime_entrada'
    ];

    protected $appends = [
        'prime_entrada_text'
    ];

    /**
     * Get the status descrip.
     *
     * @return string
     */
    public function getPrimeEntradaTextAttribute()
    {
        return ($this->Prime_entrada == 1 ) ? 'Sí' : 'No';
    }

    public function empleado() : BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'co_empleado');
    }

    public function perfilUsu() : HasOne
    {
        return $this->hasOne(Perfil::class, 'cod_per', 'cod_per');
    }
}
