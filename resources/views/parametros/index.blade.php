@extends('layouts.appvue')

@section('title', 'Parámetros de Sistema')

@section('content')

    <parametros-component 
        :parametros="{{ $parametros }}" 
        :usuario="{role_id: {{ Auth::user()->role_id }} }"
    />
@endsection