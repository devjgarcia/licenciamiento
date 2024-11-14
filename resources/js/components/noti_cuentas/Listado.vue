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

                                <b-button
                                    variant="success"
                                    @click="gestionar"
                                >
                                    <i class="fa fa-plus"></i> 
                                    Agregar Cuenta de Pago
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
                        
                        <template #cell(actions)="data">
                            <b-button variant="primary" class="btn-sm" @click="gestionar(data.item)">
                                <i class="fa fa-edit"></i>
                            </b-button>
                        </template>

                        <template #cell(tipo_moneda)="data">
                            <b-badge variant="info" v-if="data.item.noti_moneda !== undefined && data.item.noti_moneda.estatus == 1">
                                <i class="fa fa-money-bill"></i> 
                                {{ data.item.tipo_moneda }}
                            </b-badge>
                            <b-badge variant="secondary" v-else>
                                <i class="fa fa-money-bill"></i> 
                                {{ data.item.tipo_moneda }}
                            </b-badge>
                        </template>

                        <template #cell(estatus)="data">
                            <b-badge v-if="data.item.estatus === 1" variant="success">
                                Activo
                            </b-badge>
                            <b-badge v-else variant="danger">
                                Inactivo
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
import NotiCuentas from '../../services/NotiCuentas'
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
            campos_tabla: NotiCuentas.obtenerCabeceraTabla(),
        }
    },

    methods: {
        
        async obtenerLista() {
            this.cargando = true
            await NotiCuentas.obtenerListado()
                        .then( response => this.listado = response )
                        .catch(error => Mensajes.parsearErrores(error))
            this.cargando = false
        },

        gestionar( registro = null ) {
            this.$emit('gestionar', registro)
        },

        agregarAListado( registro ) {
            const posicion = this.listado.findIndex( f => f.id === registro.id ) ?? false;

            if( posicion !== undefined && posicion >= 0 ){
                this.listado[posicion] = registro
            }
            else{
                this.listado.push( registro )
            }
        },
    }
    
}
</script>
