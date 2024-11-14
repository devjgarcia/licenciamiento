<?php

namespace App\Http\Controllers;

use App\Models\Parametros;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Enums\EstadoLicencia;
use App\Mail\AlertaPrevioVencimientoMail;
use App\Models\Licencias;
use Illuminate\Support\Facades\Mail;

class CorreoPorVencerController extends Controller
{
    public function enviarCorreoLicencias(Request $request)
    {
        $param = Parametros::where('nombre', 'DIAS_PREV_VENCE_ALERTA')->first();

        abort_unless($param, 404, 'No se ha definido el parametro de dias previos a vencimiento.');

        $fecha = Carbon::now();
        $fecha_desde = $fecha->addDay()->format('Y-m-d');
        $fecha_hasta = $fecha->addDays($param->valor)->format('Y-m-d');

        $licencias = Licencias::where('status', EstadoLicencia::ACTIVA)
                                ->whereBetween('vencimiento', [$fecha_desde, $fecha_hasta])
                                ->get();

        $correos_noti = Parametros::where('nombre', 'CORREOS_NOTI_PREV_VENCE')->first();

        abort_unless($correos_noti, 400,'No se han agregado correos receptores de esta alerta');

        $datos_correo = [
            'subject'     => 'Alerta de Licencias Saca próximas a vencer',
            'licencias'   => $licencias
        ];

        Mail::to( explode(',', $correos_noti->valor) )
            ->send( new AlertaPrevioVencimientoMail($datos_correo) );
    }
}
