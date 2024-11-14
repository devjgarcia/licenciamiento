@extends('layouts.app')

@section('title', 'Importar idioma')

@section('content')
<div class="container">
    <form method="POST" enctype="multipart/form-data" name="form-dropzone" id="form-dropzone" action="{{ url('import-language') }}">
        @csrf

        <div class="row">

            <div class="mb-3">
                <label for="file" class="form-label">
                    {{ __('Archivo excel') }}
                </label>
                <input 
                    type="file" 
                    id="file" 
                    name="file" 
                    class="form-control @error('file') is-invalid @enderror" 
                    required 
                    accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                />
            </div>

            <div class="col-12 d-flex justify-content-end">
                <button type="submit" id="save" class="btn btn-primary mr-1 mb-1">{{__("Importar")}}</button>
            </div>
        </div>
    </form>
    
</div>
@endsection