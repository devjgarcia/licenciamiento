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
                @ok.prevent="guardarParametro"
            >
                <template #modal-header="">
                    <h5>
                        Parámetro 
                    </h5>
                </template>
                <b-overlay id="overlay-background" :show="guardando" rounded="md">
                    <b-row>
                        <b-col md="6">
                            <ValidationProvider 
                                :rules="{required: true, regex: /^[P0-9-]+$/}" 
                                v-slot="{ errors, valid }" 
                                name="Código"
                            >
                                <b-form-group label="Código" class="py-2">
                                    <b-form-input 
                                        type="text"
                                        v-model="parametro.codigo"
                                        :state="valid" 
                                    />
                                    <div class="invalid-feedback d-block">
                                        <span>{{ errors[0] }}</span>
                                    </div>
                                </b-form-group>
                            </ValidationProvider>
                        </b-col>
                        
                        <b-col md="6">
                            <ValidationProvider 
                                :rules="{required: true, regex: /^[a-zA-Z0-9_]+$/}" 
                                v-slot="{ errors, valid }" 
                                name="Nombre"
                            >
                                <b-form-group label="Nombre" class="py-2">
                                    <b-form-input 
                                        type="text"
                                        v-model="parametro.nombre"
                                        :state="valid" 
                                    />
                                    <div class="invalid-feedback d-block">
                                        <span>{{ errors[0] }}</span>
                                    </div>
                                </b-form-group>
                            </ValidationProvider>
                        </b-col>

                        <b-col md="12">
                            <ValidationProvider 
                                :rules="{required: false, max: 60, alpha_spaces: true}" 
                                v-slot="{ errors, valid }" 
                                name="Descripción"
                            >
                                <b-form-group label="Descripción" class="py-2">
                                    <b-form-textarea 
                                        v-model="parametro.descripcion"
                                        :state="valid" 
                                        no-resize
                                        rows="2"
                                    />
                                    <div class="invalid-feedback d-block">
                                        <span>{{ errors[0] }}</span>
                                    </div>
                                </b-form-group>
                            </ValidationProvider>
                        </b-col>

                        <b-col md="12" v-if="parametro.nombre.split('_')[0] == 'VEDITOR'">
                            <ckeditor :editor="editor" v-model="parametro.valor" :config="configEditor"></ckeditor>
                            <!--<vue-html-editor name="html-editor" :model.sync="parametro.valor"></vue-html-editor>-->
                            <!--<VueEditor v-model="parametro.valor" />-->
                            <!--<vue-editor v-model="parametro.valor"></vue-editor>-->
                        </b-col>
                        <b-col md="12" v-else>
                            <ValidationProvider 
                                :rules="{required: true}" 
                                v-slot="{ errors, valid }" 
                                name="Valor"
                            >
                                <b-form-group label="Valor" class="py-2">
                                    <b-form-textarea 
                                        v-model="parametro.valor"
                                        :state="valid" 
                                        no-resize
                                        rows="3"
                                    />
                                    <div class="invalid-feedback d-block">
                                        <span>{{ errors[0] }}</span>
                                    </div>
                                </b-form-group>
                            </ValidationProvider>
                        </b-col>

                        <b-col md="6" v-if="guardar_o_editar !==1 ">
                            <ValidationProvider 
                                :rules="{required: true}" 
                                v-slot="{ errors, valid }" 
                                name="Estatus"
                            >
                                <b-form-group label="Estatus" class="py-2">
                                    <b-form-select 
                                        v-model="parametro.estatus"
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
import Parametros from '../../services/Parametros'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

export default {
    computed: {
        
        obtenerAccion() {
            return (this.guardar_o_editar === 1) ? `Guardar` : `Actualizar`
        }

    },

    data(){
        return {
            editor: ClassicEditor,
            configEditor: {},
            parametro: {
                id: 0,
                codigo: '',
                nombre: '',
                descripcion: '',
                valor: '',
                estatus: 1,
            },

            guardar_o_editar: 1,
            guardando: false,
        }
    },

    methods: {

        mostrarModal( parametro = null ){

            if( parametro.id !== undefined && parametro.id > 0 ){
                this.parametro = parametro
                this.guardar_o_editar = 2
            }
            this.$refs.modal_formulario.show()
        },

        async guardarParametro() {
            this.guardando = true

            await Parametros.guardarParametro( this.parametro )
                    .then( response => {
                        Mensajes.success(`Parámetro ${response.nombre} guardado correctamente`)
                        this.$emit('nuevo-parametro', response)
                    })

            this.limpiarFormulario()
            this.guardando = false
            this.$refs.modal_formulario.hide()
        },

        limpiarFormulario() {
            this.parametro = {
                id: 0,
                codigo: '',
                nombre: '',
                descripcion: '',
                valor: '',
                estatus: '',
            }

            this.guardar_o_editar = 1
        },

    },

}
</script>