<?php

use App\Enums\EstadoLicencia;
use App\Enums\EstadoUsuarios;
use App\Exports\LicenciasExport;
use App\Http\Controllers\CorreoPorVencerController;
use App\Http\Controllers\DatosSmController;
use App\Http\Controllers\EstadisticasController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LicenciaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\Api\Sm\UsuarioController as SmUsuarioController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IdiomaController;
use App\Http\Controllers\ImportIdiomaController;
use App\Http\Controllers\NotiCuentaController;
use App\Http\Controllers\NotiMonedaController;
use App\Http\Controllers\NotiPagoController;
use App\Http\Controllers\NotiTipoPagoController;
use App\Http\Controllers\ParametrosController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\SolicitudDemoController;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//ruta por defecto
Route::get('/', [HomeController::class, 'index']);

//Rutas que crea Laravel para todo lo necesario con autenticacion
Auth::routes();

Route::middleware('auth', 'rol')->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::post('/activate_lice', [LicenciaController::class, 'activateLicen']);

    /**
     * Rustas de Sistema
     */
        
        //carga vista para la creacion de nueva licencia por Administracion
        Route::get('/licencia/generar', [LicenciaController::class, 'create'])
                ->name('crear_licencia')
                ->middleware('sistemas');

        //Ruta que almacena las licencias que se generan por Administracion
        Route::post('/licencia/guardar', [LicenciaController::class, 'store'])
                ->name('guardar_licencia')
                ->middleware('sistemas');

        //ruta que retorna el listado de Estatus para las licencias
        Route::get('/obtenerEstados', function() {        
            return response()->json(['estados' => EstadoLicencia::getEstadoLicencia()]);
        })->middleware('sistemas');

        //ruta que retorna el listado de Estatus para las licencias
        Route::get('/parametros',[ParametrosController::class, 'index'])->middleware('sistemas');

        Route::resource('/parametros', ParametrosController::class)
                ->except(['index', 'create', 'edit'])
                ->middleware('sistemas');

        Route::get('/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware('sistemas');

        Route::get('/datoslice', '\App\Http\Controllers\Sistemas\LicenciaController@index')->middleware('sistemas');
        Route::resource('/licesm', App\Http\Controllers\Sistemas\LicenciaController::class)->only(['edit', 'update']);


        Route::group(['prefix' => 'sm', 'middleware' => 'sistemas'], function () {
            Route::get('/datasm',[DatosSmController::class, 'index']);
            Route::resource('/usuarios', SmUsuarioController::class)->only(['index', 'destroy', 'update']);
        });

    /**
     * Hasta aqui rutas que se protegen para perfil Sistemas
     */

    
     /**
     * Rustas de Administradores
     */
    Route::middleware('EsAdmin')->group(function () {

        //rutas solo administrador
        Route::post('/extender_vencimiento', [LicenciaController::class, 'extenderVencimiento']);
        Route::get('/getLiceAct', [LicenciaController::class, 'getLiceAct']);
        Route::get('/getLiceXActivar', [LicenciaController::class, 'getLiceXActivar']);
        Route::resource('/licencia', LicenciaController::class)->except(['index', 'create', 'edit', 'destroy']);
    
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::resource('/usuario', UsuarioController::class)->except(['create', 'edit', 'index', 'update']);
        Route::post('/usuario/{id}', [UsuarioController::class, 'update']);
        Route::get('/getRoles', [RolController::class, 'getRoles']);
        Route::get('/getEstadosUsuario', function(){
            $estados = EstadoUsuarios::getEstados();
            return response()->json(['estados' => $estados]);
        });
    
        Route::get('/descarga-licencias/{estado}', function( $estado ){
    
            $fecha = Carbon::now()->format('Ymd');
    
            return Excel::download(new LicenciasExport($estado), "Licencias_{$fecha}.xlsx");
        });

        Route::get('/lice_dispo_estadisticas', [EstadisticasController::class, 'licenciasDisponibles']);

        Route::get('/tipo-pagos',[NotiTipoPagoController::class, 'index']);
        Route::resource('/tipo-pagos', NotiTipoPagoController::class)->except(['index', 'create', 'edit']);

        Route::get('/noti-moneda',[NotiMonedaController::class, 'index']);
        Route::resource('/noti-moneda', NotiMonedaController::class)->except(['index', 'create', 'edit']);

        Route::get('/noti-cuenta',[NotiCuentaController::class, 'index']);
        Route::resource('/noti-cuenta', NotiCuentaController::class)->except(['index', 'create', 'edit']);

        Route::get('/solisaca-listado', function(){
            return view('solicitud_demo.listadosoli');
        });

        Route::get('/demosoli-listado', [SolicitudDemoController::class, 'select']);

        Route::get('/correos-saca', function(){
            return view('correos_saca.index');
        });
    });

    /**
     * Hasta aqui rutas que se protegen para perfil Administrador
     */

    /**
     * Rustas de Cobranza
     */
    Route::group(['middleware' => ['EsCobranza']], function(){
        //rutas para admin y cobranza
        Route::get('/activar-licencias', [LicenciaController::class, 'activacion'])->name('activar_licencias');
        Route::get('/licencias', [LicenciaController::class, 'index'])->name('licencias.index');
        Route::get('/getLice', [LicenciaController::class, 'getLice']);
    });

    Route::group(['middleware' => ['EsSoporte']], function(){
        //rutas para soporte
        Route::resource('/language', IdiomaController::class);
        Route::get('/import-language', [ImportIdiomaController::class, 'create']);
        Route::post('/import-language', [ImportIdiomaController::class, 'import']);
    });

    Route::put('notipago/{id}', [NotiPagoController::class, 'update']);
    
});





//rutas publicas
Route::get('/solicitud_demo', [SolicitudDemoController::class, 'index']);
Route::post('/solicitud_demo', [SolicitudDemoController::class, 'store']);
Route::get('/demo/activation', [SolicitudDemoController::class, 'activation']);
Route::get('/alerta_previo_vence', [CorreoPorVencerController::class, 'enviarCorreoLicencias']);
