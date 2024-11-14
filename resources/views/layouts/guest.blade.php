<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Gestión') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <style>
            html,
            body {
                height: 100%;
            }

            body {
                display: flex;
                flex-direction: column;
                align-items: center;
                padding-top: 40px;
                padding-bottom: 40px;
                background-color: #f5f5f5;
            }
        </style>

        @stack('styles')

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body class="@yield('class_body')">
        <main class="w-100 m-auto" style="max-width: 550px;">
            <div class="p-4 border rounded bg-white shadow-sm">
                @yield('content')
            </div>

            @if(Route::is('login'))
                <div class="text-center text-muted mt-3 row">
                    <!--<small class="col-12">versión 1.0.2</small>-->
                    <small class="col-12">Iseweb CA &copy; <?php echo date('Y') ?></small>
                </div>
            @endif
        </main>

        @stack('scripts')
  </body>
</html>