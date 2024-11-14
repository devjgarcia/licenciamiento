<?php

namespace App\Http\Controllers;

use App\Alertas\AlertaTopeLicDisponibles;
use App\Enums\EstadoLicencia;
use Illuminate\Http\Request;
use App\Models\Licencias;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $activas  = Licencias::where('status', EstadoLicencia::ACTIVA)->count();
        $demo     = Licencias::where('status', EstadoLicencia::DEMO)->count();
        $vencidas = Licencias::where('status', EstadoLicencia::VENCIDA)->count();


        AlertaTopeLicDisponibles::verificarTope();

        return view('home', compact('activas', 'demo', 'vencidas'));
    }
}
