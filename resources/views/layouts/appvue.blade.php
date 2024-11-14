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
        <!--<link rel="stylesheet" href="{ asset('css/app.css') }">-->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        @stack('styles')

        <!-- JQuery -->
        <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>

        <script type="text/javascript">
            var idleTime = 0;
            $(document).ready(function () {
                //Increment the idle time counter every minute.
                var idleInterval = setInterval(timerIncrement, 240000); // 1 minute

                //Zero the idle timer on mouse movement.
                $(this).mousemove(function (e) {
                    idleTime = 0;
                });
                $(this).keypress(function (e) {
                    idleTime = 0;

                });
            });

            function timerIncrement() {
                idleTime = idleTime + 1;

                if (idleTime >= 4) { 
                    //minutes
                    $("#form_logout").submit()
                }
            }
        </script>   

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="{{ asset('vue/js/app.js') }}" defer></script>
    </head>
    <body class="bg-light">
        @auth
            @include('layouts.navigation')
        @endauth

        <div class="bg-white border-top shadow-sm py-3 mb-4">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <h3 class="m-0 fs-5">@yield('title')</h3>
                @yield('toolbar')
            </div>
        </div>

        <main class="container-fluid">
            <div id="app">
                @yield('content')
            </div>
        </main>

        @stack('scripts')
    </body>
</html>
