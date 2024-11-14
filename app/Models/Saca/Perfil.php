<?php

namespace App\Models\Saca;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as HasAudit;

class Perfil extends Model implements Auditable
{
    use HasAudit;

    protected $primaryKey = 'cod_per';

    protected $connection = 'conn_sm';

    protected $table = 'tblperfil';

    public $timestamps = false;

    protected $fillable = [
        'cod_per',
        'desc_per',
        'estatus_per',
        'incide',
        'asistencia',
        'administracion',
        'tab_generales',
    ];
}
