@extends('layouts.app')

@section('title', 'Gestión de Sacamóvil')

@section('content')

    <div class="row">
        <div class="col-md-5">
            <div class="form-group">
                <select name="sm" id="sm" class="form-control" onchange="cargaMenuSm(this.value)">
                    <option value="">--Licencia--</option>
                    @foreach ($licencias as $lice)
                        <option value="{{ $lice->codigo }}">{{ $lice->codigo }} - {{ $lice->frkcliente }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-5">
            <div class="input-group">
                <select name="modulo" id="modulo" class="form-control" onchange="cargaModulo(this.value)">
                    <option value="">--Debes seleccionar una licencia--</option>
                </select>
                <button class="btn btn-info" type="button" id="btn-carga-modulo" disabled onclick="cargaModulo($(`#modulo`).val())">
                    <i class="fa fa-sync"></i>
                </button>
            </div>
        </div>

        <div class="col-md-12 mt-3 table-responsive" id="div-modulo" style="display: none;"></div>
    </div>
    
@endsection

@section('custom_script')
    <script>
        const cargaMenuSm = async ( sm ) => {

            if( sm != '' ) {
                $(`#modulo option[value=""]`).text(`Cargando...`);

                await axios.get(`/sm/datasm?sm=${sm}`)
                    .then(response => {
                        const data = response.data ?? false

                        if( data && data.view ) {
                            $(`#modulo`).empty().html(data.view);
                        }
                        else {
                            alert(`Error`);
                        }
                    })
                    .catch(err => console.log(err))
            }
            else {
                $(`#modulo option[value=""]`).text(`--Debes seleccionar una licencia--`);
            }
        }

        const cargaModulo = async (modulo, page = 1, filtro = false) => {
            if( modulo != '' ) {
                
                let params = ``;

                if( filtro ) {
                    const filter = $(`#filtro`).val().trim() ?? ``;
                    params = `filtro=${filter}`
                }
                else {
                    params = `page=${page}`
                }

                $(`#div-modulo`).html(`<h4>Cargando...</h4>`);

                await axios.get(`/sm/${modulo}?sm=${$('#sm').val()}&${params}`)
                    .then(response => {
                        const data = response.data ?? false

                        if( data && data.view ) {
                            $(`#div-modulo`).empty().html(data.view).show();
                            $(`#btn-carga-modulo`).removeAttr(`disabled`);
                        }
                        else {
                            $(`#btn-carga-modulo`).attr(`disabled`,`disabled`);
                            alert(`Error`);
                        }
                    })
                    .catch(err => console.log(err))
            }
            else {
                $(`#btn-carga-modulo`).attr(`disabled`,`disabled`);
                $(`#div-modulo`).empty().hide();
            }
        }

        const eliminarEnModulo = async (nombres, id, question, route) => {
                $.confirm({
                    title: 'Eliminar',
                    content: question,
                    buttons: {
                        confirm: {
                            text: `Sí, eliminar`,
                            btnClass: `btn btn-danger`,
                            action: async function () {
                                await axios.delete(route)
                                        .then(response => {
                                            const resp = response.data ?? false;
                                            if( resp && resp.code == 200 ) {
                                                toastr.success( resp.message );
                                                $(`#${resp.tr}`).remove();
                                            }
                                            else {
                                                toastr.error( resp.message );
                                            }
                                        })

                                return true;
                            },
                        },
                        cancel: {
                            text: `Cancelar`,
                            action: function () {
                                //$.alert('Canceled!');
                            }
                        },
                    }
                });
        }

        const cambioPassUser = async (pass, co_usuario) => {
            
            await axios.put(`/sm/usuarios/${co_usuario}?sm=${$('#sm').val()}`, {password: pass, change_pass: 200})
                    .then(response => {
                        const resp = response.data ?? false

                        if( resp.code == 200 ) {
                            toastr.success(resp.message);
                        }
                        else {
                            toastr.error(resp.message);
                        }
                    })
                    .catch(err => console.log(err))

            return true;
        }

        const showCambioPassUser = ( user, co_usuario ) => {
            $.confirm({
                title: `Cambio de contraseña`,
                content: `<form action="" class="formName">
                            <input type="hidden" class="co-usu" value="${co_usuario}">
                            <p class="mt-2 py-2">Usuario: ${user}</p>
                            <div class="form-group">
                                <label>Nueva contraseña</label>
                                <input type="password" placeholder="Nueva contraseña" class="pass-usu form-control" required />
                                <small>Debe ser una contraseña segura</small>
                            </div>
                        </form>`,
                buttons: {
                    formSubmit: {
                        text: 'Cambiar',
                        btnClass: 'btn btn-success',
                        action: async function () {
                            const co_usu = this.$content.find('.co-usu').val();
                            const pass   = this.$content.find('.pass-usu').val();
                            if(!pass || pass.length < 8){
                                $.alert('Debes ingresar una contraseña válida');
                                return false;
                            }

                            await cambioPassUser(pass, co_usu);
                            //$.alert('Your name is ' + name);
                        }
                    },
                    cancel: {
                        text: 'Cancelar',
                        action: function () {
                        //close
                        },
                    }
                },
                onContentReady: function () {
                    // bind to events
                    var jc = this;
                    this.$content.find('form').on('submit', function (e) {
                        // if the user submits the form by pressing enter in the field.
                        e.preventDefault();
                        jc.$$formSubmit.trigger('click'); // reference the button and click it
                    });
                }
            });
        }

        const cargarPagina = ( page, filtro = false ) => {
            const modulo = $(`#modulo`).val()

            cargaModulo(modulo, page, filtro);
        }
    </script>
@endsection