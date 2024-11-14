<template>
    <div>
        <validation-observer ref="observer" v-slot="{ invalid }">
            <b-modal
                id="modal_formulario"
                ref="modal_formulario"
                no-fade
                no-close-on-esc
                no-close-on-backdrop
                size="md"
                @ok.prevent="actualizarDatos"
            >
                <template #modal-header="">
                    <h5>
                        Actualizar Licencia
                    </h5>
                </template>
                <b-overlay id="overlay-background-photo" :show="guardando || buscando_lice" rounded="md">
                    <b-row v-if="buscando_lice== false">
                        <b-col md="10" offset-md="1">
                            <ValidationProvider 
                                :rules="{required: false}" 
                                v-slot="{ errors, valid }" 
                                name="Servidor Profit"
                            >
                                <b-form-group label="Servidor Profit" class="py-2">
                                    <b-form-input 
                                        type="text"
                                        v-model="licencia.profitserver"
                                        :state="valid" 
                                    />
                                    <div class="invalid-feedback d-block">
                                        <span>{{ errors[0] }}</span>
                                    </div>
                                </b-form-group>
                            </ValidationProvider>

                            <ValidationProvider 
                                :rules="{required: false}" 
                                v-slot="{ errors, valid }" 
                                name="Base de Datos Profit"
                            >
                                <b-form-group label="Base de Datos Profit" class="py-2">
                                    <b-form-input 
                                        type="text"
                                        v-model="licencia.profitdb"
                                        :state="valid" 
                                    />
                                    <div class="invalid-feedback d-block">
                                        <span>{{ errors[0] }}</span>
                                    </div>
                                </b-form-group>
                            </ValidationProvider>

                            <ValidationProvider 
                                :rules="{required: false}" 
                                v-slot="{ errors, valid }" 
                                name="Usuario Profit"
                            >
                                <b-form-group label="Usuario Profit" class="py-2">
                                    <b-form-input 
                                        type="text"
                                        v-model="licencia.profituser"
                                        :state="valid" 
                                    />
                                    <div class="invalid-feedback d-block">
                                        <span>{{ errors[0] }}</span>
                                    </div>
                                </b-form-group>
                            </ValidationProvider>

                            <ValidationProvider 
                                :rules="{required: false}" 
                                v-slot="{ errors, valid }" 
                                name="Contraseña Profit"
                            >
                                <b-form-group label="Contraseña Profit" class="py-2">
                                    <b-form-input 
                                        type="password"
                                        v-model="licencia.profitpass"
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
                profitserver: '',
                profitdb: '',
                profituser: '',
                profitpass: '',
            },

            guardando: false,
            buscando_lice: true,
        }
    },
    methods: {
        mostrarModal( licencia ){
            this.limpiarForm()
            this.licencia = licencia

            this.$refs.modal_formulario.show();
            this.getDatosActualizar()
        },
        async getDatosActualizar() {
            this.buscando_lice = true
            await axios.get(`/getLiceAct?sm=${this.licencia.codigo}&mail=${this.licencia.correo}`)
                    .then( response => {
                        const datos = response.data
                        
                        this.licencia.profitserver = datos.profitserver
                        this.licencia.profitdb     = datos.profitdb
                        this.licencia.profituser   = datos.profituser
                        this.licencia.profitpass   = datos.profitpass
                    })
                    .catch( error => {
                        Mensajes.parsearErrores( error )
                    })
            this.buscando_lice = false
        },
        async actualizarDatos() {
            this.guardando = true
            await axios.put(`/licencia/${this.licencia.id}`, this.licencia)
                        .then(response => {
                            Mensajes.success(`Datos actualizados`)
                            this.$refs.modal_formulario.hide();
                            this.$emit('licencia_actualizada', response.data.licencia)
                        })
                        .catch(error => Mensajes.parsearErrores(error))
            this.guardando = false
        },
        limpiarForm() {
            this.licencia = {
                id: 0,
                codigo: '',
                profitserver: '',
                profitdb: '',
                profituser: '',
                profitpass: '',
            }
        }
    }
}
</script>