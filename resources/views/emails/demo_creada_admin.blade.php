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
        <h5 style="font-size: 18px; text-align: center">¡Nueva Demo Saca Móvil!</h5>
        <br><br>

        <p>
            Se ha generado una nueva demo de Saca Móvil con los siguientes datos:
        </p>
        <ul>
            <li style="font-size: 16px;">
                <b>Licencia: </b> {{ $codigo }}
            </li>
            <li style="font-size: 16px;">
                <b>Empresa: </b> {{ $empresa }}
            </li>
            <li style="font-size: 16px;">
                <b>Correo: </b> {{ $correo }}
            </li>
            <li style="font-size: 16px;">
                <b>Contacto: </b> {{ $contacto }}
            </li>
            <li style="font-size: 16px;">
                <b>Teléfono: </b> {{ $telefono }}
            </li>
            <li style="font-size: 16px;">
                <b>País: </b> {{ $pais }}
            </li>
        </ul>

        <br><br>
    <div style="width: 100%; text-align: center;">
        <hr>
    </div>
@endsection