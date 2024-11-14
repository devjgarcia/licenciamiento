@extends('layouts.emails')

@section('content')
    
    <div 
        style="width: 90%; 
                padding-left: 5%;
                padding-right: 5%;
                padding-top: 10px;
                padding-bottom: 10px;
                background-color: #FFFFFF;
                border-bottom: solid 2px #636b6f;
                text-align: center;
            "
    >
        <img src="https://newsm.sacamovil.com/public/images/logos/logosaca-grayblue.png" style="width: 250px; height: 190px;" alt="Logo SACA">
    </div>
    <div style="width: 100%">
        <h5 style="font-size: 18px; text-align: center">¡Gracias por solicitar los servicios de nuestra plataforma!</h5>
        <br><br>

        <ul>
            <li style="font-size: 16px;">
                <b>Empresa: </b> {{ $datos->empresa }}
            </li>
            <li style="font-size: 16px;">
                <b>Correo Electrónico: </b> {{ $datos->correo }}
            </li>
            <li style="font-size: 16px;">
                <b>Teléfono: </b> {{ $datos->telefono }}
            </li>
        </ul>

        <br><br>
        <h5 style="font-size: 16px;">Para continuar con el proceso de Solicitud de Demo, haga click en el siguiente botón: </h5>
    <hr>
    <div style="width: 100%; text-align: center; padding-bottom: 1rem;">
        <a target="__blank" href="{{ url('/demo/activation?s=') }}{{ $datos->idsoli }}&token={{ $datos->token }}" class="btn-action">
            Completar Solicitud
        </a>
    </div>
@endsection