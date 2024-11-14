@extends('layouts.public')

@section('title', 'Solicitud Demo')
@section('class_body', 'bg-form-soli')

@section('content')
    <div class="row">
        <div class="col-md-12" style="background: url( '/images/fondo_huella_1.jpg') cover no-repeat;">
            <solicitud-component
                :datos_pais="{{ $datos_pais }}"
            />
        </div>
    </div>
@endsection
