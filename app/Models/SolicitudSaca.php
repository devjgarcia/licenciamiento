<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as HasAudit;

class SolicitudSaca extends Model implements Auditable
{
    use HasAudit;

    protected $connection = 'mysql';

    protected $table = 'soli_saca';

    protected $primaryKey = 'idsoli';

    protected $fillable = [
        'nombres',
        'empresa',
        'correo',
        'telefono',
        'pais',
        'code_pais',
        'token',
        'completado',
    ];

    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
    ];

    public function licen() : HasOne
    {
        return $this->hasOne( Licencias::class, 'correo', 'correo' );
    }
}
