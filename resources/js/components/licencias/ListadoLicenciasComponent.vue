<template>
    <div>
        <b-row>
            <b-col cols="12">
                <b-card>
                    <template #header>
                        <b-row>
                            <b-col md="3" sm="12" xl="3">
                                <b-form-input v-model="input_buscar" placeholder="Buscar..."></b-form-input>
                            </b-col>
                            <b-col md="3" sm="12" xl="3">
                                <b-button
                                    variant="primary"
                                    ref="boton_act"
                                    @click="obtenerLicenciasAllType"
                                >
                                    Actualizar Listado
                                </b-button>
                            </b-col>
                            <b-col md="6" sm="12" xl="6" class="text-right">
                                <b-form-checkbox 
                                    v-model="actualizacion_continua" 
                                    name="check-button" 
                                    switch 
                                    size="lg"
                                >
                                    Actualización continua
                                </b-form-checkbox>
                            </b-col>
                        </b-row>
                    </template>
                    <b-table 
                        striped 
                        hover 
                        :items="obtenerListadoLicencias" 
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
                        
                        <template #cell(estado)="data">
                            <b-badge 
                                :variant="'danger'"
                                v-if="isVencidas"
                            >
                                Vencida
                            </b-badge>
                            <b-badge 
                                :variant="'primary'"
                                v-else-if="isDemo"
                            >
                                Demo
                            </b-badge>
                            <b-badge 
                                :variant="data.item.class_estado"
                                v-else
                            >
                                {{ data.item.estado_licencia }}
                            </b-badge>
                        </template>

                        <template #cell(vencimiento)="data">
                            <b class="text-warning" v-if="isVencimiento">
                                {{ data.item.vencimiento_format }}
                            </b>
                            <b class="text-danger" v-else-if="isVencidas">
                                {{ data.item.vencimiento_format }}
                            </b>
                            <span v-else>
                                {{ data.item.vencimiento_format }}
                            </span>
                        </template>

                        <template #cell(actions)="data">
                            <b-button 
                                size="sm" 
                                variant="primary"
                                @click="data.toggleDetails"
                            >
                                <i 
                                    v-if="data.detailsShowing"
                                    class="fa fa-eye-slash"
                                    title="Mostrar otros detalles" 
                                ></i>
                                <i 
                                    v-else
                                    class="fa fa-eye" 
                                    title="Ocultar detalles" 
                                ></i>
                            </b-button>
                            <button class="btn btn-sm btn-default" v-if="obtenerTipoEstado === 1" @click="editarDatosProfit(data.item)">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button 
                                class="btn btn-sm btn-success" 
                                v-if="(obtenerTipoEstado === 1 || obtenerTipoEstado === 2) && (obtenerRoleUsuario === 1 || obtenerRoleUsuario === 2)" 
                                @click="extenderVencimiento(data.item)"
                                title="Extender Vencimiento"
                            >
                                <i class="fa fa-calendar-plus"></i>
                            </button>
                            <button 
                                class="btn btn-sm btn-success" 
                                v-if="(obtenerTipoEstado === 3) && (obtenerRoleUsuario === 1 || obtenerRoleUsuario === 2 || obtenerRoleUsuario === 3)" 
                                @click="confirmarReactivar(data.item)"
                                title="Reactivar Licencia"
                                :disabled="obtenerLiceUpdate(data.item.codigo)"
                            >
                                <i class="fa fa-spinner fa-spin" v-if="obtenerLiceUpdate(data.item.codigo)"></i>
                                <i class="fa fa-clipboard-check" v-else></i>
                            </button>
                        </template>

                        <template #row-details="row">
                            <b-card>
                                <b-row class="mb-2">
                                    <b-col sm="12" md="4" class="">
                                        <b>Tipo de Producto: </b>
                                        {{ row.item.tipo_producto }}
                                    </b-col>
                                    <b-col sm="12" md="4" class="">
                                        <b>Correo: </b>
                                        {{ row.item.correo }}
                                    </b-col>
                                    <b-col sm="12" md="4" class="">
                                        <b>Telefono: </b>
                                        {{ row.item.telefono }}
                                    </b-col>
                                    <b-col sm="12" md="4" class="">
                                        <b>País: </b>
                                        {{ row.item.codigo_pais }} - {{ row.item.nombre_pais }}
                                    </b-col>
                                </b-row>
                            </b-card>
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
                            Total de Licencias: {{ obtenerCantidadFilas }}
                        </p>
                    </div>
                </b-card>
            </b-col>
        </b-row>

        <ModalFormDatosLicencia 
            ref="modal_form"
            @licencia_actualizada="actualizarLicenciaEnListado"
        />

        <ModalExtenderVencimiento
            ref="modal_extender_vencimiento"
            @vencimiento_actualizado="actualizarVencimientoEnListado"
        />
    </div>
</template>
<script>
    import axios from 'axios'
    import Mensajes from '../../services/Mensajes'
    import licencias from '../../services/Licencias'
    import ModalFormDatosLicencia from './ModalFormDatosLicencia'
    import ModalExtenderVencimiento from './ModalExtenderVencimiento'

    import ModalDetallesLicencia from './ModalDetallesLicencia'

    export default {
        props: ['estado', 'listado_lice', 'vencimiento', 'usuario'],
        components: {
            ModalDetallesLicencia,
            ModalFormDatosLicencia,
            ModalExtenderVencimiento,
        },
        data () 
        {
            return {
                input_buscar: '',
                campos_tabla: licencias.obtenerCabeceraTabla(),
                licencias: [],
                cargando: true,

                pagina_actual: 1,
                por_pagina: 20,
                actualizacion_continua: false,
                intervalo: null,
                estado_actual: 0,

                licencias_update: [],
            }
        },
        watch: {
            actualizacion_continua(newVal, old) {

                if( newVal ){
                    let ref = this.$refs
                    this.intervalo = setInterval(() => {
                        this.obtenerLicenciasAllType(2)
                     }, 5000)                    
                }
                else{
                    clearInterval(this.intervalo)
                }
            }

        },
        computed:{
            obtenerListadoLicencias() {
                return this.licencias
            },
            obtenerCantidadFilas() {
                return this.licencias.length
            },
            isVencimiento() {
                return (this.vencimiento === 1)
            },
            isVencidas(){
                return (this.estado === 3)
            },
            isDemo(){
                return (this.estado === 2)
            },
            obtenerTipoEstado() {
                return this.estado
            },
            obtenerRoleUsuario() {
                return this.usuario.role_id ?? 0
            },
            
            obtenerLicenciasUpdate() {
                let texto = this.licencias_update.map( l => { return ` ${l.codigo},` })[0]

                return texto.substring( 0, texto.length - 1 )
            }
        },
        created() 
        {
            this.licencias = this.listado_lice
            this.cargando = false
        },
        methods: {
            async obtenerLicenciasAllType( load = 1 ) {
                this.cargando = true
                await licencias.obtenerLicenciasAllType(this.estado).then( response => {
                    this.licencias = response
                })

                this.cargando = false
            },
            async buscarLicencias(){
                this.cargando = true
                await licencias.obtenerLicencias(this.estado).then( response => {
                    this.licencias = response
                })
                this.cargando = false
            },
            editarDatosProfit( licencia ) {
                this.$refs.modal_form.mostrarModal(licencia)
            },
            actualizarLicenciaEnListado( licencia ) {
                const pos = this.licencias.findIndex( f => f.id === licencia.id )

                this.licencias[pos] = licencia
            },
            extenderVencimiento( licencia ) {
                const lice = {
                    id: licencia.id,
                    codigo: licencia.codigo,
                    vencimiento: licencia.vencimiento,
                    fecha_vence: '',
                    observacion_admin: '',
                    observacion_demo_ext: ''
                }

                this.$refs.modal_extender_vencimiento.mostrarModal( lice, this.estado )
            },
            actualizarVencimientoEnListado( licencia ){
                const pos = this.licencias.findIndex( f => f.codigo === licencia.codigo )

                this.licencias[pos].vencimiento = licencia.vencimiento
                this.licencias[pos].vencimiento_format = licencia.vencimiento_format
            },
            obtenerEstadoActual() {
                return this.estado_actual
            },

            async confirmarReactivar( licencia = null ) {

                const descrip = `¿Está seguro de reactivar la Licencia ${licencia.codigo}?`

                this.$bvModal.msgBoxConfirm(descrip, {
                    title: 'Confirme esta operación',
                    size: 'md',
                    buttonSize: 'sm',
                    okVariant: 'success',
                    okTitle: 'Sí, reactivar',
                    cancelTitle: 'No',
                    footerClass: 'p-2',
                    hideHeaderClose: false,
                    centered: true
                })
                .then(value => {                
                    if( value === true ){
                        this.licencias_update.push( licencia )
                        this.reactivacionLicencia( licencia );
                    }
                })
                .catch(err => {})
            },
            async reactivacionLicencia( licencia ) {
                //licencias que han vencido
                const datos = {
                    licencias: [{codigo: licencia.codigo, correo: licencia.correo, empresa: licencia.empresa}],
                    tipo_activa: 1,
                    massive: 0,
                    estado: 3
                }

                await axios.post(`/activate_lice`, datos)
                            .then( response => { 

                                response.data.licencias.forEach( item => {
                                    const posicion = this.licencias.findIndex( f => f.codigo === item.codigo );
                                    if( posicion >= 0 ) this.licencias.splice( posicion, 1 );

                                    const posicion_udp = this.licencias_update.findIndex( f => f.codigo === item.codigo );
                                    if( posicion_udp >= 0 ) this.licencias_update.splice( posicion_udp, 1 );
                                });

                                Mensajes.success(`Licencia ha sido ractivada`);
                            })
                            .catch( error => Mensajes.parsearErrores(error) )
            },

            obtenerLiceUpdate( codigo ) {
                const resp = this.licencias_update.findIndex( l => l.codigo === codigo)

                return (resp >= 0) ? true : false
            },
        }
    }
</script>
