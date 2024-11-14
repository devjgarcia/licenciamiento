@extends('layouts.appvue')

@section('title', 'Cuentas de Pago')

@section('content')

    <np-cuentas-component 
        :usuario="{role_id: {{ Auth::user()->role_id }} }"
    />
@endsection