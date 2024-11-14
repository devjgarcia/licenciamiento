<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as HasAudit;

class NotiTipoPago extends Model implements Auditable
{
    use HasAudit;

    protected $table = 'np_tippago';

    protected $fillable = [
        'id',
        'nombre',
        'estatus'
    ];

    public $timestamps = false;
}
