<template>
    <div>
        <validation-observer ref="observer" v-slot="{ invalid }" v-if="!guardado">
            <b-card title="" header-tag="header" class="mt-4" footer-tag="footer" style="background: url('/images/fondo_huella_1.jpg') cover no-repeat;">
                <template #header>
                    <b-row>
                        <b-col md="3" sm="3">
                            <b-img src="/images/logo_saca3.png" style="width: 110px; height: 100px" alt="Saca"></b-img>
                        </b-col>
                        <b-col md="6" sm="6" class="text-center">
                            <h4 class="mt-4 text-color-lice-1">Formulario de Solicitud</h4>
                        </b-col>
                        <b-col md="3" sm="3" class="text-right">
                            <i class="fa fa-fingerprint text-color-lice-1 mt-2" style="font-size: 70px;"></i>
                        </b-col>
                    </b-row>
                </template>
                <b-card-text>
                    <b-overlay id="overlay-background" :show="guardando" rounded="md">
                        <b-row>
                            <b-col md="12" class="text-center">
                                <p>
                                    Completa los siguientes datos para solicitar una Demo del Sistema Sacamóvil   
                                </p>    
                            </b-col>  
                        </b-row>
                        <b-row>
                            <b-col md="12" class="mt-2">
                                <ValidationProvider 
                                    :rules="{required: true, alpha_spaces: true}" 
                                    v-slot="{ errors, valid }" 
                                    name="Nombres del Propietario de la Empresa"
                                >
                                    <b-form-group label="Nombres del Propietario de la Empresa" class="py-2">
                                        <b-form-input 
                                            type="text"
                                            v-model="solicitud.nombres"
                                            :state="valid" 
                                        />
                                        <div class="invalid-feedback d-block">
                                            <span>{{ errors[0] }}</span>
                                        </div>
                                    </b-form-group>
                                </ValidationProvider>

                                <ValidationProvider 
                                    :rules="{required: true, regex: /^[a-zA-Z0-9,.\s]+$/}" 
                                    v-slot="{ errors, valid }" 
                                    name="Nombre o Razón Social de su Empresa"
                                >
                                    <b-form-group label="Nombre o Razón Social de su Empresa" class="py-2">
                                        <b-form-input 
                                            type="text"
                                            v-model="solicitud.empresa"
                                            :state="valid" 
                                        />
                                        <div class="invalid-feedback d-block">
                                            <span>{{ errors[0] }}</span>
                                        </div>
                                    </b-form-group>
                                </ValidationProvider>

                                <ValidationProvider 
                                    :rules="{required: true, email: true}" 
                                    v-slot="{ errors, valid }" 
                                    name="Correo electrónico del Propietario"
                                >
                                    <b-form-group label="Correo electrónico del Propietario" class="py-2">
                                        <b-form-input 
                                            type="email"
                                            v-model="solicitud.correo"
                                            :state="valid" 
                                        />
                                        <div class="invalid-feedback d-block">
                                            <span>{{ errors[0] }}</span>
                                        </div>
                                        <small>Asegurese de ingresar el correo del propietario de la empresa</small>
                                    </b-form-group>
                                </ValidationProvider>

                                <VuePhoneNumberInput 
                                    v-model="solicitud.phone" 
                                    :required="true"
                                    :preferred-countries="obtenerPais"
                                    ref="input_telefono"
                                    :fetch-country="true"
                                    :show-code-on-list="true"
                                    @update="cambiarTelefono"
                                    :translations="{
                                        countrySelectorLabel: 'País',
                                        countrySelectorError: 'Selecciona el país',
                                        phoneNumberLabel: 'Número de teléfono',
                                        example: 'Ejemplo :'
                                    }"
                                />
                            </b-col>
                        </b-row>
                    </b-overlay>                    
                </b-card-text>
                <template class="text-center" #footer>
                    <b-row>
                        <b-col md="12" class="text-center">
                            <b-button
                                :variant="(invalid || guardando || !validaForm) ? `secondary` : `success`"
                                @click="confirmarSolicitud"
                                :disabled="invalid || guardando || !validaForm"
                            >
                                Solicitar
                            </b-button>
                            <a href="#" onclick="window.close()" class="btn btn-default">
                                Cancelar
                            </a>
                        </b-col>
                    </b-row>
                </template>
            </b-card>
        </validation-observer>

        <b-card title="" class="mt-5" header-tag="header" footer-tag="footer" v-else>
            <template #header>
                <b-col md="12" class="text-center">
                    <b-img src="/images/logo_saca1.png" style="width: 250px; height: 190px" alt="Saca"></b-img>
                </b-col>
            </template>
            <b-card-text>
                <b-row>
                    <b-col md="12" class="text-center">
                        <h3 class="text-success">
                            <b>Se ha procesado su solicitud de Demo.</b>
                            <br><br>
                        </h3>
                    </b-col>
                    <b-col md="12" class="text-justify mt-5 py-3">
                        <h5>
                            Por favor consulte el correo electrónico ingresado para continuar con el proceso  y poder ingresar a la plataforma.
                            <br>
                            <br>
                        </h5>
                    </b-col>

                    <b-col md="12" class="text-center mt-5 py3">
                        <h5>
                            <br>
                            Por favor cierre esta ventana de su navegador
                        </h5>
                    </b-col>
                </b-row>
            </b-card-text>
        </b-card>

        <ModalTerminos 
            ref="modal_terminos"
            @aceptar="solicitarDemo"
        />
    </div>
</template>

<script>
import axios from 'axios'

import ModalTerminos from './ModalTerminos'
import Mensajes from '../../services/Mensajes'

import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';

export default {
    props: ['datos_pais'],
    data() {
        return {
            solicitud: {
                nombres: '',
                empresa: '',
                correo: '',
                telefono: '',
                phone: ''
            },

            guardando: false,
            guardado: false,
        }
    },
    created() {},
    computed: {
        validaForm(){
            return (this.solicitud.telefono!=='')
        },
        obtenerPais(){
            return [this.datos_pais.country]
        }
    },
    components: {
        ModalTerminos,
        VuePhoneNumberInput,
    },
    methods: {
        async solicitarDemo() {
            this.guardando = true
            const datos = this.solicitud
            this.limpiarFormulario();
            await axios.post('/solicitud_demo',datos)
                        .then( response => {
                            const resp = response.data ?? false

                            if( resp && resp.code == 200 ){
                                Mensajes.success(`${response.data.message}`)
                                this.guardado = true
                            }
                            else{
                                Mensajes.error(`${response.data.message}`)
                                this.guardado = false
                            }
                        })
                        .catch( error => Mensajes.parsearErrores(error) )
            this.guardando = false
        },
        confirmarSolicitud(){
            this.$refs.modal_terminos.mostrarModal()
        },
        cambiarTelefono( e ){
            this.solicitud.telefono = (e.isValid === true) ? e.formattedNumber : ''
        },
        limpiarFormulario() {
            this.solicitud = {
                nombres: '',
                empresa: '',
                correo: '',
                telefono: '',
                phone: ''
            }
        }
    }
}
</script>