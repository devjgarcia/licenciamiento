<template>
    <div>
        <validation-observer ref="observer" v-slot="{ invalid }">
            <b-card title="" header-tag="header" footer-tag="footer" class="py-3">
                <b-card-text>
                    <b-overlay id="overlay-background" :show="guardando" rounded="md">
                        <b-row>
                            <b-col md="6" class="mt-2 py-2">
                                <ValidationProvider 
                                    :rules="{required: true, alpha_spaces: true}" 
                                    v-slot="{ errors, valid }" 
                                    name="Nombres y apellidos"
                                >
                                    <b-form-group label="Nombres y apellidos" class="py-2">
                                        <b-form-input 
                                            type="text"
                                            v-model="licencia.nombres"
                                            :state="valid" 
                                        />
                                        <div class="invalid-feedback d-block">
                                            <span>{{ errors[0] }}</span>
                                        </div>
                                    </b-form-group>
                                </ValidationProvider>
                            </b-col>

                            <b-col md="6" class="mt-2 py-2">
                                <ValidationProvider 
                                    :rules="{required: true, regex: /^[a-zA-Z0-9,.\s]+$/}" 
                                    v-slot="{ errors, valid }" 
                                    name="Nombre de Empresa"
                                >
                                    <b-form-group label="Nombres de Empresa" class="py-2">
                                        <b-form-input 
                                            type="text"
                                            v-model="licencia.empresa"
                                            :state="valid" 
                                        />
                                        <div class="invalid-feedback d-block">
                                            <span>{{ errors[0] }}</span>
                                        </div>
                                    </b-form-group>
                                </ValidationProvider>
                            </b-col>

                            <b-col md="6" class="mt-2 py-2">
                                <ValidationProvider 
                                    :rules="{required: true, email: true}" 
                                    v-slot="{ errors, valid }" 
                                    name="Correo electrónico"
                                >
                                    <b-form-group label="Correo electrónico" class="py-2">
                                        <b-form-input 
                                            type="email"
                                            v-model="licencia.correo"
                                            :state="valid" 
                                        />
                                        <div class="invalid-feedback d-block">
                                            <span>{{ errors[0] }}</span>
                                        </div>
                                    </b-form-group>
                                </ValidationProvider>
                            </b-col>

                            <b-col md="6" class="mt-2 py-2">
                                <b-form-group label="Telefono" class="py-2">
                                    <VuePhoneNumberInput 
                                        v-model="licencia.phone" 
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
                                </b-form-group>
                            </b-col>

                            <b-col md="6" class="mt-2 py-2">
                                
                                <ValidationProvider 
                                    :rules="{required: true, regex: /^[a-zA-Z0-9\s]+$/}" 
                                    v-slot="{ errors, valid }" 
                                    name="Nombre del País"
                                >
                                    <b-form-group label="Nombres del País" class="py-2">
                                        <b-input-group>
                                            <b-input-group-prepend>
                                                <vue-country-code
                                                    @onSelect="seleccionaPais"
                                                    :preferredCountries="['ve', 'us']" 
                                                    :enabledCountryCode="false"
                                                    :enableSearchField="true"
                                                    :defaultCountry="obtenerPaisIp"
                                                    :searchPlaceholderText="'Buscar País por nombre'"
                                                    :dropdownOptions="{ disabledDialCode: false }"
                                                />
                                            </b-input-group-prepend>
                                            <b-form-input 
                                                type="text"
                                                v-model="licencia.pais"
                                                :state="valid" 
                                                readonly
                                            />
                                            
                                        </b-input-group>
                                        <div class="invalid-feedback d-block">
                                            <span>{{ errors[0] }}</span>
                                        </div>
                                    </b-form-group>
                                </ValidationProvider>
                            </b-col>

                            <b-col md="6" class="mt-2 py-2">
                                <ValidationProvider 
                                    :rules="{required: true, regex: /^[a-zA-Z\s]+$/}" 
                                    v-slot="{ errors, valid }" 
                                    name="Código de País"
                                >
                                    <b-form-group label="Código País" class="py-2">
                                        <b-form-input 
                                            type="text"
                                            v-model="licencia.code_pais"
                                            :state="valid"
                                            readonly 
                                        />
                                        <div class="invalid-feedback d-block">
                                            <span>{{ errors[0] }}</span>
                                        </div>
                                    </b-form-group>
                                </ValidationProvider>
                            </b-col>

                            <b-col md="6" class="mt-2 py-2">
                                <ValidationProvider 
                                    :rules="{required: true}" 
                                    v-slot="{ errors, valid }" 
                                    name="Estatus de Licencia"
                                >
                                    <b-form-group label="Estatus de Licencia" class="py-2">
                                        <b-form-select
                                            v-model="licencia.estatus"
                                            :state="valid" 
                                        >
                                            <b-form-select-option :value="null">Seleccione</b-form-select-option>
                                            <b-form-select-option 
                                                v-for="(estatus, key) in obtenerEstatus" 
                                                v-bind:key="key" 
                                                v-bind:value="estatus.value"
                                            >
                                                {{ estatus.text }}
                                            </b-form-select-option>
                                        </b-form-select>
                                        <div class="invalid-feedback d-block">
                                            <span>{{ errors[0] }}</span>
                                        </div>
                                    </b-form-group>
                                </ValidationProvider>
                            </b-col>

                            <b-col md="6" class="mt-2 py-2">
                                <ValidationProvider 
                                    :rules="{required: true}" 
                                    v-slot="{ errors, valid }" 
                                    name="Tipo de Producto"
                                >
                                    <b-form-group label="Tipo de Producto" class="py-2">
                                        <b-form-select
                                            v-model="licencia.tipo_producto"
                                            :state="valid" 
                                        >
                                            <b-form-select-option :value="null">Seleccione</b-form-select-option>
                                            <b-form-select-option 
                                                v-for="(pro, key) in obtenerTiposProducto" 
                                                v-bind:key="key" 
                                                v-bind:value="pro.value"
                                            >
                                                {{ pro.text }}
                                            </b-form-select-option>
                                        </b-form-select>
                                        <div class="invalid-feedback d-block">
                                            <span>{{ errors[0] }}</span>
                                        </div>
                                    </b-form-group>
                                </ValidationProvider>
                            </b-col>

                            <hr>

                            <b-col md="12" class="text-center">
                                <h4>
                                    Datos de Profit
                                </h4>
                            </b-col>

                            <b-col md="6" class="mt-2 py-2">
                                <ValidationProvider 
                                    :rules="{required: false}" 
                                    v-slot="{ errors, valid }" 
                                    name="Servidor Profit"
                                >
                                    <b-form-group label="Servidor Profit">
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
                            </b-col>

                            <b-col md="6" class="mt-2 py-2">
                                <ValidationProvider 
                                    :rules="{required: false}" 
                                    v-slot="{ errors, valid }" 
                                    name="Base de Datos Profit"
                                >
                                    <b-form-group label="Base de Datos Profit">
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
                            </b-col>

                            <b-col md="6" class="mt-2 py-2">
                                <ValidationProvider 
                                    :rules="{required: false}" 
                                    v-slot="{ errors, valid }" 
                                    name="Usuario Profit"
                                >
                                    <b-form-group label="Usuario Profit">
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
                            </b-col>

                            <b-col md="6" class="mt-2 py-2">
                                <ValidationProvider 
                                    :rules="{required: false}" 
                                    v-slot="{ errors, valid }" 
                                    name="Contraseña Profit"
                                >
                                    <b-form-group label="Contraseña Profit">
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
                </b-card-text>
                <template class="text-center py-3" #footer>
                    <b-row>
                        <b-col md="12" class="text-center">
                            <b-button
                                :variant="(invalid || guardando || !validaForm) ? `secondary` : `success`"
                                @click="confirmarCreacion"
                                :disabled="invalid || guardando || !validaForm"
                            >
                                Crear Licencia
                            </b-button>
                            <a href="/" class="btn btn-default">
                                Cancelar
                            </a>
                        </b-col>
                    </b-row>
                </template>
            </b-card>
        </validation-observer>

        <ModalConfirmarCreacion 
            ref="modal_confirmar"
            @aceptar="guardarLicencia"
        />
    </div>
</template>

<script>
import axios from 'axios'

import ModalConfirmarCreacion from './ModalConfirmarCreacion'
import Mensajes from '../../services/Mensajes'
import Licencias from '../../services/Licencias'

import VuePhoneNumberInput from 'vue-phone-number-input'
import 'vue-phone-number-input/dist/vue-phone-number-input.css'

import VueCountryCode from "vue-country-code-select";
//Vue.use(VueCountryCode);

export default {
    props: ['datos_pais', 'tipos_producto'],
    data() {
        return {
            licencia: {
                nombres: '',
                empresa: '',
                correo: '',
                telefono: '',
                phone: '',
                pais: '',
                code_pais: '',
                profitserver: '',
                profituser: '',
                profitpass: '',
                profitdb: '',
                estatus: null,
                tipo_producto: null,
            },

            guardando: false,
            estados_licencia: [],
            productos: [],

            pais: '',
        }
    },
    created() {
        Licencias.obtenerEstadosLicencia().then(response => this.estados_licencia = response)
        this.productos = this.tipos_producto
    },
    computed: {
        validaForm(){
            return (this.licencia.telefono!=='')
        },
        obtenerPais(){
            return [this.datos_pais.country ?? 'VE']
        },
        obtenerPaisIp(){
            return this.datos_pais.country ?? 'VE'
        },
        obtenerTiposProducto() {
            return this.productos
        },
        obtenerEstatus(){
            if( this.estados_licencia.length > 0 ){
                return this.estados_licencia
            }
        },
    },
    components: {
        VuePhoneNumberInput,
        VueCountryCode,
        ModalConfirmarCreacion,
    },
    methods: {
        async guardarLicencia() {
            this.guardando = true
            const datos = this.licencia
            
            await axios.post('/licencia/guardar', datos)
                        .then( response => {
                            Mensajes.success(`${response.data.message}`)
                            this.limpiarFormulario()
                        })
                        .catch( error => Mensajes.parsearErrores(error) )
            this.guardando = false
        },
        confirmarCreacion(){
            const pos = this.estados_licencia.findIndex( e => e.value === this.licencia.estatus )
            const pos_prod = this.productos.findIndex( p => p.value === this.licencia.tipo_producto )
            const datos = [
                {key: 'Nombres',              dato: this.licencia.nombres},
                {key: 'Empresa',              dato:this.licencia.empresa},
                {key: 'Teléfono',             dato: this.licencia.telefono},
                {key: 'País',                 dato: this.licencia.pais},
                {key: 'Código de País',       dato:this.licencia.code_pais},
                {key: 'Servidor Profit',      dato:this.licencia.profitserver},
                {key: 'Usuario Profit',       dato:this.licencia.profituser},
                {key: 'Base de Datos Profit', dato: this.licencia.profitdb},
                {key: 'Estatus de Licencia',  dato: this.estados_licencia[pos].text},
                {key: 'Tipo de Producto',     dato: this.productos[pos_prod].text},
            ]

            this.$refs.modal_confirmar.mostrarModal( datos )
        },
        cambiarTelefono( e ){
            this.licencia.telefono = (e.isValid === true) ? e.formattedNumber : ''
        },
        seleccionaPais({name, iso2, dialCode}) {
            console.log(name, iso2, dialCode);

            this.licencia.pais = name
            this.licencia.code_pais = iso2
        },
        limpiarFormulario() {
            this.licencia = {
                nombres: '',
                empresa: '',
                correo: '',
                telefono: '',
                phone: '',
                pais: '',
                code_pais: '',
                profitserver: '',
                profituser: '',
                profitpass: '',
                profitdb: '',
                estatus: '',
            }
        }
    }
}
</script>