@extends('layouts.appvue')

@section('title', 'Crear Licencia Saca')
@section('content')
    <crear-licencia-component 
        :datos_pais="{{ $datos_pais }}"
        :usuario="{role_id: {{ Auth::user()->role_id }} }"
        :tipos_producto="{{ $tipos_producto }}"
    />
@endsection