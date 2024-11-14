@extends('layouts.emails')

@section('content')
    
    <div class="div-header">
        <img src="https://test.sacamovil.com/public/images/logos/logosaca_01.png" style="width: 170px; height: 140px;" alt="Logo SACA">
    </div>
    <div style="width: 100%">
        <h5 class="titulo">Licencias Próximas a Vencer</h5>
        <br><br>

        <p class="p-body">
            Las siguientes Licencias vencen pronto: 
        </p>

        <div class="w-100">
            <table class="table-lice">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Empresa</th>
                        <th>Tipo Producto</th>                        
                        <th>Vencimiento</th>
                        <th>Persona</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                    </tr>
                </thead>
                <tbody>
                    @if( $licencias )
                        @foreach ( $licencias as $lice )
                            <tr>
                                <td>{{ $lice->codigo }}</td>
                                <td>{{ $lice->empresa }}</td>
                                <td>{{ $lice->tipo_producto }}</td>
                                <td>{{ $lice->vencimiento }}</td>
                                <td>{{ $lice->persona }}</td>
                                <td>{{ $lice->correo }}</td>
                                <td>{{ $lice->telefono }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <br><br>
    <div style="width: 100%; text-align: center;">
        <hr>
    </div>
@endsection