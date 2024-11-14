@extends('layouts.app')

@section('title', 'Gestión de Sacamóvil')

@section('content')

<div class="col-md-12 py-2">
    <div class="row">
        <div class="col-md-5 offset-md-7">
            <div class="input-group mb-3">
                <input type="text" id="search" name="search" value="{{ $datos['search'] ?? '' }}" class="form-control" placeholder="Busca por correo, SM o cliente">
                <div class="input-group-append">
                    <button onclick="cargarListado(1, this)" class="btn btn-info" type="button">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12 table-responsive" id="div-listado">
    @include('sistemas.licencias.listado')    
</div>
@endsection

@section('custom_script')
    @include('sistemas.licencias._scripts')
@endsection
