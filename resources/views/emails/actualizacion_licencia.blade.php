@extends('layouts.emails')

@section('content')
    
    <div class="div-header">
        <img src="https://test.sacamovil.com/public/images/logos/logosaca_01.png" style="width: 170px; height: 140px;" alt="Logo SACA">
    </div>
    <div style="width: 100%">
        <h5 class="titulo">¡Se ha actualizado una Licencia Saca!</h5>
        <br><br>

        
        @if( $datos['tipo'] === 'extend_vencimiento' )
            <p class="p-body">
                Se ha actualizado el vencimiento de la Licencia <b>{{ $datos['codigo'] }}</b>.
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
            @if( $datos['tipo'] === 'extend_vencimiento' )
                <li>
                    <b>Nuevo Vencimiento: </b> {{ $datos['vencimiento'] }}
                </li>
                <li>
                    <b>Días Extendidos: </b> {{ $datos['dias_extend'] }}
                </li>
                <li>
                    <b>Tipo de Extensión: </b> {{ $datos['tipo_extend'] }}
                </li>
                <li>
                    <b>Observación: </b> {{ $datos['observacion'] }}
                </li>
            @endif
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