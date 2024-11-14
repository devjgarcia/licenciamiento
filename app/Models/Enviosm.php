<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enviosm extends Model
{
    use HasFactory;

    protected $table = 'tbl_enviossm';

    protected $fillable = [
        'codsm',
        'correolic',
        'receptores',
        'cuerpo_msj',
        'asunto',
        'fechaenvio',
        'status',
        'from_name',
        'email_from',
        'id_tn003',
        'cod_gen',
    ];

    protected $guarded = [
        'cod_gen',
    ];

    public $timestamps = false;

}
