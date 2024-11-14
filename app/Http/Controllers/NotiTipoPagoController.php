<?php

namespace App\Http\Controllers;

use App\Models\NotiTipoPago;
use Illuminate\Http\Request;

class NotiTipoPagoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        if( $request->ajax() ){
            $tipo_pagos = NotiTipoPago::all();

            return response()->json(['tipo_pagos' => $tipo_pagos]);
        }

        return view('np_tipopago.index');
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
            'nombre' => 'required|string|unique:'.NotiTipoPago::class,
            'estatus' => 'required|in:0,1'
        ]);

        $tipo_pago = NotiTipoPago::create($validar);

        return response()->json( ['tipo_pago' => $tipo_pago] );
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
            'nombre' => 'required|string|unique:'.NotiTipoPago::class.',nombre,'.$id,
            'estatus' => 'required|in:0,1'
        ]);

        $tipo_pago = NotiTipoPago::find($id);
        $tipo_pago->fill($validar);
        $tipo_pago->save();

        return response()->json( ['tipo_pago' => $tipo_pago] );
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
