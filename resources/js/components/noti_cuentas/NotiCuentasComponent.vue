<template>
    <div>    
        <Listado 
            ref="listado"
            @gestionar="mostrarModalFormulario"
        />

        <ModalFormulario 
            :monedas="obtenerListaMonedas"
            ref="modal_formulario"
            @creado-actualizado="agregarAListado"
        />
    </div>
</template>

<script>
import Listado from './Listado.vue'
import ModalFormulario from './ModalFormulario'
import NotiMoneda from '../../services/NotiMoneda'

export default {
    props: ['usuario'],
    components: {
        Listado,
        ModalFormulario
    },
    data() {
        return {
            en_edicion: null,
            monedas: [],
        }
    },
    computed: {
        obtenerListaMonedas() {
            if( this.monedas.length > 0 ) return this.monedas
        }
    },
    created(){
        NotiMoneda.obtenerListado( true )
                .then( response => {
                    this.monedas = response
                })
    },
    methods: {
        mostrarModalFormulario( row = null ) {
            const rowEditar = ( row !== null ) ? row : null

            //console.log( rowEditar )
            this.$refs.modal_formulario.mostrarModal( rowEditar );
        },
        agregarAListado( row ) {
            this.$refs.listado.agregarAListado(row)
        },
    }
}
</script>