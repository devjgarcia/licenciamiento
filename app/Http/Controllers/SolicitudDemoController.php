<?php

namespace App\Http\Controllers;

use App\Alertas\AlertaTopeLicDisponibles;
use App\Enums\ZonaHoraria;
use App\Helper\CorreoAdministradores;
use Illuminate\Http\Request;

use App\Models\SolicitudSaca;
use App\Models\UsuarioSaca;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

use App\Models\CorreSm;
use App\Models\EmpresaSaca;
use App\Models\Enviosm;
use App\Models\Parametros;
use App\Models\ParametrosSaca;
use App\Models\PerfilSaca;
use Carbon\Carbon;
use App\Seguridad\GeneraPassword;

class SolicitudDemoController extends Controller
{
    public function __construct()
    {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        $datos_pais = $this->getDatosPais( $request );
        return view('solicitud_demo.index', compact('datos_pais'));
    }

    public function select( Request $request )
    {
        $listado = SolicitudSaca::with(['licen'])->orderBy('created_at', 'asc')->get();

        return response()->json(['soli_saca' => $listado]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //reglas de validacion
        $validar = $request->validate([
            'nombres'  => 'required|string|max:60',
            'empresa'  => 'required|string|max:60',
            'correo'   => 'required|email|unique:soli_saca,correo',
            'telefono' => 'required|string',
        ]);

        try {

            //busco si existe una demo ya creada con este correo
            $buscarCorreo = CorreSm::where('correo', $validar['correo'])->first();

            if( !$buscarCorreo ) {

                $datos_pais = $this->getDatosPais( $request, true ); 

                //se busca en caso de existir y no haberse enviado el correo, caso contrario lo crea
                //$solicitud = SolicitudSaca::first(['correo' => $validar['correo'], 'correo_env' => 0]);
                $solicitud = new SolicitudSaca();
                $solicitud->nombres    = $validar['nombres'];
                $solicitud->empresa    = $validar['empresa'];
                $solicitud->correo     = $validar['correo'];
                $solicitud->telefono   = $validar['telefono'];
                $solicitud->pais       = $datos_pais['pais'] ?? NULL;
                $solicitud->code_pais  = $datos_pais['code_pais'] ?? NULL;
                $solicitud->correo_env = 0;
                $solicitud->token      = base64_encode($validar['correo'].'_'.$datos_pais['pais'].'_'.$datos_pais['code_pais']);
                $solicitud->completado = 0;
                $resp = $solicitud->save();

                //$resp_correo = Mail::to([$validar['correo']])
                //                ->send( new SolicitudDemoMail( $solicitud ) );
                //$resp_correo = Mail::to(['develop.juanc@gmail.com'])

                $resp_correo = Enviosm::create([
                    'codsm'      => 'SM000',
                    'correolic'  => 'info@sacamovil.com',
                    'receptores' => $validar['correo'],
                    'cuerpo_msj' => view('emails.solicitud_demo')->with([ 'datos' => $solicitud ])->render(),
                    'asunto'     => 'Solicitud Demo Saca Móvil',
                    'fechaenvio' => Carbon::yesterday(),
                    'status'     => 0,
                    'from_name'  => 'Saca Movil',
                    'email_from' => 'jgarcia@sacamovil.com',
                    'id_tn003'   => 0,
                    'cod_gen'    => 'SM000-'.date('YmdHis').'-'.rand(100, 9999).'-'.rand(1,999),
                ]);
                                    

                $solicitud->correo_env = 1;
                $solicitud->save();
                
                //AlertaTopeLicDisponibles::verificarTope();

                return response()->json([
                    'code'    => 200,
                    'message' => 'Solicitud creada de manera correcta, verifique su correo electrónico para continuar con el proceso.'
                ]);
            }
            else{
                return response()->json([
                    'code'    => 500,
                    'message' => 'Este correo electrónico ya ha sido usado previamente'
                ]);
            }
        }
        catch( Exception $e ){
            
            //report($e);
            //return $e;
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
        //
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

    protected function getDatosPais( Request $request, $only_country = false )
    {
        $user_ip = ($request->ip() != '127.0.0.1') ? $request->ip() : '162.241.188.252';
        //$user_ip = '162.241.188.252';

        //echo $user_ip;

        
        if( $request->ip() !== '127.0.0.1' ) {

            $url = "http://ipinfo.io/".$user_ip;
            $ip_info = json_decode(file_get_contents($url));
            
            $response = json_decode(json_encode($ip_info), true);
        }
        else{
            $response = ['city' => 'Caracas', 'region' => 'Venezuela', 'code_pais' => 'VE', 'country' => 'VE'];
        }

        if( $only_country ){
            return ['pais' => $response['city'].' '.$response['region'], 'code_pais' => $response['country'] ];
        }

        return json_encode($response);
    }

    public function activation( Request $request )
    {
        $validar = $request->validate([
            's' => 'required|integer',
            'token' => 'required|string'
        ]);

        $solicitud = SolicitudSaca::where('idsoli', $validar['s'])
                                ->where('token', $validar['token'])
                                ->where('completado', 0)
                                ->first();

        abort_if(!$solicitud, 403, 'Url ha expirado o es posible que haya procesado su solicitud previamente.');

        $solicitud->completado = 2;
        $solicitud->save();
        
        try {
            //busco que el correo no se encuentre ya procesado
            $buscarCorreo = CorreSm::where('correo', $solicitud->correo)->first();

            if( !$buscarCorreo ) {

                //busco un sm disponible para asignarlo como Demo
                $proximo_sm = DB::select('SELECT `fun_corsm`() AS sm');
                //en caso de que no encuentre un sm disponible
                abort_unless($proximo_sm, 403, 'No hay licencias para asignación, contacte a soporte');

                $sm = $proximo_sm[0]->sm;
                //se activa la licencia como Demo
                DB::select("CALL RegLiceSMProce( '{$solicitud->empresa}', '{$solicitud->correo}', '{$solicitud->telefono}', '{$solicitud->codigo_pais}', '{$solicitud->pais}', {$sm})");

                $lice = "SM{$sm}";
                //cambiar codigo a 300 cuando se suba al server
                $datos_conn = DB::select( "CALL `ActProtecSMKey`('{$lice}', '{$solicitud->correo}', 300)" );
                
                //setea valores del .env para la conexion dinamica
                Config::set([
                    'database.connections.conn_dinamica.host'     => 'localhost',
                    'database.connections.conn_dinamica.database' => $datos_conn[0]->webdb,
                    'database.connections.conn_dinamica.username' => $datos_conn[0]->webuser,
                    'database.connections.conn_dinamica.password' => $datos_conn[0]->webpass,
                ]);

                //limpieza de cache del .env
                DB::purge('conn_dinamica');
                DB::reconnect('conn_dinamica');

                $passCl = new GeneraPassword();

                $tx_clave = $passCl->generatePassword(8);

                $usuario = new UsuarioSaca();
                $usuario->co_empleado = 'demo';
                $usuario->nb_usuario  = $solicitud->correo;
                $usuario->nb_nombres  = $solicitud->nombres;
                $usuario->tx_mail     = $solicitud->correo;
                $usuario->tx_clave    = sha1(md5( $tx_clave ));
                $usuario->admin       = 1;
                $usuario->cod_per     = 'demo';
                $usuario->save();

                $empresa = EmpresaSaca::updateOrCreate([
                    'co_empresas' => 'EP001',
                    'nb_empresas' => $solicitud->empresa,
                ]);


                PerfilSaca::updateOrCreate([
                    'cod_per'        => 'PR001',
                    'desc_per'       => 'SUPERVISOR',
                    'estatus_per'    => 1,
                    'incide'         => 1,
                    'administracion' => 1,
                    'asistencia'     => 1,
                    'tab_generales'  => 1,
                ]);

                PerfilSaca::updateOrCreate([
                    'cod_per'        => 'PR0202',
                    'desc_per'       => 'TRABAJADOR',
                    'estatus_per'    => 1,
                    'incide'         => 0,
                    'administracion' => 0,
                    'asistencia'     => 0,
                    'tab_generales'  => 0,
                ]);

                $id_param = rand(1, 20);

                ParametrosSaca::updateOrCreate([
                    'id'             => $id_param,
                    'codigo'         => 'INC-100',
                    'nombre'         => 'actiaproauto',
                    'descripcion'    => 'activa aproacion automatico',
                    'label'          => 'Aprobar Incidencias Automaticas',
                    'tipo_dato'      => 'seleccion',
                    'valor'          => '0',
                    'estatus'        => 1,
                    'origen'         => 'SACA',
                    'ayuda'          => NULL,
                    'val_seleccion'  => '0,1',
                    'text_seleccion' => 'NO,SI',
                ]);

                ParametrosSaca::updateOrCreate([
                    'id'             => $id_param++,
                    'codigo'         => 'PAR001',
                    'nombre'         => 'timezone_sm',
                    'descripcion'    => 'Zona Horaria de la Licencia',
                    'label'          => 'Zona Horaria',
                    'tipo_dato'      => 'seleccion',
                    'valor'          => 'America/Caracas',
                    'estatus'        => 1,
                    'origen'         => 'SACA',
                    'ayuda'          => NULL,
                    'val_seleccion'  => ZonaHoraria::getZonasValues(true), 
                    'text_seleccion' => ZonaHoraria::getZonasNames(true),
                ]);
                
                $datos_mail = [
                    'codigo'     => $lice,
                    'correo'     => $solicitud->correo,
                    'contrasena' => $tx_clave,
                    'url_saca'   => env('REDIRECT_SACA', 'https://newsm.sacamovil.com/'),
                    'contacto'   => $solicitud->nombres,
                    'empresa'    => $solicitud->empresa,
                    'telefono'   => $solicitud->telefono,
                    'pais'       => $solicitud->pais,
                ];

                $this->correoImportante([
                    'view'      => 'emails.demo_creada',
                    'data_view' => [
                        'codigo'     => $datos_mail['codigo'],
                        'correo'     => $datos_mail['correo'],
                        'contrasena' => $datos_mail['contrasena'],
                        'url_saca'   => $datos_mail['url_saca'],
                    ],
                    'subject'   => 'Solicitud Demo Saca Móvil',
                    'sm'        => $datos_mail['codigo']
                ]);                
                
                if( !empty($this->correos_noti) && !empty($this->correos_noti->valor) ){

                    $this->correoImportante([
                        'view'      => 'emails.demo_creada_admin',
                        'data_view' => [
                            'codigo'     => $datos_mail['codigo'],
                            'correo'     => $datos_mail['correo'],
                            'empresa'    => $datos_mail['empresa'],
                            'contacto'   => $datos_mail['contacto'],
                            'telefono'   => $datos_mail['telefono'],
                            'pais'       => $datos_mail['pais'],
                        ],
                        'subject'   => 'Nuevo Demo Saca Móvil',
                        'sm'        => $datos_mail['codigo']
                    ]);                    
                }

                $solicitud->completado = 1;
                $solicitud->save();

                AlertaTopeLicDisponibles::verificarTope();
                
                return response()->json([
                    'code' => 200,
                    'message' => "Se ha generado la estructura necesaria para el demo de la empresa {$solicitud->empresa}. SM asignado: {$lice}",
                    'lice' => $lice,
                ]);
            }
            else{
                return response()->json([
                    'code'      => 500,
                    'error'     => "El correo {$solicitud->correo} ya ha generado una Demo Previamente, verifique su correo electrónico."
                ]);
            }
        }
        catch( Exception $e ) {

            if( !empty($this->correos_noti) && !empty($this->correos_noti->valor) ){
                $this->correoImportante([
                    'view'      => 'emails.falla_creacion_demo',
                    'data_view' => [ 'error' => $e->getMessage(), 'solicitud' => $solicitud ],
                    'subject'   => 'Error al generar Demo Saca Móvil',
                    'sm'        => 'SM000'
                ]);
            }

            return response()->json([
                'code'    => 500,
                'message' => $e->getMessage(),
                'errors'  => $e
            ]);
        }
    }

    protected function correoImportante($datos)
    {
        CorreoAdministradores::crearEnvio( $datos );
    }
}
