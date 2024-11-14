@extends('layouts.emails')

@section('content')
    
    <div class="div-header">
        <img src="https://plataformaise.sacamovil.com/images/iselogo.png" style="width: 170px; height: 140px;" alt="Logo SACA">
    </div>
    <div style="width: 100%">
        <h5 class="titulo">¡Se ha creado una Licencia Saca!</h5>
        <br><br>

        
        @if( $datos['tipo'] === 'extend_vencimiento' )
            <p class="p-body">
                La Licencia Saca <b>{{ $datos['codigo'] }}</b> fue creada desde la plataforma de Licenciamiento.
            </p>
        @endif

        <ul class="lista">
            <li>
                <b>Licencia: </b> {{ $datos['codigo'] }}
            </li>
            <li>
                <b>Empresa: </b> {{ $datos['empresa'] }}
            </li>
            <li>
                <b>Correo: </b> {{ $datos['correo'] }}
            </li>
        </ul>

        <br><br>

        <p class="p-body">
            Esta acción del sistema ha sido realizada por: {{ $datos['usuario'] }}.
        </p>
        <p class="p-body">
            Fecha: {{ $datos['fecha'] }}.
        </p>
        <br><br>
    <div style="width: 100%; text-align: center;">
        <hr>
    </div>
@endsection