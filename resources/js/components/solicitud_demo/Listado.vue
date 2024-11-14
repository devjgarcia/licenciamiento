<template>
    <div>
        <b-row>
            <b-col cols="12">
                <b-card>
                    <template #header>
                        <b-row>
                            <b-col md="5" sm="12" xl="5">
                                <b-form-input v-model="input_buscar" placeholder="Buscar en este listado..."></b-form-input>
                            </b-col>
                            <b-col md="7" sm="12" xl="7" class="text-right">
                                <b-button
                                    variant="primary"
                                    ref="boton_act"
                                    @click="obtenerLista"
                                    title="Refrescar el listado"
                                >
                                    <i class="fa fa-sync"></i>
                                </b-button>
                            </b-col>
                        </b-row>
                    </template>
                    <b-table 
                        striped 
                        hover 
                        :items="obtenerListado" 
                        :fields="campos_tabla"
                        :busy="cargando"
                        :per-page="por_pagina"
                        :current-page="pagina_actual"
                        ref="table"
                        :filter="input_buscar"
                        class="table-sm"
                    >
                        <template #table-busy>
                            <div class="text-center my-2">
                                <b-spinner class="align-middle"></b-spinner>
                                <strong>Cargando...</strong>
                            </div>
                        </template>
                        
                        <template #cell(correo_env)="data">
                            <i class="fa fa-check text-success" v-if="data.item.correo_env == 1"></i>
                            <i class="fa fa-times text-danger" v-else></i>
                        </template>

                        <template #cell(completado)="data">
                            <b-badge variant="success" v-if="data.item.completado == 1 && data.item.licen.codigo !== undefined">
                                {{ data.item.licen.codigo }}
                            </b-badge>
                            <b-badge variant="danger" v-else>
                                No
                            </b-badge>
                        </template>

                    </b-table>
                    <div class="mt-3">
                        <b-pagination
                            align="center"
                            v-model="pagina_actual"
                            :total-rows="obtenerCantidadFilas"
                            :per-page="por_pagina"
                            class="text-center"
                        ></b-pagination>
                        <p>
                            Registros: {{ obtenerCantidadFilas }}
                        </p>
                    </div>
                </b-card>
            </b-col>
        </b-row>
    </div>
</template>

<script>
import SolicitudDemo from '../../services/SolicitudDemo'
import Mensajes from '../../services/Mensajes'

export default {
    computed: {

        obtenerCantidadFilas() {
            if( this.listado !== undefined && this.listado ){
                return this.listado.length
            }
            else {
                return 0
            }
        },

        obtenerListado() {
            if( this.listado !== undefined && this.listado.length > 0 ){
                return this.listado
            }
        },

    },

    created() {
        this.obtenerLista()
    },

    data() {
        return {

            cargando: true,
            listado: [],
            input_buscar: '',
            por_pagina: 20,
            pagina_actual: 1,
            campos_tabla: SolicitudDemo.obtenerCabeceraTabla(),
        }
    },

    methods: {
        
        async obtenerLista() {
            this.cargando = true
            await SolicitudDemo.obtenerListado()
                        .then( response => this.listado = response )
                        .catch(error => Mensajes.parsearErrores(error))
            this.cargando = false
        },
    }
    
}
</script>
