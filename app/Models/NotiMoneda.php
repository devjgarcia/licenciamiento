<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as HasAudit;

class NotiMoneda extends Model implements Auditable
{
    use HasAudit;

    protected $table = 'np_tipmoneda';

    protected $fillable = [
        'id',
        'codigo',
        'nombre',
        'estatus'
    ];

    public $timestamps = false;
}
