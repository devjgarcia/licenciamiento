<template>
    <div>
        <b-row class="py-3">
            <b-col md="10" offset-md="1" class="mt-2">
                <b-input-group size="lg">
                    
                    <template #append>
                        <b-button variant="primary" @click="buscarLicencias" :disabled="buscando">
                            <b v-if="buscando">
                                <i class="fas fa-spinner fa-spin"></i>
                            </b>
                            <b v-else>
                                <i class='fa fa-search'></i>
                                Buscar
                            </b>
                        </b-button>
                    </template>

                    <b-form-input
                        placeholder="Buscar Licencia por Codigo, Correo, Persona de Contacto"
                        v-model="buscar"></b-form-input>
                </b-input-group>
            </b-col>
            <b-col class="table-responsive" v-if="obtenerLicencias">

            </b-col>
        </b-row>
    </div>
</template>

<script>
import Mensajes from '../../services/Mensajes'
import Licencia from '../../services/Licencias'

export default {
    data() {
        return {
            buscar: '',
            licencias: [],
            buscando: true
        }
    },
    computed: {
        obtenerLicencias() {
            if( this.licencias.length > 0 ){
                return this.licencias
            }
        }
    },

    methods: {
        async buscarLicencias() {
            this.buscando = true

            const datos = {
                busca: this.buscar
            }

            await axios.post(`/busca-lice`, datos)
                        .then( response => {
                            
                        })

            this.buscando = false
        }
    }
}
</script>