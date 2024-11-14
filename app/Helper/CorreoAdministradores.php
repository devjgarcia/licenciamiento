<?php 

namespace App\Helper;

use App\Models\Enviosm;
use App\Models\Parametros;
use Carbon\Carbon;

class CorreoAdministradores 
{
    public function __construct()
    {}

    public static function crearEnvio( $datos ) 
    {
        $receptores = Parametros::where('nombre', 'CORREOS_NOTIFICACION')
                        ->where('estatus', 1)
                        ->first();

        if( !empty($receptores->valor) ) {
            Enviosm::create([
                'codsm'      => $datos['sm'],
                'correolic'  => 'info@sacamovil.com',
                'receptores' => (!empty($datos['receptores'])) ? $datos['receptores'] : $receptores->valor,
                'cuerpo_msj' => view($datos['view'])->with($datos['data_view'])->render(),
                'asunto'     => $datos['subject'],
                'fechaenvio' => Carbon::yesterday(),
                'status'     => 0,
                'from_name'  => 'Saca Movil',
                'email_from' => 'jgarcia@sacamovil.com',
                'id_tn003'   => 0,
                'cod_gen'    => $datos['sm'].'-'.date('YmdHis').'-'.rand(100, 9999).'-'.rand(1,999),
            ]);
        }
    }
}