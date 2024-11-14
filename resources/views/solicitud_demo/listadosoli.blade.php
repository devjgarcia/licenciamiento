@extends('layouts.appvue')

@section('title', 'Solicitudes de Demo Saca')

@section('content')

    <listado-solisaca-component 
        :usuario="{role_id: {{ Auth::user()->role_id }} }"
    />
@endsection