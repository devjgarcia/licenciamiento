@if( $licencias )
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Codigo</th>
                <th>Cliente</th>
                <th>Correo</th>
                <th>Estatus</th>
                <th>Vencimiento</th>
                <th>Servi Correos</th>
                <th>Lím. Empleados</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($licencias as $lice)
                <tr id="tr-lice-{{ $lice->codigo }}">
                    <td>{{ $lice->codigo }}</td>
                    <td>{{ $lice->frkcliente }}</td>
                    <td>{{ $lice->correo }}</td>
                    <td><span class="badge badge-{{ $lice->class_estado }}">{{ $lice->estado_licencia }}</span></td>
                    <td>{{ $lice->vencimiento }}</td>
                    <td class="text-center">{!! $lice->span_servicorreo !!}</td>
                    <td>{{ $lice->totemple }}</td>
                    <td>
                        <button 
                            onclick="abrirModalEditar(`{{ $lice->codigo }}`, `{{ $lice->id }}`)" 
                            class="btn btn-sm btn-info" 
                            title="Editar"
                        >
                            <i class="fa fa-edit"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="col-md-12 mt-3 text-right ml-auto" id="div-pagination">
        {{ $licencias->links('vendor.pagination.listadosajax-pagination') }}
    </div>
@endif