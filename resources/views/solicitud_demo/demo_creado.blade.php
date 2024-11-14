@extends('layouts.guest')

@section('title', 'Demo Creado')
@section('class_body', 'bg-form-soli')

@section('content')
    <form>
        @include('solicitud_demo.logo_saca') 

        <div class="mb-3 py-3 mt-3 text-center">
            <i class="fa fa-check fa-3x text-success"></i>
            <h3 class="text-success">{{ $lice }}</h3>
        </div>
        <div class="mb-3 py-3 mt-3 text-center">
            <h4>
                Demo ha sido creado, verifique su correo electrónico para obtener sus credenciales y poder ingresar al sistema.
            </h4>
        </div>
        <div class="py-5 mt-5 text-center">
            <button class="btn btn-secondary" type="button" onclick="javascript:window.close()">
                Cerrar Ventana
            </button>
        </div>
    </form>
@endsection