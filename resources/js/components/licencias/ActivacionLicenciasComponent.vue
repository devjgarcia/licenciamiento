<template>
    <div>
        <b-row>
            <b-col cols="12">
                <b-card>
                    <template #header>
                        <b-row>
                            <b-col md="4" sm="12" xl="4">
                                <b-form-input v-model="input_buscar" placeholder="Buscar..."></b-form-input>
                            </b-col>
                            
                            <b-col md="4" sm="12" xl="4" class="text-lg-center">
                                <b-form-group
                                    label=""
                                    v-slot="{ ariaDescribedby }"
                                    >
                                    <b-form-radio-group
                                        id="btn-radios-2"
                                        v-model="tipo_busqueda"
                                        :options="options_tipo_busqueda"
                                        :aria-describedby="ariaDescribedby"
                                        button-variant="outline-primary"
                                        size="sm"
                                        name="radio-btn-outline"
                                        buttons
                                        @change="actualizarListado()"
                                    ></b-form-radio-group>
                                    </b-form-group>
                            </b-col>

                            <b-col md="4" sm="12" xl="4" class="text-lg-right">
                                <b-form-checkbox 
                                    v-model="reload_time" 
                                    name="check-button" 
                                    switch 
                                    size="md"
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
                        :fields="obtenerCamposTabla" 
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

                        <template #cell(selected)="data">
                            <b-form-checkbox 
                                :value="data.item"
                                v-model="marcadas" 
                                @change="agregarLicenciaActivar(data.item)"
                                size="lg"></b-form-checkbox>
                        </template>

                        <template #cell(vencimiento)="data">
                            <span>
                                {{ data.item.vencimiento }}
                            </span>
                        </template>
                        
                        <template #cell(estado)="data">
                            <b-badge 
                                :variant="data.item.class_estado"
                            >
                                {{ data.item.estado_licencia }}
                            </b-badge>
                        </template>

                        <template #cell(datos_pago)="data">
                            <span v-bind:id="'nppago_'+data.item.codnotipago" v-if="data.item.codnotipago !== null && data.item.codnotipago !== undefined">
                                <b>{{ data.item.codnotipago }}</b>
                            </span>
                            <span v-else>
                                <b>--</b>
                            </span>
                            <b-button
                                v-bind:id="'btn_popover_nppago_'+data.item.codnotipago"
                                v-if="data.item.codnotipago !== null && data.item.codnotipago !== undefined"
                                variant="primary"
                                title="Ver detalles de pago"
                                @click="actualizarEstadoPago( data.item )"
                            >
                                <i class="fa fa-eye"></i>
                            </b-button>
                            <b-popover 
                                v-bind:target="'btn_popover_nppago_'+data.item.codnotipago" 
                                triggers="click" 
                                placement="auto"
                                v-if="data.item.codnotipago !== null"
                            >
                                <template #title>
                                    {{ data.item.codnotipago }}
                                    <b-button @click="onClose( 'btn_popover_nppago_'+data.item.codnotipago )" class="close" aria-label="Close">
                                        <span class="d-inline-block" aria-hidden="true">&times;</span>
                                    </b-button>
                                </template>
                                <b>Fecha de Pago: </b>{{ data.item.fecha_pago }} <br>
                                <b>Cuenta: </b>{{ data.item.nombre_cuenta }} <br>
                                <b>Tipo de Pago: </b>{{ data.item.tippago_nombre }} <br>
                                <b>Moneda: </b>{{ data.item.codigo_moneda }} <br>
                                <b>Monto: </b>{{ data.item.monto }} <br>
                                <b>Comprobante: </b> <a target="__blank" :href="data.item.comprobante">Ver imagen amplia</a> <br><br>

                                <img 
                                    :src="data.item.comprobante" 
                                    :alt="data.item.codnotipago" 
                                    style="width: 100%; height: auto; max-height: 350px">

                            </b-popover>
                        </template>

                        <template #cell(datos_contacto)="data">
                            <b-button
                                v-bind:id="'btn_popover_contact_'+data.item.codigo"
                                variant="secondary"
                                title="Ver datos de Contacto"
                            >
                                <i class="fa fa-address-book"></i>
                            </b-button>
                            <b-popover 
                                v-bind:target="'btn_popover_contact_'+data.item.codigo" 
                                triggers="click" 
                                placement="auto"
                            >
                                <template #title>
                                    Datos de Contacto
                                    <b-button @click="onCloseContact( 'btn_popover_contact_'+data.item.codigo )" class="close" aria-label="Close">
                                        <span class="d-inline-block" aria-hidden="true">&times;</span>
                                    </b-button>
                                </template>
                                <b>Persona: </b>{{ data.item.persona }} <br>
                                <b>Correo: </b>{{ data.item.correo }} <br>
                                <b>Teléfono: </b>{{ data.item.telefono }} <br>
                                
                            </b-popover>
                        </template>

                    </b-table>
                    <b-col md="12" sm="12" xl="12" class="text-center">
                        <b-button 
                            class="btn-sm" 
                            variant="success" 
                            @click="activarSeleccionadas()"
                            :disabled="verificaSeleccionadas"
                        >
                            <i class="fa fa-check-double"></i> 
                            Activar Seleccionadas
                        </b-button>
                        <b-button 
                            class="btn-sm" 
                            variant="secondary" 
                            @click="quitarSeleccionadas"
                            v-if="!verificaSeleccionadas"
                        >
                            <i class="fa fa-times"></i> 
                            Desmarcar Seleccionadas
                        </b-button>
                        
                    </b-col>
                    <div class="mt-3">
                        <b-pagination
                        align="center"
                        v-model="pagina_actual"
                        :total-rows="obtenerCantidadFilas"
                        :per-page="por_pagina"
                        class="text-center"
                        ></b-pagination>
                        <p>
                            Total de Solicitudes: {{ obtenerCantidadFilas }}
                        </p>
                    </div>
                    <div class="mt-3" v-if="this.reload_time">
                        <p>
                            Se actualiza cada 1 min.
                        </p>
                    </div>
                </b-card>
            </b-col>
        </b-row>

        <ModalExtenderVencimiento
            ref="modal_extender_vencimiento"
            @vencimiento_actualizado="actualizarVencimientoEnListado"
        />

        <ModalDetalleActivacion
            ref="modal_detalle_activa"
            @activar="activacionLicencias"
        />
    </div>
</template>

<script>
import licencias from '../../services/Licencias'
import Mensajes from '../../services/Mensajes'

import ModalExtenderVencimiento from './ModalExtenderVencimiento'
import ModalDetalleActivacion from './ModalDetalleActivacion'

export default {
    props: ['listado_licencias', 'usuario'],
    components: {
        ModalExtenderVencimiento,
        ModalDetalleActivacion
    },
    data() {
        return {
            cargando: true,
            listado: [],
            input_buscar: '',
            por_pagina: 20,
            pagina_actual: 1,
            campos_tabla: null,

            licencias_activar: [],
            marcadas: [],
            tipo_busqueda: 1,
            options_tipo_busqueda: [
                {text: 'Activaciones', value: 1},
                {text: 'Recargas', value: 2}
            ],

            popoverShow: false,
            popoverContact: false,
            reload_time: false,
            interval: null,
        }
    },
    created(){
        console.log( this.listado_licencias )
        this.campos_tabla = licencias.obtenerCabeceraTablaPorActivar()
        this.listado      = this.listado_licencias
        this.cargando     = false
    },
    computed: {
        obtenerCamposTabla() {
            if( this.campos_tabla !== null ){
                return this.campos_tabla
            }
        },
        obtenerListadoLicencias() {
            return this.listado
        },
        obtenerCantidadFilas() {
            return this.listado.length
        },
        verificaSeleccionadas() {
            return (this.licencias_activar.length <= 0)
        },
        obtenerRoleUsuario() {
            return this.usuario.role_id
        }
    },
    watch: {
            reload_time(newVal, old) {

                if( newVal ){
                    this.interval = setInterval(() => {
                        this.actualizarListado()
                     }, 60000)                    
                }
                else{
                    clearInterval(this.interval)
                }
            }

        },
    methods: {
        async actualizarListado() {
            this.cargando = true
            await licencias.obtenerLicenciasXActivar( this.tipo_busqueda )
                        .then( response => this.listado = response );
            this.cargando = false
        },
        async activacionDefault( licencia ) {
            const datos = {
                licencias: [licencia.codigo],
                massive: 0
            }

            this.activacionLicencias(datos)
        },
        async activarSeleccionadas() {
            
            const licencias = this.licencias_activar;

            this.$refs.modal_detalle_activa.mostrarModal( licencias )
        },
        seleccionaLicencia(items) {
            this.licencias_activar = items
        },
        agregarLicenciaActivar() {
            this.licencias_activar = this.marcadas
        },
        seleccionarTodas() {
            this.licencias_activar = this.listado
            //this.$refs.table.selectAllRows()
        },
        quitarSeleccionadas() {
            this.licencias_activar = []
            //this.$refs.table.clearSelected()
        },
        obtenerMensajeConfirmacion( licencia = null ) {

            if( licencia === null ){
                let licen = ``
                this.licencias_activar.forEach(lice => licen += `${lice.codigo}, `)
                return `Se activarán las siguientes licencias: \n\n ${licen.trim(',')}`
            }
            else{
                return `¿Está seguro de activar esta Licencia?`
            }
        },
        async activacionLicencias( datos ) {
            const fData = {
                licencias: datos.licencias,
                tipo_activa: this.tipo_busqueda,
            }
            await axios.post(`/activate_lice`, fData)
                        .then( response => { 

                            Mensajes.success(`Operación exitosa`);
                            this.marcadas = []
                            this.licencias_activar = []
                            this.actualizarListado()

                        })
                        .catch( error => Mensajes.parsearErrores(error) )
        },
        extenderVencimiento( licencia ) {
            const lice = {
                id: licencia.id,
                codigo: licencia.codigo,
                vencimiento: licencia.vencimiento,
                fecha_vence: '',
                observacion: ''
            }

            this.$refs.modal_extender_vencimiento.mostrarModal( lice, 2 )
        },

        actualizarVencimientoEnListado( licencia ){
            const pos = this.listado.findIndex( f => f.codigo === licencia.codigo )

            this.listado[pos].vencimiento = licencia.vencimiento
            this.listado[pos].vencimiento_format = licencia.vencimiento_format
        },

        async actualizarEstadoPago( pago ) {
            
            if( pago.estatus_pago !== undefined && pago.estatus_pago == 0 ){
                await axios.put(`/notipago/${pago.codnotipago}`, pago)
                    .then(response => {})
                    .catch(error => Mensajes.parsearErrores(error))
            }
        },

        onClose( id ) {
            document.getElementById( id ).click()
        },

        onCloseContact( id ) {
            document.getElementById( id ).click()

        },
    }
}
</script>