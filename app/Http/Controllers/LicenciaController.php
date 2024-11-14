<?php

namespace App\Http\Controllers;

use App\Models\Licencias;
use Illuminate\Http\Request;

use App\Enums\EstadoLicencia;
use App\Enums\TipoProducto;
use App\Helper\CorreoAdministradores;
use App\Mail\ActivacionLicenciaMail;
use App\Mail\ActualizacionLicenciaMail;
use App\Models\Parametros;
use Carbon\Carbon;
use App\Models\UsuarioSaca;

use App\Mail\TestMail;
use App\Models\EmpresaSaca;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Facades\Mail;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class LicenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        $vencimiento = 0;
        //status por defecto para consulta
        $estado = EstadoLicencia::ACTIVA; 

        if( $request->has('estado') ){
            //en caso de que se solicite un status especifico
            $estado = $request->estado;
        }

        if( $request->has('vencimiento_proximo') ){
            //Consulta de Licencias proximas a vencer
            $parametro = Parametros::where('nombre', 'DIAS_PREVIO_VENCIMIENTO')->first();

            $fecha = Carbon::now();

            $fecha_desde = $fecha->addDay()->format('Y-m-d');
            $fecha_hasta = $fecha->addDays($parametro->valor ?? 30)->format('Y-m-d');

            $vencimiento = 1;

            $licencias = Licencias::where('status', EstadoLicencia::ACTIVA)
                                    ->whereBetween('vencimiento', [$fecha_desde, $fecha_hasta])
                                    ->get();
        }
        else{            
            $licencias = Licencias::where('status', $estado)->get();
        }

        if( $request->has('is_ajax') && $request->is_ajax == 1 ){
            return response()->json(['licencias' => $licencias]);
        }
        else{
            return view('licencias.index', compact('licencias', 'estado', 'vencimiento'));
        }
    }

    /**
     * Display a listing of the Licencias to activate.
     *
     * @return \Illuminate\Http\Response
     */
    public function activacion( Request $request )
    {
        $vencimiento = 0;

        $estado = EstadoLicencia::DEMO;
        //$licencias = Licencias::where('status', $estado)
        //                        ->get();

        $licencias = DB::table('lice_sm')
                        ->select([
                            'lice_sm.*',
                            'tbl_actlicparam.*',
                            'tbl_actlicparam_det.*',
                            'np_pagos.*',
                            'np_cuentas.nombre AS nombre_cuenta',
                            'np_cuentas.tipo_moneda AS codigo_moneda',
                            'np_tippago.nombre AS tippago_nombre',
                        ])
                        ->join('tbl_actlicparam', 'lice_sm.codigo', '=', 'tbl_actlicparam.codigo')
                        ->join('tbl_actlicparam_det', 'lice_sm.codigo', '=', 'tbl_actlicparam_det.codigo')
                        ->leftJoin('np_pagos', 'tbl_actlicparam_det.codnotipago', '=', 'np_pagos.codnotipago')
                        ->leftJoin('np_cuentas', 'np_pagos.np_cuentas', '=', 'np_cuentas.codigo')
                        ->leftJoin('np_tippago', 'np_pagos.tipo_pago', '=', 'np_tippago.id')
                        ->where('lice_sm.status', '=', 2)
                        ->where('tbl_actlicparam_det.tiporeg', '=', 'CO')
                        ->where('tbl_actlicparam.totempxact', '>', 0)
                        ->whereNotNull('tbl_actlicparam_det.codnotipago')
                        ->orderBy('tbl_actlicparam.fechcompra', 'desc')
                        ->get();

        return view('licencias.index', compact('licencias', 'estado', 'vencimiento'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $datos_pais     = $this->getDatosPais( $request );
        $tipos_producto = json_encode(TipoProducto::getTiposProducto());
        return view('licencias.create', ['datos_pais' => $datos_pais, 'tipos_producto' => $tipos_producto]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //se guardan las licencias creadas desde el perfil Sistemas
        $solicitud = $request->validate([
            'nombres'        => 'required|string',
            'empresa'        => 'required|string',
            'correo'         => 'required|email|unique:lice_sm,correo',
            'telefono'       => 'required|string',
            'pais'           => 'required|string',
            'code_pais'      => 'required|string',
            'profitserver'   => 'nullable|string',
            'profituser'     => 'nullable|string',
            'profitpass'     => 'nullable|string',
            'profitdb'       => 'nullable|string',
            'estatus'        => 'nullable|integer',
            'tipo_producto'  => 'nullable|string',
        ]);

        try {
            //busco un sm disponible para asignarlo como Demo
            $proximo_sm = DB::select('SELECT `fun_corsm`() AS sm');
            $sm = $proximo_sm[0]->sm ?? false;

            //en caso de que no encuentre un sm disponible
            abort_if(! $sm, 422, 'No hay licencias para asignación, contacte a soporte');

            //se activa la licencia como Demo
            DB::select("CALL RegLiceSMProce( '{$solicitud['empresa']}', '{$solicitud['correo']}', '{$solicitud['telefono']}', '{$solicitud['code_pais']}', '{$solicitud['pais']}', {$sm})");

            $lice = "SM{$sm}";
            $datos_conn = DB::select( "CALL `ActProtecSMKey`('{$lice}', '{$solicitud['correo']}', 300)" );

            if( !empty($solicitud['profitserver']) ){
                //Desencriptamos el sm para actualizar los datos
                $des_sm = DB::select( "CALL `ProtecSMKeyAll`(2085, '{$lice}')" );

                $licencia = Licencias::where('codigo', $lice)->first();

                $licencia->profitserver = $solicitud['profitserver'] ?? '';
                $licencia->profitdb     = $solicitud['profitdb'] ?? '';
                $licencia->profituser   = $solicitud['profituser'] ?? '';
                $licencia->profitpass   = $solicitud['profitpass'] ?? '';
                $licencia->save();

                //encripto nuevamente la licencia
                $enc_sm = DB::select( "CALL `ProtecSMKeyAll`(1085, '{$lice}')" );
            }

            if( !empty($solicitud['estatus']) && $solicitud['estatus'] == EstadoLicencia::ACTIVA ){

                $parametro   = Parametros::where('nombre', 'DIAS_ACTIVA')->first();
                $fecha_vence = Carbon::now()->addDays( ($parametro->valor ?? 365) )->format('Y-m-d');

                DB::table('lice_sm')
                    ->where('codigo', $lice)
                    ->where('correo', $solicitud['correo'])
                    ->where('frkcliente', $solicitud['empresa'])
                    ->update(['status' => $solicitud['estatus'], 'vencimiento' => $fecha_vence]);
            }

            if( !empty($solicitud['tipo_producto']) ){

                DB::table('lice_sm')
                    ->where('codigo', $lice)
                    ->where('correo', $solicitud['correo'])
                    ->where('frkcliente', $solicitud['empresa'])
                    ->update(['frkproducto' => $solicitud['tipo_producto']]);
            }
            
            Config::set([
                'database.connections.conn_dinamica.host'     => 'localhost',
                'database.connections.conn_dinamica.database' => $datos_conn[0]->webdb,
                'database.connections.conn_dinamica.username' => $datos_conn[0]->webuser,
                'database.connections.conn_dinamica.password' => $datos_conn[0]->webpass,
            ]);

            //DB::purge('conn_dinamica');
            DB::reconnect('conn_dinamica');

            $tx_clave = 'Ab123456';

            $usuario = new UsuarioSaca();
            $usuario->co_empleado = 'demo';
            $usuario->nb_usuario  = $solicitud['correo'];
            $usuario->nb_nombres  = $solicitud['nombres'];
            $usuario->tx_mail     = $solicitud['correo'];
            $usuario->tx_clave    = sha1(md5($tx_clave));
            $usuario->admin       = 1;
            $usuario->cod_per     = 'demo';
            $usuario->save();

            $empresa = EmpresaSaca::firstOrCreate(['co_empresas' => 'EP001']);
            $empresa->nb_empresas = $solicitud['empresa'];
            $empresa->save();

            //crear correo de notificacion 
            $this->armarCorreo([
                'codigo'  => $lice,
                'subject' => 'Creación de Licencia Saca',
                'tipo'    => 'create_lice',
                'correo'  => $solicitud['correo'], 
                'empresa' => $solicitud['empresa'], 
                'view'    => 'emails.creacion_licencia'
            ]);
            

            if( !empty($solicitud['estatus']) ){
                $mensaje = "Licencia ha sido creada. Código: {$lice}. Se ha guardado con Estatus: ". EstadoLicencia::getEstado($solicitud['estatus']);
            }
            else{
                $mensaje = "Licencia ha sido creada. Código: {$lice}.";
            }

            return response()->json(['message' => $mensaje, 'lice' => $lice]);
        }
        catch( Exception $e ) {
            return $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $datos = $request->validate([
            'id'           => 'required|integer',
            'codigo'       => 'required|string',
            'profitserver' => 'nullable|string',
            'profitdb'     => 'nullable|string',
            'profituser'   => 'nullable|string',
            'profitpass'   => 'nullable|string',
        ]);

        //Desencriptamos el sm para actualizar los datos
        $des_sm = DB::select( "CALL `ProtecSMKeyAll`(2085, '{$datos['codigo']}')" );

        $licencia = Licencias::find($id);

        $licencia->profitserver = $datos['profitserver'] ?? '';
        $licencia->profitdb     = $datos['profitdb'] ?? '';
        $licencia->profituser   = $datos['profituser'] ?? '';
        $licencia->profitpass   = $datos['profitpass'] ?? '';
        $licencia->save();

        //encripto nuevamente la licencia
        $enc_sm = DB::select( "CALL `ProtecSMKeyAll`(1085, '{$datos['codigo']}')" );

        return response()->json(['licencia' => $licencia]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function getLice( Request $request )
    {
        $licencias = Licencias::where('status', EstadoLicencia::DEMO)->get();

        return response()->json(['licencias' => $licencias]);
    }

    public function getLiceXActivar( Request $request )
    {
        //TODO: se obtienen las licencias que tienen solicitud de renovacion
        $datos = $request->validate([
            'tipo_act' => 'required|integer|in:1,2'
        ]);

        $licencias = DB::table('lice_sm')
                        ->select([
                            '*'
                        ]);
        
        if( $datos['tipo_act'] == 1 ){
            //licencias con solicitud de activacion o compra
            $licencias = DB::table('lice_sm')
                        ->select([
                            'lice_sm.*',
                            'tbl_actlicparam.*',
                            'tbl_actlicparam_det.*',
                            'np_pagos.*',
                            'np_pagos.estatus AS estatus_pago',
                            'np_cuentas.nombre AS nombre_cuenta',
                            'np_cuentas.tipo_moneda AS codigo_moneda',
                            'np_tippago.nombre AS tippago_nombre',
                        ])
                        ->join('tbl_actlicparam', 'lice_sm.codigo', '=', 'tbl_actlicparam.codigo')
                        ->join('tbl_actlicparam_det', 'lice_sm.codigo', '=', 'tbl_actlicparam_det.codigo')
                        ->leftJoin('np_pagos', 'tbl_actlicparam_det.codnotipago', '=', 'np_pagos.codnotipago')
                        ->leftJoin('np_cuentas', 'np_pagos.np_cuentas', '=', 'np_cuentas.codigo')
                        ->leftJoin('np_tippago', 'np_pagos.tipo_pago', '=', 'np_tippago.id')
                        ->where('lice_sm.status', '=', 2)
                        ->where('tbl_actlicparam_det.tiporeg', '=', 'CO')
                        ->whereNotNull('tbl_actlicparam_det.codnotipago')
                        ->where('tbl_actlicparam.totempxact', '>', 0)
                        ->orderBy('tbl_actlicparam.fechcompra', 'desc')
                        ->get();
        }
        else if( $datos['tipo_act'] == 2 ){
            //licencias con solicitud de recarga de empleados
            $licencias = DB::table('lice_sm')
                            ->select([
                                'lice_sm.*',
                                'tbl_actlicparam.*',
                                'tbl_actlicparam_det.*',
                                'np_pagos.*',
                                'np_pagos.estatus AS estatus_pago',
                                'np_cuentas.nombre AS nombre_cuenta',
                                'np_cuentas.tipo_moneda AS codigo_moneda',
                                'np_tippago.nombre AS tippago_nombre',
                            ]) 
                        ->join('tbl_actlicparam', 'lice_sm.codigo', '=', 'tbl_actlicparam.codigo')
                        ->join('tbl_actlicparam_det', 'lice_sm.codigo', '=', 'tbl_actlicparam_det.codigo')
                        ->leftJoin('np_pagos', 'tbl_actlicparam_det.codnotipago', '=', 'np_pagos.codnotipago')
                        ->leftJoin('np_cuentas', 'np_pagos.np_cuentas', '=', 'np_cuentas.codigo')
                        ->leftJoin('np_tippago', 'np_pagos.tipo_pago', '=', 'np_tippago.id')
                        ->where('tbl_actlicparam_det.tiporeg', '=', 'RT')
                        ->whereNotNull('tbl_actlicparam_det.codnotipago')
                        ->orderBy('tbl_actlicparam_det.fechaproce', 'desc')
                        ->get();
        }
        else{}
                        

        return response()->json(['licencias' => $licencias]);
    }

    /**
     * Activate Licencias with days of parameter.
     *
     * @return \Illuminate\Http\Response
     */
    public function activateLicen( Request $request )
    {
        //Activate Licencias
        $datos = $request->validate([
            'licencias'   => 'required|array',
            'tipo_activa' => 'required|integer|in:1,2',
            'estado'      => 'nullable|integer',
        ]);
    
        
        foreach( $datos['licencias'] as $lice ) {
            //validar que el vencimiento no haya pasado

            if( $datos['tipo_activa'] == 1 ){
                $tipo_op = 'CO';
            }
            else{
                $tipo_op = 'RE';
            }

            $sql = "CALL Actlicesmxemple( '{$lice['codigo']}', 
                                        CURDATE(), 
                                        '0.00',
                                        '0',
                                        '{$lice['correo']}',
                                        '2',
                                        '{$tipo_op}'
                                    );";
            
            $resp = DB::select( $sql );
        }

        //enviar correo notificando
        CorreoAdministradores::crearEnvio([
            'sm'        => 'SM1',
            'view'      => 'emails.activacion_licencia',
            'subject'   => ((!empty($datos['estado']) && $datos['estado']==3) ? 'Renovación de Licencia Saca' : 'Activación de Licencias Saca'),
            'data_view' => [
                'licencias'    => $datos['licencias'],
                'reactivacion' => (!empty($datos['estado']) && $datos['estado'] == 3) ? true : false,
                'usuario'      => Auth::user()->name . ' (' . Auth::user()->email . ')',
                'fecha'        => Carbon::now()->format('d-m-Y h:i a'),
            ]
        ]);

        return response()->json(['licencias' => $datos['licencias']]);
    }

    public function extenderVencimiento( Request $request )
    {
        $validar = $request->validate([
            'codigo'        => 'required|string|exists:'. Licencias::class .',codigo',
            'vencimiento'   => 'required|date',
            'estado'        => 'required|integer',
            'observacion'   => 'required|string',
        ]);

        $licencia = Licencias::where('codigo', $validar['codigo'])->first();
        $vencimiento_old = Carbon::parse($licencia->vencimiento);
        $vencimiento_new = Carbon::parse($validar['vencimiento']);

        $licencia->vencimiento = $validar['vencimiento'];
        
        if( $validar['estado'] == 1 ){
            $licencia->Observ_admin = $validar['observacion'];
            $tipo_extend = 'Extensión de Vencimiento (Licencia Activa)';
        }

        if( $validar['estado'] == 2 ){
            $licencia->Observ_ampdemo = $validar['observacion'];
            $tipo_extend = 'Extensión de Demo';

        }

        $licencia->save();

        $datos_correo = [
            'subject'     => 'Actualización de Vencimiento de Licencia',
            'tipo'        => 'extend_vencimiento',
            'codigo'      => $licencia->codigo,
            'correo'      => $licencia->correo,
            'empresa'     => $licencia->empresa,
            'observacion' => $validar['observacion'],
            'vencimiento' => Carbon::parse( $licencia->vencimiento )->format('d-m-Y'),
            'dias_extend' => $vencimiento_old->diff( $vencimiento_new )->days,
            'tipo_extend' => $tipo_extend,
            'usuario'     => Auth::user()->name . ' (' . Auth::user()->email . ')',
            'fecha'       => Carbon::now()->format('d-m-Y h:i a')
        ];

        //enviar correo notificando
        CorreoAdministradores::crearEnvio([
            'sm'        => 'SM1',
            'view'      => 'emails.actualizacion_licencia',
            'subject'   => 'Actualización de Vencimiento de Licencia',
            'data_view' => [
                'datos' => $datos_correo,
            ]
        ]);

        return response()->json(['licencia' => $licencia]);
    }

    public function getLiceAct( Request $request )
    {
        abort_unless( $request->has('sm'), 403, 'Error en la peticion' );

        //Desencriptamos el sm para actualizar los datos
        DB::select( "CALL `ProtecSMKeyAll`(2085, '{$request->sm}')" );

        $datos_lice = Licencias::where('codigo', $request->sm)->first();

        //Encriptamos nuevamente el sm
        DB::select( "CALL `ProtecSMKeyAll`(1085, '{$request->sm}')" );

        return response()->json( $datos_lice );
    }

    protected function getDatosPais( Request $request, $only_country = false )
    {
        $user_ip = $request->ip();
        //$user_ip = '162.241.188.252';

        $url = "http://ipinfo.io/".$user_ip;
        $ip_info = json_decode(file_get_contents($url));
        
        if( $only_country ){
            return ['pais' => $ip_info->city.' '.$ip_info->region, 'code_pais' => $ip_info->country ];
        }

        return json_encode($ip_info);
    }

    protected function armarCorreo( $datos )
    {
        $datos_correo = [
            'subject'     => $datos['subject'],
            'tipo'        => $datos['tipo'],
            'codigo'      => $datos['codigo'],
            'correo'      => $datos['correo'],
            'empresa'     => $datos['empresa'],
            'observacion' => $datos['observacion'] ?? null,
            'vencimiento' => ($datos['vencimiento']) ? Carbon::parse( $datos['vencimiento'] )->format('d-m-Y') : null,
            'dias_extend' => $datos['dias_extend'] ?? null,
            'tipo_extend' => $datos['tipo_extend'] ?? null ,
            'usuario'     => Auth::user()->name . ' (' . Auth::user()->email . ')',
            'fecha'       => Carbon::now()->format('d-m-Y h:i a')
        ];

        //enviar correo notificando
        CorreoAdministradores::crearEnvio([
            'sm'        => 'SM1',
            'view'      => $datos['view'],
            'subject'   => $datos['subject'],
            'data_view' => [
                'datos' => $datos_correo,
            ]
        ]);
    }
}
