<template>
    <div>
        
        <ListadoParametros 
            ref="listado"
            :usuario="obtenerUsuario"
            :parametros="obtenerParametros"
            @gestionar-parametro="gestionarParametro"
        />

        <ModalFormulario 
            ref="formulario"
            @nuevo-parametro="actualizarListado"

        />

    </div>
</template>

<script>
import ListadoParametros from './ListadoParametros'
import ModalFormulario from './ModalFormulario'

export default {
    props: ['parametros', 'usuario'],

    components: {
        ListadoParametros,
        ModalFormulario,
    },

    computed: {

        obtenerUsuario() {
            return this.usuario
        },

        obtenerParametros() {
            return this.parametros
        },

    },

    data() {
        return {
            
        }
    },

    methods: {
        gestionarParametro( parametro = null ){
            this.$refs.formulario.mostrarModal( parametro )
        },

        actualizarListado( parametro ) {
            
            this.$refs.listado.agregarAListado( parametro )
        },
    }
}
</script>