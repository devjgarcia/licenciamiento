<?php

namespace App\Alertas;

use App\Helper\CorreoAdministradores;
use App\Mail\AlertaTopeLicDisponibles as MailAlertaTopeLicDisponibles;
use Illuminate\Support\Facades\DB;
use App\Models\Parametros;
use Illuminate\Support\Facades\Mail;

final class AlertaTopeLicDisponibles
{
    public static function verificarTope()
    {
        $resp = DB::select('CALL ProceCountDispo(100)');
        $param = Parametros::where('nombre', 'TOPE_ALERTA_NODB')->first();

        if( $resp && $param && $resp[0]->LicDisponibles <= $param->valor ){
            //esta proximo a llegar a tope las DB disponibles
            $correos = Parametros::where('nombre', 'CORREOS_NOTI_PREV_VENCE')->first();

            if( !empty($correos->valor) ) {
                CorreoAdministradores::crearEnvio([
                    'sm'         => 'SM1',
                    'view'       => 'emails.alerta_tope_db',
                    'subject'    => 'Quedan Pocas Licencias de Saca',
                    'receptores' => $correos->valor,
                    'data_view'  => [
                        'disponibles' => $resp[0]->LicDisponibles,
                        'asignadas' => $resp[0]->LicTotales,
                    ]
                ]);
            }
        }
    }
}