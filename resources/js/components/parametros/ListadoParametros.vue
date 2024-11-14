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
                                    @click="obtenerParametros"
                                    title="Refrescar el listado"
                                >
                                    <i class="fa fa-sync"></i>
                                </b-button>

                                <b-button
                                    variant="success"
                                    @click="gestionarParametro"
                                >
                                    <i class="fa fa-plus"></i> 
                                    Agregar Parámetro
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
                        
                        <template #cell(acciones)="data">
                            <b-button variant="primary" class="btn-sm" @click="gestionarParametro(data.item)">
                                <i class="fa fa-edit"></i>
                            </b-button>
                        </template>

                        <template #cell(valor)="data">
                            <div v-if="data.item.nombre.split('_')[0] == 'VEDITOR'">
                                <i class="fa fa-code"></i> Contenido HTML
                            </div>
                            <div v-else>
                                <p>{{ data.item.valor }}</p>
                            </div>
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
                            Total de Parámetros: {{ obtenerCantidadFilas }}
                        </p>
                    </div>
                </b-card>
            </b-col>
        </b-row>
    </div>
</template>

<script>
import Parametros from '../../services/Parametros'
import Mensajes from '../../services/Mensajes'

export default {
    props: ['usuario','parametros'],

    computed: {

        obtenerCantidadFilas() {
            if( this.listado !== undefined && this.listado ){
                return this.listado.length
            }
            else {
                return 0
            }
        },

        obtenerTipoUsuaurio() {
            if( this.usuario !== undefined && this.usuario !== null ){
                return this.usuario.role_id
            }
        },

        obtenerListado() {
            if( this.listado !== undefined && this.listado.length > 0 ){
                return this.listado
            }
        },

    },

    created() {
        this.listado = this.parametros
        this.cargando = false

        console.log(this.listado)
    },

    data() {
        return {

            cargando: true,
            listado: [],
            input_buscar: '',
            por_pagina: 20,
            pagina_actual: 1,
            campos_tabla: Parametros.obtenerCabeceraTabla(),
        }
    },

    methods: {
        
        async obtenerParametros() {
            this.cargando = true
            await Parametros.obtenerParametros()
                        .then( response => this.listado = response )
                        .catch(error => Mensajes.parsearErrores(error))
            this.cargando = false
        },

        gestionarParametro( parametro = null ) {
            this.$emit('gestionar-parametro', parametro)
        },

        agregarAListado( parametro ) {
            const posicion = this.listado.findIndex( f => f.id === parametro.id ) ?? false;

            console.log(posicion)

            if( posicion !== undefined && posicion >= 0 ){
                this.listado[posicion] = parametro
            }
            else{
                this.listado.push( parametro )
            }
        },
    }
    
}
</script>
