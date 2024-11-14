<?php 

namespace App\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class ConexionDinamica 
{
    public static function cargarConexion( $sm, $correo )
    {
        //$hora = date("H.i.s");
        /*$lice = DB::table('lice_sm')
                        ->where('codigo', $sm)
                        ->where('correo', $correo)
                        ->first();*/

        $datos_conn = DB::select("CALL ActProtecSMKey('{$sm}', '{$correo}', 300)");
        
        //print_r( $datos_conn );
        //return $datos_conn;
        
        /*Config::set([
            'database.connections.conn_dinamica.host'     => $lice->webserver,
            'database.connections.conn_dinamica.database' => $lice->webdb,
            'database.connections.conn_dinamica.username' => $lice->webuser,
            'database.connections.conn_dinamica.password' => $lice->webpass,
        ]);*/
    }

    public static function cargarConnSm( $sm )
    {
        $lice = DB::table('lice_sm')
                        ->where('codigo', $sm)
                        ->first();

        $datos_conn = DB::select("CALL ActProtecSMKey('{$lice->codigo}', '{$lice->correo}', 300)");

        //Cache::flush();
        //Artisan::call('config:clear');
        
        Config::set([
            'database.connections.conn_sm.host'     => $datos_conn[0]->webserver,
            'database.connections.conn_sm.database' => $datos_conn[0]->webdb,
            'database.connections.conn_sm.username' => $datos_conn[0]->webuser,
            'database.connections.conn_sm.password' => $datos_conn[0]->webpass,
        ]);

        //DB::purge('conn_sm');
        DB::reconnect('conn_sm');
    }
}