<table class="table">
    <tbody>
        <tr>
            <td>
                <b>Código</b>
            </td>
            <td>
                {{ $notipago->codnotipago }}
            </td>
        </tr>
        <tr>
            <td>
                <b>Fecha de Notificación</b>
            </td>
            <td>
                {{ $notipago->fecha_noti }}
            </td>
        </tr>
        <tr>
            <td>
                <b>Fecha de Pago</b>
            </td>
            <td>
                {{ $notipago->fecha_pago }}
            </td>
        </tr>
        <tr>
            <td>
                <b>Tipo de Pago</b>
            </td>
            <td>
                 {{ $notipago->notiTipoPago->nombre }}
            </td>
        </tr>
        <tr>
            <td>
                <b>N de Documento</b>
            </td>
            <td>
                {{ $notipago->n_doc }}
            </td>
        </tr>
        <tr>
            <td>
                <b>Monto</b>
            </td>
            <td>
                {{ $notipago->monto_format }}
            </td>
        </tr>
        <tr>
            <td>
                <b>Estatus</b>
            </td>
            <td>
                <span class="badge" style="{{ $notipago->class_estado }}">{{ $notipago->estado_pago }}</span>                 
            </td>
        </tr>
        <tr>
            <td>
                <b>Comentario</b>
            </td>
            <td>
                {{ $notipago->coment_usu }}
            </td>
        </tr>
        <tr>
            <td>
                <b>Cuenta Receptora</b>
            </td>
            <td>
                {{ $notipago->notiCuenta->nombre }}
            </td>
        </tr>
        <tr>
            <td>
                <b>Moneda de Pago</b>
            </td>
            <td>
                {{ $notipago->notiCuenta->notiMoneda->nombre }} ({{ $notipago->notiCuenta->notiMoneda->codigo }})
            </td>
        </tr>
        <tr>
            <td class="text-center" colspan="2">
                <img 
                    src="{{ asset($notipago->comprobante) }}" 
                    alt=""
                    style="width: 100%; max-width: 100vw; height: auto; max-height: 500px;"
                >
            </td>
        </tr>
    </tbody>
</table>