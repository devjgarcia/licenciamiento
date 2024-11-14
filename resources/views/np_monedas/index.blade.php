@extends('layouts.appvue')

@section('title', 'Monedas de Pago')

@section('content')

    <np-monedas-component 
        :usuario="{role_id: {{ Auth::user()->role_id }} }"
    />
@endsection