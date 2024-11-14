<?php

use App\Http\Controllers\NotiPagoController;
use App\Models\NotiCuenta;
use App\Models\NotiMoneda;
use App\Models\NotiTipoPago;
use App\Models\Licencias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/get-monedas', function() {
    $monedas = NotiMoneda::where('estatus', 1)->get();

    return response()->json(['monedas' => $monedas]);
});


Route::get('/get-licencias-activas', function() {
    $licencias = Licencias::where('status', 1)
                        ->whereRaw('vencimiento > NOW()')
                        ->get();

    return response()->json($licencias);
});


Route::get('/get-tipopagos', function() {
    $tipos = NotiTipoPago::where('estatus', 1)->get();

    return response()->json(['tipo_pagos' => $tipos]);
});

Route::get('/get-cuentas', function( Request $request ) {

    if( $request->has('tipo_moneda') ){
        $cuentas = NotiCuenta::where('estatus', 1)->where('tipo_moneda', $request->tipo_moneda)->get();

        return response()->json(['cuentas' => $cuentas]);
    }
});

Route::post('/np-sv-saca', [NotiPagoController::class, 'store']);
Route::get('/np-sv-saca/get', [NotiPagoController::class, 'index']);
