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
                @ok.prevent="guardarUsuario"
            >
                <template #modal-header="">
                    <h5>
                        Agregar Usuario
                    </h5>
                </template>
                <b-overlay id="overlay-background-photo" :show="guardando_foto || guardando" rounded="sm">
                    <b-row>
                        <b-col md="12" class="text-center">
                            <img width="100" height="100" v-if="url_photo" :src="url_photo" class="rounded-circle m1"/>
                        </b-col>
                    </b-row>
                </b-overlay>
                <b-overlay id="overlay-background" :show="guardando" rounded="md">
                    <b-row>
                        <b-col md="6">
                            <ValidationProvider 
                                :rules="{required: true, alpha_spaces: true}" 
                                v-slot="{ errors, valid }" 
                                name="Nombres y apellidos"
                            >
                                <b-form-group label="Nombres y apellidos" class="py-2">
                                    <b-form-input 
                                        type="text"
                                        v-model="usuario.name"
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
                                :rules="{required: true, alpha_num: true}" 
                                v-slot="{ errors, valid }" 
                                name="Doc de Identidad"
                            >
                                <b-form-group label="Doc de Identidad" class="py-2">
                                    <b-form-input 
                                        type="text"
                                        v-model="usuario.rif"
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
                                :rules="{required: true, email: true}" 
                                v-slot="{ errors, valid }" 
                                name="Correo electrónico"
                            >
                                <b-form-group label="Correo electrónico" class="py-2">
                                    <b-form-input 
                                        type="text"
                                        v-model="usuario.email"
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
                                :rules="{required: obtenerRequiredPassword}" 
                                v-slot="{ errors, valid }" 
                                name="Contraseña"
                            >
                                <b-form-group label="Contraseña" class="py-2">
                                    <b-form-input 
                                        type="password"
                                        v-model="usuario.password"
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
                                :rules="{required: true}" 
                                v-slot="{ errors, valid }" 
                                name="Rol"
                            >
                                <b-form-group label="Rol" class="py-2">
                                    <b-form-select 
                                        v-model="usuario.role_id"
                                        :state="valid"
                                    >
                                        <b-form-select-option :value="null">Seleccione</b-form-select-option>
                                        <b-form-select-option 
                                            v-for="(role, key) in obtenerRoles" 
                                            v-bind:key="key"
                                            :value="role.value"
                                        >
                                            {{ role.text }}
                                        </b-form-select-option>
                                    </b-form-select>

                                    <div class="invalid-feedback d-block">
                                        <span>{{ errors[0] }}</span>
                                    </div>
                                </b-form-group>
                            </ValidationProvider>
                        </b-col>

                        <b-col md="6" v-if="nuevo_o_edita!==1">
                            <ValidationProvider 
                                :rules="{required: true}" 
                                v-slot="{ errors, valid }" 
                                name="Estado de Usuario"
                            >
                                <b-form-group label="Estado de Usuario" class="py-2">
                                    <b-form-select 
                                        v-model="usuario.status"
                                        :state="valid"
                                    >
                                        <b-form-select-option :value="null">Seleccione</b-form-select-option>
                                        <b-form-select-option 
                                            v-for="(estado, key) in obtenerEstados" 
                                            v-bind:key="key"
                                            :value="estado.value"
                                        >
                                            {{ estado.text }}
                                        </b-form-select-option>
                                    </b-form-select>

                                    <div class="invalid-feedback d-block">
                                        <span>{{ errors[0] }}</span>
                                    </div>
                                </b-form-group>
                            </ValidationProvider>
                        </b-col>

                        <b-col md="6">
                            <ValidationProvider 
                                :rules="{required: false}" 
                                v-slot="{ errors, valid }" 
                                name="Teléfono"
                            >
                                <b-form-group label="Teléfono" class="py-2">
                                    <b-form-input 
                                        type="text"
                                        v-model="usuario.phone"
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
                                :rules="{required: false, image: true }" 
                                v-slot="{ errors, valid }" 
                                name="Foto de Perfil"
                            >
                                <b-form-group  
                                    label="Foto de Perfil:"
                                >
                                    <b-form-file
                                        v-model="usuario.file_photo"
                                        placeholder="Presiona para buscar archivo"
                                        drop-placeholder="Quitar archivo seleccionado"
                                        :state="valid"
                                        accept=".jpg,.jpeg,.png,.gif"
                                        @change="cambiarFoto"
                                    ></b-form-file>
                                    <div class="invalid-feedback d-block">
                                        <span>{{ errors[0] }}</span>
                                    </div>
                                    <small>Extensiones válidas: png, jpg, jpeg, gif</small>
                                </b-form-group>
                            </ValidationProvider>
                        </b-col>

                        <b-col md="12">
                            <ValidationProvider 
                                :rules="{required: false, max: 255}" 
                                v-slot="{ errors, valid }" 
                                name="Dirección"
                            >
                                <b-form-group label="Dirección" class="py-2">
                                    <b-form-textarea 
                                        v-model="usuario.direction"
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
import axios from 'axios'
import Mensajes from '../../services/Mensajes'
import s_usuario from '../../services/Usuarios'

export default {
    data() {
        return {
            usuario: {
                id: 0,
                name: '',
                rif: '',
                direction: '',
                phone: '',
                email: '',
                password: '',
                role_id: null,
                status: '',
                photo: '',
                file_photo: null,
            },
            url_photo: null,

            guardando: false,
            guardando_foto: false,
            nuevo_o_edita: 1,

            roles: [],
            estados: [],
        }
    },
    created() {
        s_usuario.obtenerRolesUsuario().then( response => this.roles = response )
        s_usuario.obtenerEstadosUsuario().then( response => this.estados = response )
    },
    computed: {
        obtenerRoles() {
            return this.roles
        },
        obtenerEstados() {
            return this.estados
        },
        obtenerAccion() {
            return ( this.nuevo_o_edita === 1 ) ? 'Guardar' : 'Actualizar'
        },
        obtenerRequiredPassword() {
            return (this.nuevo_o_edita === 1)
        },
    },
    methods: {
        mostrarModal( usuario = null ) {
            this.nuevo_o_edita = ( usuario !== null ) ? 2 : 1
            this.limpiarFormulario()
            
            if( this.nuevo_o_edita === 2 ) this.usuario = usuario

            if(this.nuevo_o_edita === 2) this.url_photo = usuario.photo

            this.$refs.modal_formulario.show();
        },
        async guardarUsuario() {
            this.guardando = true

            const fData = new FormData();

            fData.append('id', this.usuario.id)
            fData.append('name', this.usuario.name)
            fData.append('rif', this.usuario.rif)

            if(this.usuario.direction !== '') fData.append('direction', this.usuario.direction)
            if(this.usuario.phone !== '') fData.append('phone', this.usuario.phone)
            fData.append('email', this.usuario.email)
            fData.append('password', this.usuario.password)
            fData.append('role_id', this.usuario.role_id)
            if(this.usuario.file_photo!==null) fData.append('file_photo', this.usuario.file_photo)
            fData.append('status', this.usuario.status)

            if( this.nuevo_o_edita === 1 ){
                await axios.post(`/usuario`, fData, {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            })
                            .then( response => { 
                                this.limpiarFormulario();
                                this.$refs.modal_formulario.hide();
                                Mensajes.success(`Usuario Creado`)
                                this.$emit('usuario_creado', response.data.usuario)
                            })
                            .catch( error => Mensajes.parsearErrores(error) )
            }
            else{
                await axios.post(`/usuario/${this.usuario.id}`, fData, {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            })
                            .then( response => { 
                                this.limpiarFormulario();
                                this.$refs.modal_formulario.hide();
                                Mensajes.success(`Usuario Actualizado`)
                                this.$emit('usuario_actualizado', response.data.usuario)
                            })
                            .catch( error => Mensajes.parsearErrores(error) )
            }
            
            this.guardando = false
        },
        limpiarFormulario() {
            this.usuario = {
                id: 0,
                name: '',
                rif: '',
                direction: '',
                phone: '',
                email: '',
                password: '',
                role_id: null,
                status: '',
                photo: '',
                file_photo: null,
            }

            this.guardando = false
            this.guardando_foto = false
            this.url_photo = null
        },
        cambiarFoto( e ) {
            const file = e.target.files[0];
            this.url_photo = URL.createObjectURL(file);
        }
    }

}
</script>