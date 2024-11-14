<?php

namespace App\Http\Controllers;

use App\Models\NotiMoneda;
use Illuminate\Http\Request;

class NotiMonedaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        if( $request->ajax() ){

            $monedas = NotiMoneda::all();

            if( $request->has('only_active') ){
                $monedas = NotiMoneda::where('estatus', 1)->orderBy('nombre', 'asc')->get();
            }

            return response()->json(['noti_monedas' => $monedas]);
        }

        return view('np_monedas.index');
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
            'codigo' => 'required|string|unique:'.NotiMoneda::class.',codigo',
            'nombre' => 'required|string|unique:'.NotiMoneda::class.',nombre',
            'estatus' => 'required|in:0,1'
        ]);

        $moneda = NotiMoneda::create($validar);

        return response()->json( ['noti_moneda' => $moneda] );
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
            'codigo' => 'required|string|unique:'.NotiMoneda::class.',codigo,'.$id,
            'nombre' => 'required|string|unique:'.NotiMoneda::class.',nombre,'.$id,
            'estatus' => 'required|in:0,1'
        ]);

        $moneda = NotiMoneda::find($id);
        $moneda->fill($validar);
        $moneda->save();

        return response()->json( ['noti_moneda' => $moneda] );
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
