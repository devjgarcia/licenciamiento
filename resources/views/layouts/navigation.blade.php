<nav class="navbar navbar-expand-lg navbar-dark bg-lightblue">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home') }}">
            {{ config('app.name', 'Licenciamiento') }}
        </a>
  
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false">
            <span class="navbar-toggler-icon"></span>
        </button>
  
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="navbar-nav align-items-center ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/home" role="button" aria-expanded="false">
                        <i class="fa fa-home"></i> 
                        Inicio
                    </a>
                </li>
                @if( Auth::user()->isSoporte())
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-globe-americas"></i> 
                        Idiomas
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="/language" class="dropdown-item">
                                Etiquetas
                            </a>
                        </li>
                        <li>
                            <a href="/import-language" class="dropdown-item">
                                Importar de excel
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if( Auth::user()->isSistemas())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-user-secret"></i> 
                            Sistemas
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{ route('crear_licencia') }}" class="dropdown-item">
                                    Crear Licencia
                                </a>
                            </li>
                            <li>
                                <a href="/parametros" class="dropdown-item">
                                    Parámetros de Sistema
                                </a>
                            </li>
                            <li>
                                <a href="/correos-saca" class="dropdown-item">
                                    Correos de Saca
                                </a>
                            </li>
                            <li>
                                <a href="/logs" target="__blank" class="dropdown-item">
                                    Logs de Sistema
                                </a>
                            </li>
                            <li>
                                <a href="/sm/datasm" class="dropdown-item">
                                    Gestión en SM
                                </a>
                            </li>
                            <li>
                                <a href="/datoslice" class="dropdown-item">
                                    Datos Licencia
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif
                @if( Auth::user()->isCobranza() || Auth::user()->isAdmin() )
                    <!--<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-clipboard-check"></i> 
                            Solicitud Demo
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="#" class="dropdown-item">
                                    Ver Solicitudes
                                </a>
                            </li>
                        </ul>
                    </li>-->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-id-card"></i> 
                            Licencias
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{ route('activar_licencias') }}" class="dropdown-item">
                                    Activación y Recarga
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('licencias.index') }}?estado=2" class="dropdown-item">
                                    Licencias en Demo
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="{{ route('licencias.index') }}" class="dropdown-item">
                                    Licencias Activas
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('licencias.index') }}?vencimiento_proximo=1" class="dropdown-item">
                                    Licencias Por Vencer
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('licencias.index') }}?estado=3" class="dropdown-item">
                                    Licencias Vencidas
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="/solisaca-listado" class="dropdown-item">
                                    Solicitudes de Demo
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if( Auth::user()->isAdmin() )
                    <!--<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-cogs"></i> 
                            Administración
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{ route('usuarios.index') }}" class="dropdown-item">
                                    Parámetros de Sistema
                                </a>
                            </li>
                        </ul>
                    </li>-->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-dollar-sign"></i> 
                            Pagos Saca
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="/tipo-pagos" class="dropdown-item">
                                    Tipos de Pago
                                </a>
                            </li>
                            <li>
                                <a href="/noti-moneda" class="dropdown-item">
                                    Monedas de Pago
                                </a>
                            </li>
                            <li>
                                <a href="/noti-cuenta" class="dropdown-item">
                                    Cuentas de Pago
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-users"></i> 
                            Usuarios
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{ route('usuarios.index') }}" class="dropdown-item">
                                    Gestión de Usuarios
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
  
        <ul class="navbar-nav align-items-center mr-2">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-user-circle"></i>
                    {{ Auth::user()->name }}
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <form method="POST" action="{{ route('logout') }}" id="form_logout">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                {{ __('Cerrar Sesión') }}
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
  