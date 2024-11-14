<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaSaca extends Model
{
    protected $connection = 'conn_dinamica';
    
    protected $table = 'tg012_empresas';

    protected $primaryKey = 'co_empresas';

    protected $fillable = [
        'co_empresas',
        'nb_empresas',
    ];

    public $timestamps = false;
}
