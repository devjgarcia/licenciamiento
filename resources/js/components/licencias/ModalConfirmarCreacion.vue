<template>
    <div>
        <b-modal 
            id="modal-confirma" 
            size="xl" 
            scrollable 
            no-fade
            no-close-on-esc
            no-close-on-backdrop
            title="Verifique los datos"
            ref="modal_confirma"
            @ok="aceptar"
        >
            <b-row>
                <b-col md="10" offset-md="1">
                    <ul class="list-group">
                        <li class="list-group-item" v-for="(dato, key) in lice_datos" v-bind:key="key">
                            <b>{{ dato.key }}: </b> {{ dato.dato }}
                        </li>
                    </ul>
                </b-col>
            </b-row>

            <template #modal-footer="{ ok, cancel }">                
                <b-button 
                    :variant="`success`" 
                    @click="ok()"
                >
                    Sí, crear Licencia
                </b-button>
                
                <b-button variant="default" @click="cancel()">
                    Cancelar
                </b-button>
            </template>
        </b-modal>
    </div>
</template>

<script>
export default {
    data() {
        return {
            lice_datos: []
        }
    },
    methods: {
        mostrarModal( datos ){
            this.lice_datos = datos;
            this.$refs.modal_confirma.show()
        },
        aceptar() {
            this.$emit('aceptar', true)
            this.lice_datos = []
        },
    }
}
</script>