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
                        Tipo de Pago
                    </h5>
                </template>
                <b-overlay id="overlay-background" :show="guardando" rounded="md">
                    <b-row>

                        <b-col md="8" offset-md="2">
                            <ValidationProvider 
                                :rules="{required: true, regex: /^[a-zA-Z0-9]+$/}" 
                                v-slot="{ errors, valid }" 
                                name="Código"
                            >
                                <b-form-group label="Código" class="py-2">
                                    <b-form-input 
                                        type="text"
                                        v-model="moneda.codigo"
                                        :state="valid" 
                                        placeholder="No use espacios. Ejemplo: USD"
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
                                        v-model="moneda.nombre"
                                        :state="valid" 
                                    />
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
                                        v-model="moneda.estatus"
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
import NotiMoneda from '../../services/NotiMoneda'

export default {
    computed: {
        
        obtenerAccion() {
            return (this.guardar_o_editar === 1) ? `Guardar` : `Actualizar`
        }

    },

    data(){
        return {
            moneda: {
                id: 0,
                codigo: '',
                nombre: '',
                estatus: 1,
            },

            guardar_o_editar: 1,
            guardando: false,
        }
    },

    methods: {

        mostrarModal( row = null ){

            if( row.id !== undefined && row.id > 0 ){
                this.moneda = row
                this.guardar_o_editar = 2
            }
            this.$refs.modal_formulario.show()
        },

        async guardar() {
            this.guardando = true

            await NotiMoneda.guardar( this.moneda )
                    .then( response => {
                        Mensajes.success(`${response.nombre} guardado correctamente`)
                        this.$emit('creado-actualizado', response)
                    })

            this.limpiarFormulario()
            this.guardando = false
            this.$refs.modal_formulario.hide()
        },

        limpiarFormulario() {
            this.moneda = {
                id: 0,
                codigo: '',
                nombre: '',
                estatus: 1,
            }

            this.guardar_o_editar = 1
        },

    },

}
</script>