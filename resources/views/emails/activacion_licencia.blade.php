@extends('layouts.emails')

@section('content')
    
    <div class="div-header">
        <img src="https://test.sacamovil.com/public/images/logos/logosaca_01.png" style="width: 170px; height: 140px;" alt="Logo SACA">
    </div>
    <div style="width: 100%">
        <h5 class="titulo">¡Se ha activado una Licencia Saca!</h5>
        <br><br>

        <p class="p-body">
            Se han activado o procesado recargas a las siguientes Licencias:  
        </p>

        <div class="w-100">
            <table class="table-lice">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Empresa</th>
                        <th>Correo</th>     
                        @if( empty($reactivacion) )                 
                            <th>Nuevo Vencimiento</th>
                            <th>Cantidad Empleados</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if( $licencias )
                        @foreach ( $licencias as $lice )
                            
                            <tr>
                                <td>{{ $lice['codigo'] }}</td>
                                <td>{{ $lice['empresa'] }}</td>
                                <td>{{ $lice['correo'] }}</td>

                                @if( empty($reactivacion) )
                                    <td>{{ $lice['vencimiento'] }}</td>
                                    <td>{{ $lice['total_empleados'] }}</td>
                                @endif
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <br><br>

        <p class="p-body">
            Esta acción del sistema ha sido realizada por: {{ $usuario }}.
        </p>
        <p class="p-body">
            Fecha: {{ $fecha }}.
        </p>
        <br><br>
    <div style="width: 100%; text-align: center;">
        <hr>
    </div>
@endsection