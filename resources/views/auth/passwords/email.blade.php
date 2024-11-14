@extends('layouts.guest')

@section('title', 'Recuperar Contraseña')

@section('content')
    @include('shared.session_status')
    @include('shared.errors')

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="col-12 text-center">
            <img 
                class="mb-4" src="{{ asset('images/iselogo.png') }}" 
                alt="Iseweb C.A" 
                width="72" 
                height="57"
            >
        </div>

        <div class="col-12 text-center py-3">
            <h4>Recuperación de Acceso</h4>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Correo electrónico') }}</label>
            <input 
                id="email" 
                type="email" 
                class="form-control @error('email') is-invalid @enderror" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                autocomplete="email" 
                autofocus
            >

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group row mb-0 py-3">
            <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-primary">
                    {{ __('Enviar link para resetear contraseña') }}
                </button>
            </div>
        </div>
    </form>
@endsection
