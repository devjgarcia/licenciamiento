<template>
    <div>
        <validation-observer ref="observer" v-slot="{ invalid }">
            <b-modal
                id="modal_formulario_ext"
                ref="modal_formulario_ext"
                no-fade
                no-close-on-esc
                no-close-on-backdrop
                size="lg"
                @ok.prevent="actualizarVencimiento"
            >
                <template #modal-header="">
                    <h5>
                        {{ obtenerTipoExtension }} - Licencia: {{ obtenerCodigoLicencia }}
                    </h5>
                </template>
                <b-overlay id="overlay-vencimiento" :show="guardando" rounded="lg">
                    <b-row>
                        <b-col md="10" offset-md="1">
                            <h5 class="text-danger py-2">
                                <b>Vencimiento Actual: </b>{{ licencia.vencimiento }}
                            </h5>
                            <ValidationProvider 
                                :rules="{required: true}" 
                                v-slot="{ errors, valid }" 
                                name="Nuevo Vencimiento"
                            >
                                <b-form-group label="Nueva Fecha de Vencimiento" class="py-2 mt-3">
                                    <b-form-datepicker
                                        id="fecha_vence"
                                        locale="es"
                                        :state="valid"
                                        v-model="fecha_vence"
                                        @context="onContext"
                                    />
                                </b-form-group>
                                <div class="invalid-feedback d-block">
                                    <span>{{ errors[0] }}</span>
                                </div>
                            </ValidationProvider>

                            <ValidationProvider 
                                :rules="{required: true}" 
                                v-slot="{ errors, valid }" 
                                name="Observación"
                            >
                                <b-form-group label="Observación" class="py-2 mt-3">
                                    <b-form-textarea
                                        v-model="licencia.observacion"
                                        :state="valid" 
                                    />
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
                        Actualizar
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
import axios from 'axios'
import Mensajes from '../../services/Mensajes'

export default {
    data() {
        return {
            licencia: {
                id: 0,
                codigo: '',
                vencimiento: '',
                observacion: '',
            },

            tipo_extension: 1, // 1 -> extiende vencimiento lice activa | 2 -> extiende vencimiento demo
            fecha_seleccionada: '',
            fecha_vence: '',

            guardando: false,
        }
    },
    computed: {
        obtenerTipoExtension() {
            return (this.tipo_extension === 2) ? 'Extensión Demo' : 'Extensión de Vencimiento'
        },
        obtenerCodigoLicencia() {
            return this.licencia.codigo ?? '--'
        }
    },
    methods: {
        mostrarModal( licencia, tipo_extension ){

            this.limpiarForm()
            
            this.tipo_extension = tipo_extension
            this.licencia = licencia

            this.$refs.modal_formulario_ext.show();
        },

        async actualizarVencimiento() {
            this.guardando = true
            const datos = {
                codigo: this.licencia.codigo,
                estado: this.tipo_extension,
                vencimiento: this.fecha_seleccionada,
                observacion: this.licencia.observacion
            };
            await axios.post(`/extender_vencimiento`, datos)
                        .then(response => {
                            Mensajes.success(`Vencimiento actualizado a la licencia: ${this.licencia.codigo}`)
                            this.$refs.modal_formulario_ext.hide();
                            this.$emit('vencimiento_actualizado', response.data.licencia)
                        })
                        .catch(error => Mensajes.parsearErrores(error))
            this.guardando = false
        },
        limpiarForm() {
            this.licencia = {
                id: 0,
                codigo: '',
                vencimiento: '',
                observacion: '',
            }

            this.fecha_seleccionada = ''
            this.fecha_vence  = ''
        },
        onContext(ctx) {
            console.log( ctx )
            this.fecha_seleccionada   = ctx.selectedYMD
            this.licencia.vencimiento = ctx.selectedYMD
        }
    }
}
</script>