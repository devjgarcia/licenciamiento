@extends('layouts.appvue')

@section('title', 'Usuarios')

@section('content')
    <usuarios-component :listado_usuarios="{{ $usuarios }}" />
@endsection
