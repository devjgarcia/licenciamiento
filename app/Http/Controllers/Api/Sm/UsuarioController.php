<?php

namespace App\Http\Controllers\Api\Sm;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helper\ConexionDinamica;
use App\Models\Saca\Usuario;

use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        //se refresca la conexion con la tabla de la sm con la que se trabaja
        ConexionDinamica::cargarConnSm( $request->sm );
        $datos = $request->all();
        $filtro = $datos['filtro'] ?? '';

        $usuarios = Usuario::where(function($query) use($filtro){
                        $query->where('tx_mail', 'LIKE', "%$filtro%")
                                ->orWhere('nb_nombres', 'LIKE', "%$filtro%");
                    })
                    ->with(['empleado', 'perfilUsu'])
                    ->orderBy('co_usuario')
                    ->paginate(15);

        $data = [
            'view'  => view('sm.usuarios.listado', compact('usuarios', 'datos'))->render(),
            'datos' => $usuarios,
        ];

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if( $request->has('change_pass') ) {

            $response = ['code' => 500, 'message' => ''];
            $validator = Validator::make($request->all(), [
                'password' => [
                    'required',
                    'min:8', // Mínimo 8 caracteres
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.#])[A-Za-z\d@$!%*?&.#]/', // Al menos una minúscula, una mayúscula, un dígito y un carácter especial
                ],
            ]);
            
            if ($validator->fails()) {
                $response['message'] = 'La contraseña no es válida. Debe contener: Al menos una minúscula, una mayúscula, un dígito y un carácter especial (@$!%*?&.#)';
                return response()->json($response);
            }

            $tx_clave = sha1(md5($request->password));

            ConexionDinamica::cargarConnSm( $request->sm );
            $usuario = Usuario::find($id);
            $usuario->tx_clave = $tx_clave;
            $update  = $usuario->save();

            if( $update ) {
                $response = [
                    'code'    => 200,
                    'pass'    => $tx_clave, 
                    'message' => 'Se ha cambiado la contraseña del usuario'
                ];

                return response()->json($response);
            }
        }
        else {
            return response('', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $response = ['code' => 500, 'message' => ''];

        try {
            
            ConexionDinamica::cargarConnSm( $request->sm );
            $datos = $request->all();

            $usuario = Usuario::with(['empleado'])->where('co_usuario', $id)->first();
            $nombres = $usuario->empleado->nb_empleado ?? $usuario->nb_nombres;

            if(Usuario::destroy($id)) {
                $response = ['code' => 200, 'message' => 'Se ha eliminado la cuenta de usuario de '.$nombres, 'tr' => 'tr-usu-'.$id];
            }
        }
        catch( \Exception $e ) {
            $response['message'] = $e->getMessage();
        }

        return response()->json($response);
    }
}
