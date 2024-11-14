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
        <h5 style="font-size: 18px; text-align: center">¡Ha ocurrido un error al generar la estructura de un Demo!</h5>
        <br><br>

        <ul>
            <li style="font-size: 16px;">
                <b>Empresa: </b> {{ $solicitud->empresa }}
            </li>
            <li style="font-size: 16px;">
                <b>Correo Electrónico: </b> {{ $solicitud->correo }}
            </li>
        </ul>

        <h4>
            <b>Error: </b> {{ $error ?? '--' }}
        </h4>
    </div>
@endsection