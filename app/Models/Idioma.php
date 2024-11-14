<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\DB;

class Idioma extends Model
{
    use HasFactory;

    protected $table = 'tblidiosiste';

    protected $primaryKey = ['codidio', 'codsis'];

    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = [
        'codidio',
        'itemidioorigen',
        'idio_eng',
        'idio_fra',
        'idio_ara',
        'idio_por',
        'idio_zho',
        'idio_jpn',
        'idio_ita',
        'idio_deu',
        'idio_01',
        'idio_02',
        'ideo_03',
        'idio_padre',
        'observ_idio',
        'codsis',
    ];

}
