@extends('layouts.guest')

@section('title', 'Erro en creación de Demo')
@section('class_body', 'bg-form-soli')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <form>
                @include('solicitud_demo.logo_saca') 
                
                <div class="mb-3 py-3 mt-3 text-center">
                    <i class="fa fa-warning fa-3x text-danger"></i>
                    <h3 class="text-danger">Error</h3>
                </div>
                <div class="mb-3 py-3 mt-3">
                    <p>
                        El correo {{ $solicitud->correo }} ya ha generado una Demo Previamente, verifique su correo electrónico. 
                        <br>
                        <br>
                        Si el problema persiste, por favor contactenos a través de nuestra página web.
                    </p>
                </div>
                <div class="py-5 mt-5 text-center">
                    <button class="btn btn-secondary" type="button" onclick="javascript:window.close()">
                        Cerrar Ventana
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection