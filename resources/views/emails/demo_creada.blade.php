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
        <h5 style="font-size: 18px; text-align: center">¡Bienvenido a Saca Móvil!</h5>
        <br><br>

        <p>
            Para ingresar a la plataforma utilice las siguientes credenciales.
        </p>
        <ul>
            <li style="font-size: 16px;">
                <b>Licencia: </b> {{ $codigo }}
            </li>
            <li style="font-size: 16px;">
                <b>Nombre de Usuario: </b> {{ $correo }}
            </li>
            <li style="font-size: 16px;">
                <b>Contraseña: </b> {{ $contrasena }}
            </li>
        </ul>

        <br><br>
        <h5 style="font-size: 16px;"><b>Importante: </b> actualmente cuenta con 60 días en modo Demo, para continuar usando el sistema a despues de los 60 dias deberá adquirir una Licencia del plan de su preferencia</h5>
    <hr>
    <div style="width: 100%; text-align: center;">
        <a href="{{ $url_saca }}" class="btn-action">
            Ir a Saca Móvil
        </a>
    </div>
@endsection