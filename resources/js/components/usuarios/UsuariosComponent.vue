<template>
    <div>    
        <ListadoUsuarios 
            ref="listado"
            :listado_usuarios="this.listado_usuarios"
            @crear_usuario="mostrarModalFormulario"
            @editar_usuario="mostrarModalFormulario"
        />

        <ModalFormulario 
            ref="modal_formulario"
            @usuario_creado="agregarUsuarioAListado"
            @usuario_actualizado="actualizarUsuarioEnListado"
        />
    </div>
</template>

<script>
import ListadoUsuarios from './ListadoUsuarios'
import ModalFormulario from './ModalFormulario'

export default {
    props: ['listado_usuarios'],
    components: {
        ListadoUsuarios,
        ModalFormulario
    },
    data() {
        return {
            usuario_en_edicion: null,
        }
    },
    methods: {
        mostrarModalFormulario( usuario = null ) {
            const usuEditar = ( usuario !== null ) ? usuario : null
            this.$refs.modal_formulario.mostrarModal( usuEditar );
        },
        agregarUsuarioAListado( usuario ) {
            this.$refs.listado.cargarUsuarioAListado(usuario)
        },
        actualizarUsuarioEnListado( usuario ) {
            this.$refs.listado.actualizarUsuarioEnListado(usuario)
        }
    }
}
</script>