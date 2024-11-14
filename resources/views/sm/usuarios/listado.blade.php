<div class="col-md-12 py-2">
    <div class="row">
        <div class="col-md-5 offset-md-7">
            <div class="input-group mb-3">
                <input type="text" id="filtro" value="{{ $datos['filtro'] ?? '' }}" class="form-control" placeholder="Nombres o correo">
                <div class="input-group-append">
                    <button onclick="cargarPagina(1, true)" class="btn btn-info" type="button">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@if( $usuarios )
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Correo</th>
                <th>Cod. Empleado</th>
                <th>Empleado</th>
                <th>Tipo Empleado</th>
                <th>1ª entrada</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usuarios as $usuario)
                <tr id="tr-usu-{{ $usuario->co_usuario }}">
                    <td>{{ $usuario->co_usuario }}</td>
                    <td>{{ $usuario->tx_mail }}</td>
                    <td>{{ $usuario->co_empleado ?? '' }}</td>
                    <td>{{ $usuario->empleado->nb_empleado ?? $usuario->nb_nombres ?? '--' }}</td>
                    <td>{{ $usuario->empleado->tipo_empleado ?? '' }}</td>
                    <td>{{ $usuario->prime_entrada_text }}</td>
                    <td>
                        @php( $nombres = $usuario->empleado->nb_empleado ?? $usuario->nb_nombres )
                        <button 
                            onclick="showCambioPassUser(`{{ $nombres }}`, `{{ $usuario->co_usuario }}`)" 
                            class="btn btn-sm btn-warning" 
                            title="Cambiar contraseña"
                        >
                            <i class="fa fa-key"></i>
                        </button>

                        @if( $usuario->co_empleado != 'demo' )
                            <button 
                                onclick="eliminarEnModulo(`{{ $nombres }}`, `{{ $usuario->co_usuario }}`, `¿Está seguro de eliminar la cuenta de usuario de: {{ $nombres }}?`, `/sm/usuarios/{{$usuario->co_usuario}}?sm={{$datos['sm']}}`)"
                                class="btn btn-sm btn-danger" 
                                title="Eliminar cuenta de usuario"
                            >
                                <i class="fa fa-trash"></i>
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="col-md-12 mt-3 text-right ml-auto" id="div-pagination">
        {{ $usuarios->links('vendor.pagination.listadosajax-pagination') }}
    </div>
@endif