@extends('layouts.guest')

@section('title', 'Recuperar Contraseña')

@section('content')
    @include('shared.session_status')
    @include('shared.errors')

    <form method="POST" action="{{ route('password.update') }}">
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
            <h4>Cambia tu contraseña</h4>
        </div>

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Correo electrónico') }}</label>
            <input 
                id="email" 
                type="email" 
                class="form-control @error('email') is-invalid @enderror" 
                name="email" 
                value="{{ $email ?? old('email') }}"
                required 
                autocomplete="email" 
                autofocus
                readonly
            >

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">{{ __('Nueva Contraseña') }}</label>
            <input 
                id="password" 
                type="password" 
                class="form-control @error('password') is-invalid @enderror" 
                name="password" 
                required 
                autocomplete="new-password"
            >

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password-confirm" class="form-label">{{ __('Confirma contraseña') }}</label>
            <input 
                id="password-confirm" 
                type="password" 
                class="form-control" 
                name="password_confirmation" 
                required 
                autocomplete="new-password"
            >
        </div>

        <div class="form-group row py-3">
            <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-primary">
                    {{ __('Cambiar contraseña') }}
                </button>
            </div>
        </div>
    </form>

@endsection
