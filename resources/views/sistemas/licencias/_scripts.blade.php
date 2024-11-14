<script>
    let page_act = 1;

    const cargarListado = async (page = 1, btn = false) => {

        if( btn ) {
            $(btn).attr(`disabled`,`disabled`);
        }

        page_act = page;

        let params = `page=${page}`;

        const filter = $(`#search`).val().trim() ?? ``;
        params += `&search=${filter}`
        
        $(`#div-listado`).html(`<h4>Cargando...</h4>`);

        await axios.get(`/datoslice?${params}`)
            .then(response => {
                const data = response.data ?? false

                if( data && data.listado ) {
                    $(`#div-listado`).empty().html(data.listado).show();
                }
                else {
                    $(`#div-listado`).empty().html(JSON.stringify(response)).show()
                }
            })
            .catch(err => console.log(err))

        if( btn ) {
            $(btn).removeAttr(`disabled`);
        }
    }

    const cargarPagina = ( page ) => {
        cargarListado(page)
    }

    const abrirModalEditar = async (sm, id) => {
        titulosModal(sm, id);
        $(`#btn-show-modal-dina`).trigger(`click`)

        await axios.get(`/licesm/${id}/edit`)
                .then(response => {
                    const resp = response.data ?? false;

                    if( resp.form ) {
                        $(`#modal-dina-body`).empty().html(resp.form);
                        $(`#modal-dina-btn`).removeAttr(`disabled`, `disabled`);
                    }
                })
    }

    const cerrarModal = () => {
        $(`#modal-dina-close`).trigger(`click`)
        $(`#modal-dina-body`).empty()
        $(`#modal-dina-btn`).attr(`disabled`);
    }

    const actualizarLice = async (btn, id) => {
        $(btn).attr(`disabled`, `disabled`);

        const formulario = document.getElementById('modal-dina-form');
        const form       = $(`#modal-dina-form`);

        if( form && form.length > 0 && formulario.checkValidity() ) {
            const data = form.serialize()
            await axios.put(`/licesm/${id}`, data)
                .then(response => {
                    const resp = response.data ?? false;

                    if( resp.code && resp.code == 200 ) {
                        cerrarModal();
                        cargarListado(page_act)
                        toastr.success(resp.message);
                    }
                    else {
                        toastr.error(resp.message);
                    }
                })
                .catch(err => mostrarErrores(err))
        }
        else {
            toastr.error(`Debes completar el formulario de manera correcta`)
        }

        $(btn).removeAttr(`disabled`);
    }

    const titulosModal = (sm, id) => {
        $(`#modal-dina-title`).text(`Licencia ${sm}`)
        $(`#modal-dina-btn`).text(`Guardar`)
        $(`#modal-dina-btn`).removeAttr(`onclick`).attr(`onclick`, `actualizarLice(this, ${id})`)
    }
</script>