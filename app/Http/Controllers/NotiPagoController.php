<?php

namespace App\Http\Controllers;

use App\Enums\EstadoNotiPago;
use App\Mail\NotificacionPagoMail;
use App\Models\Licencias;
use App\Models\NotiCuenta;
use App\Models\NotiPago;
use App\Models\NotiTipoPago;
use App\Models\Parametros;
use App\Models\SolicitudRecarga;
use Carbon\Carbon;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class NotiPagoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        abort_unless( $request->has('codnp'), 404, 'Error en la petición, verifique los parametros' );

        $notipago  = NotiPago::with(['notiTipoPago', 'notiCuenta'])
                            ->where('codnotipago', $request->input('codnp') )
                            ->firstOrFail();
        $contenido = view('np_pagos._detalle_pago', compact('notipago'))->render();

        return response()->json(['datos' => $notipago, 'view_detail' => $contenido]);
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
        $datos = $request->validate([
            'sm_codigo'    => 'required|string|exists:'.Licencias::class.',codigo',
            'correo_lice'  => 'required|email|exists:'.Licencias::class.',correo',
            'fecha_pago'   => 'required|date',
            'tipo_pago'    => 'required|exists:'.NotiTipoPago::class.',id' ,
            'n_doc'        => 'required|string',
            'monto'        => 'required|numeric',
            'file_comprob' => 'nullable|mimes:jpg,png',
            'coment_usu'   => 'nullable|string',
            'np_cuentas'   => 'required|exists:'.NotiCuenta::class.',codigo',
            'fechaproce'   => 'required|date',
            'totemple'     => 'required|integer'
        ]);

        $solicitud = SolicitudRecarga::where('codigo', $datos['sm_codigo'])
                                    ->where('correo', $datos['correo_lice'])
                                    ->where('fechaproce', $datos['fechaproce'])
                                    ->where('totemple', $datos['totemple'])
                                    ->whereIn('tiporeg', ['CO','RT'])
                                    ->first();

        abort_unless( $solicitud, 404, 'No existe solicitud pendiente para su Licencia' );

        $anio = Carbon::now()->format('Y');
        $mes  = Carbon::now()->format('m');

        $count_noti = NotiPago::where('sm_codigo', $datos['sm_codigo'])
                            ->where('correo_lice', $datos['correo_lice'])
                            ->whereRaw("YEAR(fecha_noti) = {$anio}")
                            //->whereRaw("MONTH(fecha_noti) = {$mes}")
                            ->count() + 1;
        //nueva notificacion
        $noti_pago = NotiPago::create([
            'codnotipago' => "NP{$datos['sm_codigo']}-{$anio}-{$count_noti}",
            'sm_codigo'   => $datos['sm_codigo'],
            'correo_lice' => $datos['correo_lice'],
            'fecha_noti'  => Carbon::now(),
            'fecha_pago'  => $datos['fecha_pago'],
            'tipo_pago'   => $datos['tipo_pago'],
            'n_doc'       => $datos['n_doc'],
            'monto'       => $datos['monto'],
            'coment_usu'  => $datos['coment_usu'],
            'np_cuentas'  => $datos['np_cuentas'],
            'estatus'     => EstadoNotiPago::EN_ESPERA,
        ]);
        
        if( !empty($datos['file_comprob']) ){
            
            $noti_pago->comprobante = $this->uploadComprob( $request, $noti_pago );
            $noti_pago->save();
        }

        //envio de email para notificar
        $datos_mail = [
            'licencia' => Licencias::where('codigo', $datos['sm_codigo'])->first(),
            'pago'     => NotiPago::with(['notiTipoPago', 'notiCuenta'])->where('codnotipago', $noti_pago->codnotipago)->first(),
        ];

        $correos_noti = Parametros::where('nombre', 'CORREO_ALERTA_PAGO')
                                        ->where('estatus', 1)
                                        ->first();

        if( !empty($correos_noti) && !empty($correos_noti->valor) ) {

            $sep = explode('.', trim($datos_mail['pago']->comprobante));
            $ext = strtolower(end($sep));

            $datos_mail['comprobante']      = public_path( $datos_mail['pago']->comprobante );
            $datos_mail['as_comprobante']   = 'comprobante.' . $ext;
            $datos_mail['mime_comprobante'] = 'image/' . $ext;
            

            //Mail::to( explode(',', trim($correos_noti->valor, ',')) ?? 'garcia.jcarlos95@gmail.com' )
            //    ->send( new NotificacionPagoMail( $datos_mail ) );
        }

        return response()
                ->json([
                    'code'    => 200,
                    'message' => 'Se ha guardado su Notificación de Pago',
                    'data'    => $noti_pago,
                ]);
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
        $notipago = NotiPago::where('codnotipago', $id)
                            ->where('estatus', EstadoNotiPago::EN_ESPERA)
                            ->first();

        if( $notipago ){

            $notipago->estatus = EstadoNotiPago::REVISADO;
            $notipago->save();

            return response()->json($notipago);
        }
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

    protected function uploadComprob( Request $request, $data )
    {
        $carpeta = 'np_pagos';

        $file_name = base64_encode($data->codnotipago).'.'.$request->file_comprob->extension();  
        $request->file_comprob->move( public_path( $carpeta ), $file_name );

        $ruta = '/'.$carpeta.'/'.$file_name;
        
        return $ruta;
    }
}
