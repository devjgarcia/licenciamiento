<template>
    <div>    
        <Listado 
            ref="listado"
            @gestionar="mostrarModalFormulario"
        />

        <ModalFormulario 
            ref="modal_formulario"
            @creado-actualizado="agregarAListado"
        />
    </div>
</template>

<script>
import Listado from './Listado.vue'
import ModalFormulario from './ModalFormulario'

export default {
    props: ['usuario'],
    components: {
        Listado,
        ModalFormulario
    },
    data() {
        return {
            en_edicion: null,
        }
    },
    created(){
        
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