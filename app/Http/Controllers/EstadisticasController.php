<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstadisticasController extends Controller
{
    public function licenciasDisponibles()
    {
        return DB::select('CALL ProceCountDispo(100)');
    }
}
