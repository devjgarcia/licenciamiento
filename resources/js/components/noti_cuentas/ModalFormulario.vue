<template>
    <div>
        <validation-observer ref="observer" v-slot="{ invalid }">
            <b-modal
                id="modal_formulario"
                ref="modal_formulario"
                no-fade
                no-close-on-esc
                no-close-on-backdrop
                size="xl"
                @ok.prevent="guardar"
            >
                <template #modal-header="">
                    <h5>
                        Cuenta de Pago
                    </h5>
                </template>
                <b-overlay id="overlay-background" :show="guardando" rounded="md">
                    <b-row>

                        <b-col md="8" offset-md="2">
                            <ValidationProvider 
                                :rules="{required: true, max: 100}" 
                                v-slot="{ errors, valid }" 
                                name="Código"
                            >
                                <b-form-group label="Código" class="py-2">
                                    <b-form-input 
                                        type="text"
                                        v-model="cuenta.codigo"
                                        :state="valid" 
                                    />
                                    <div class="invalid-feedback d-block">
                                        <span>{{ errors[0] }}</span>
                                    </div>
                                </b-form-group>
                            </ValidationProvider>
                        </b-col>

                        <b-col md="8" offset-md="2">
                            <ValidationProvider 
                                :rules="{required: true, regex: /^[a-zA-Z0-9\s]+$/}" 
                                v-slot="{ errors, valid }" 
                                name="Nombre"
                            >
                                <b-form-group label="Nombre" class="py-2">
                                    <b-form-input 
                                        type="text"
                                        v-model="cuenta.nombre"
                                        :state="valid" 
                                    />
                                    <div class="invalid-feedback d-block">
                                        <span>{{ errors[0] }}</span>
                                    </div>
                                </b-form-group>
                            </ValidationProvider>
                        </b-col>

                        <b-col md="8" offset-md="2">
                            <ValidationProvider 
                                :rules="{required: true}" 
                                v-slot="{ errors, valid }" 
                                name="Rol"
                            >
                                <b-form-group label="Tipo de Moneda" class="py-2">
                                    <b-form-select 
                                        v-model="cuenta.tipo_moneda"
                                        :state="valid"
                                    >
                                        <b-form-select-option :value="null">Seleccione</b-form-select-option>
                                        <b-form-select-option 
                                            v-for="(moneda, key) in obtenerTipoMoneda" 
                                            v-bind:key="key"
                                            :value="moneda.codigo"
                                        >
                                            {{ moneda.nombre }}
                                        </b-form-select-option>
                                    </b-form-select>

                                    <div class="invalid-feedback d-block">
                                        <span>{{ errors[0] }}</span>
                                    </div>
                                </b-form-group>
                            </ValidationProvider>
                        </b-col>

                        <b-col md="8" offset-md="2" v-if="guardar_o_editar !== 1">
                            <ValidationProvider 
                                :rules="{required: true}" 
                                v-slot="{ errors, valid }" 
                                name="Estatus"
                            >
                                <b-form-group label="Estatus" class="py-2">
                                    <b-form-select 
                                        v-model="cuenta.estatus"
                                        :state="valid"
                                    >
                                        <b-form-select-option :value="null">Seleccione</b-form-select-option>
                                        <b-form-select-option :value="1">Activo</b-form-select-option>
                                        <b-form-select-option :value="0">Inactivo</b-form-select-option>
                                    </b-form-select>

                                    <div class="invalid-feedback d-block">
                                        <span>{{ errors[0] }}</span>
                                    </div>
                                </b-form-group>
                            </ValidationProvider>
                        </b-col>

                    </b-row>
                </b-overlay>
                
                <template #modal-footer="{ ok, cancel }">
                    <b-button type="button" size="md" variant="success" @click="ok()" :disabled="invalid || guardando">
                        {{ obtenerAccion }}
                    </b-button>
                    <b-button type="button" size="md" variant="secondary" @click="cancel()">
                        Cancelar
                    </b-button>
                </template>
            </b-modal>
        </validation-observer>
    </div>
</template>

<script>
import Mensajes from '../../services/Mensajes'
import NotiCuentas from '../../services/NotiCuentas'

export default {
    props: ['monedas'],
    computed: {
        
        obtenerAccion() {
            return (this.guardar_o_editar === 1) ? `Guardar` : `Actualizar`
        },

        obtenerTipoMoneda(){
            if( this.monedas !== undefined && this.monedas.length > 0 ) return this.monedas
        }

    },

    data(){
        return {
            cuenta: {
                id: 0,
                codigo: '',
                nombre: '',
                tipo_moneda: '',
                estatus: 1,
            },

            guardar_o_editar: 1,
            guardando: false,
        }
    },

    methods: {

        mostrarModal( row = null ){

            if( row.id !== undefined && row.id > 0 ){
                this.cuenta = row
                this.guardar_o_editar = 2
            }
            this.$refs.modal_formulario.show()
        },

        async guardar() {
            this.guardando = true

            await NotiCuentas.guardar( this.cuenta )
                    .then( response => {
                        Mensajes.success(`${response.nombre} guardado correctamente`)
                        this.$emit('creado-actualizado', response)
                    })

            this.limpiarFormulario()
            this.guardando = false
            this.$refs.modal_formulario.hide()
        },

        limpiarFormulario() {
            this.cuenta = {
                id: 0,
                codigo: '',
                nombre: '',
                tipo_moneda: '',
                estatus: 1,
            }

            this.guardar_o_editar = 1
        },

    },

}
</script>