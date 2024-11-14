@extends('layouts.emails')

@section('content')
    
    <div class="div-header">
        <img src="https://test.sacamovil.com/public/images/logos/logosaca_01.png" style="width: 170px; height: 140px;" alt="Logo SACA">
    </div>
    <div style="width: 100%">
        <h5 class="titulo">Quedan Pocas Licencias para asignar</h5>
        <br><br>

        <p class="p-body">
            Hay pocas licencias disponibles para asignación, por favor verifique.
        </p>

        <div class="w-100">
            <table class="table-lice">
                <thead>
                    <tr>
                        <th>Asignadas</th>
                        <th>Disponibles</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="color: #9a0000; text-align: center; font-size: 16px;">{{ $asignadas }}</td>
                        <td style="color: #009a22; text-align: center; font-size: 16px;">{{ $disponibles }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <br><br>
    <div style="width: 100%; text-align: center;">
        <hr>
    </div>
@endsection