<?php

namespace App\Http\Controllers;

use App\Models\NotiCuenta;
use App\Models\NotiMoneda;
use Illuminate\Http\Request;

class NotiCuentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        if( $request->ajax() ){
            $cuentas = NotiCuenta::with(['notiMoneda'])->get();

            return response()->json(['noti_cuentas' => $cuentas]);
        }

        return view('np_cuentas.index');
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
        $validar = $request->validate([
            'codigo' => 'required|string|unique:'.NotiCuenta::class.',codigo',
            'nombre' => 'required|string|unique:'.NotiCuenta::class.',nombre',
            'tipo_moneda' => 'required|string|exists:'. NotiMoneda::class . ',codigo',
            'estatus' => 'required|in:0,1'
        ]);

        $cuenta = NotiCuenta::create($validar);
        $cuenta_data = NotiCuenta::with(['notiMoneda'])->find($cuenta->id);

        return response()->json( ['noti_cuenta' => $cuenta_data] );
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
        $validar = $request->validate([
            'codigo' => 'required|string|unique:'.NotiCuenta::class.',codigo,'.$id,
            'nombre' => 'required|string|unique:'.NotiCuenta::class.',nombre,'.$id,
            'tipo_moneda' => 'required|string|exists:'. NotiMoneda::class . ',codigo',
            'estatus' => 'required|in:0,1'
        ]);

        $cuenta = NotiCuenta::with(['notiMoneda'])->find($id);
        $cuenta->fill($validar);
        $cuenta->save();

        return response()->json( ['noti_cuenta' => $cuenta] );
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
}
