@extends('layouts.appvue')

@if( $vencimiento == 1 )
    @section('title', 'Licencias Saca próximas a vencer')
@else
    @section('title', 'Licencias Saca')
@endif

@section('content')

    @if( $estado === 2 )
        <activacion-licencias-component :usuario="{role_id: {{ Auth::user()->role_id }} }" :listado_licencias="{{ $licencias }}" />
    @else
        <licencias-component 
            :vencimiento="{{ $vencimiento }}" 
            :estado="{{ $estado }}" 
            :listado_lice="{{ $licencias }}" 
            :usuario="{role_id: {{ Auth::user()->role_id }} }"
        />
    @endif
@endsection
