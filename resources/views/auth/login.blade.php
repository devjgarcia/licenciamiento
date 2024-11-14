@extends('layouts.guest')

@section('title', 'Login')

@section('content')
    @include('shared.session_status')
    @include('shared.errors')

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="col-12 text-center">
            <img 
                class="mb-4" src="{{ asset('images/iselogo.png') }}" 
                alt="Iseweb C.A" 
                width="72" 
                height="57"
            >
        </div>
        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">
                {{ __('Correo electrónico') }}
            </label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                class="form-control  @error('email') is-invalid @enderror" 
                value="{{ old('email') }}"
                placeholder="correo@iseweb.com" 
                required 
                autofocus 
            />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">
                {{ __('Contraseña') }}
            </label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                class="form-control @error('password') is-invalid @enderror" 
                required 
                autocomplete="current-password" 
            />
        </div>

        <!-- Remember Me -->
        <!--<div class="form-check">
            <input type="checkbox" id="remember_me" name="remember" class="form-check-input">
            <label for="remember_me" class="form-check-label">
                Mantener Sesión
            </label>
        </div>
        -->

        <div class="d-flex align-items-center justify-content-end mt-4">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="me-3 mr-2">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif

            <button type="submit" class="btn btn-primary">
                Ingresar
            </button>
        </div>
    </form>
@endsection