<?php

namespace App\Http\Controllers;

use App\Enums\EstadoUsuarios;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::with('role')->get();

        return view('usuarios.index', compact('usuarios'));
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
    public function store(StoreUserRequest $request)
    {
        $usuario = User::create([
            'name'      => $request->name,
            'rif'       => $request->rif,
            'direction' => $request->direction ?? NULL,
            'phone'     => $request->phone ?? NULL,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role_id'   => $request->role_id,
            'status'    => EstadoUsuarios::ACTIVO
        ]);

        if( !empty($request->file('file_photo')) ){
            $route_photo = $this->uploadPhoto( $request, $usuario );
            $usuario->photo = $route_photo;
            $usuario->save();
        }

        $nuevo_usuario = User::with(['role'])->find($usuario->id);

        return response()->json(['usuario' => $nuevo_usuario]);
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
    public function update(StoreUserRequest $request, $id)
    {
        $usuario = User::find($id);
        
        $usuario->name      = $request->name;
        $usuario->rif       = $request->rif;
        $usuario->direction = $request->direction ?? NULL;
        $usuario->phone     = $request->phone ?? NULL;
        $usuario->email     = $request->email;
        $usuario->role_id   = $request->role_id;
        $usuario->status    = $request->status;

        if( !empty($request->password) ){
            $usuario->password = Hash::make($request->password);
        }

        if( !empty($request->file('file_photo')) ){
            $route_photo = $this->uploadPhoto( $request, $usuario );
            $usuario->photo = $route_photo;
        }

        $usuario->save();

        $usuario_actualizado = User::with(['role'])->find( $id );

        return response()->json(['usuario' => $usuario_actualizado]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return User::destroy($id);
    }

    protected function uploadPhoto( StoreUserRequest $request, $data )
    {
        $carpeta = 'profile_photos';

        $file_name = base64_encode($carpeta.$data['id']).'.'.$request->file_photo->extension();  
        $request->file_photo->move( public_path( $carpeta ), $file_name );

        $ruta = '/'.$carpeta.'/'.$file_name;
        
        return $ruta;
    }
}
