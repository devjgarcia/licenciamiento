<?php

namespace App\Http\Controllers\Sistemas;

use App\Http\Controllers\Controller;
use App\Models\Licencias;
use Illuminate\Http\Request;

class LicenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        $datos = $request->all();
        $filtro = $datos['search'] ?? '';

        $licencias = Licencias::where(function($query) use($filtro){
            $query->where('correo', 'LIKE', "%$filtro%")
                    ->orWhere('frkcliente', 'LIKE', "%$filtro%")
                    ->orWhere('codigo', 'LIKE', "%$filtro%");
        })
        ->orderBy('codigo')
        ->paginate(30);

        if( \request()->ajax() ) {
            $data = [
                'listado' => view('sistemas.licencias.listado', compact('licencias'))->render(),
                'data'    => $licencias,
            ];

            return response()->json($data);
        }

        return view('sistemas.licencias.index', compact('licencias'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $licencia = Licencias::find($id);

        $data = [
            'datos' => $licencia,
            'form'  => view('sistemas.licencias.form', ['datos' => $licencia])->render()
        ];

        return response()->json($data);
    }

    public function update( Request $request, $id )
    {
        $response = ['code' => 500, 'message' => ''];

        try {
            $licencia = Licencias::find($id);
            $licencia->servicorreo   = $request->servicorreo;
            $licencia->correos_merca = $request->correos_merca;
            
            if($licencia->save()) {
                $response['code']    = 200;
                $response['message'] = 'Se actualizó la licencia '.$licencia->codigo;

                return response()->json($response);
            }

        }
        catch( \Exception $e ) {
            abort(500, $e->getMessage());
        }
    }
}
