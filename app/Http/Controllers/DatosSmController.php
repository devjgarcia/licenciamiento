<?php

namespace App\Http\Controllers;

use App\Enums\EstadoLicencia;
use App\Models\Licencias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

use App\Helper\ConexionDinamica;


class DatosSmController extends Controller
{
    //se obtienen datos de la sm que se solicite, solo para perfil Sistemas
    public function index( Request $request ) 
    {
        if( \request()->ajax() && $request->has('sm') ) {
            //hago la conexion a la base de datos consulto opciones
            //$lice = "SM{$sm}";
            //$lice = Licencias::where('codigo', $request->sm)->first();

            //$datos_conn = DB::select( "CALL `ActProtecSMKey`('{$request->sm}', '{$lice->correo}', 300)" );
            ConexionDinamica::cargarConnSm( $request->sm );

            //if( !empty($datos_conn[0]) ) {
                // Config::set([
                //     'database.connections.conn_sm.host'     => 'localhost',
                //     'database.connections.conn_sm.database' => $datos_conn[0]->webdb,
                //     'database.connections.conn_sm.username' => 'root',
                //     //'database.connections.conn_sm.username' => $datos_conn[0]->webuser,
                //     'database.connections.conn_sm.password' => '',
                //     //'database.connections.conn_sm.password' => $datos_conn[0]->webpass,
                // ]);
    
                //DB::purge('conn_sm');
                //DB::reconnect('conn_sm');

                //$usuarios = DB::connection('conn_sm')->select("SELECT * FROM tg017_usuario");

                return response()->json([
                    'view' => view('sm.menusm')->render(),
                ]);
            //}
        }

        $licencias = Licencias::where('status', EstadoLicencia::ACTIVA)
                                    ->whereRaw('vencimiento > NOW()')
                                    ->get();
        
        return view('sm.index', compact('licencias'));
    }
}
