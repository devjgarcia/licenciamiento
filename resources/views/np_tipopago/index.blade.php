@extends('layouts.appvue')

@section('title', 'Tipos de Pago')

@section('content')

    <np-tipopago-component 
        :usuario="{role_id: {{ Auth::user()->role_id }} }"
    />
@endsection