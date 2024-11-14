/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import {
    ValidationObserver,
    ValidationProvider,
    extend,
    localize
} from "vee-validate";

  
import es from "vee-validate/dist/locale/es.json";
import * as rules from "vee-validate/dist/rules";

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import CKEditor from '@ckeditor/ckeditor5-vue2';


// Install VeeValidate rules and localization
Object.keys(rules).forEach(rule => {
    extend(rule, rules[rule]);
});
  
localize("es", es);
  
// Install VeeValidate components globally
Vue.component("ValidationObserver", ValidationObserver);
Vue.component("ValidationProvider", ValidationProvider);


// Make BootstrapVue available throughout your project
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)

Vue.use( CKEditor );

Vue.component('crear-licencia-component', require('./components/licencias/CrearLicenciaComponent.vue').default);
Vue.component('licencias-component', require('./components/licencias/ListadoLicenciasComponent.vue').default);
Vue.component('activacion-licencias-component', require('./components/licencias/ActivacionLicenciasComponent.vue').default);
Vue.component('parametros-component', require('./components/parametros/ParametrosComponent.vue').default);
Vue.component('usuarios-component', require('./components/usuarios/UsuariosComponent.vue').default);
Vue.component('parametros-component', require('./components/parametros/ParametrosComponent.vue').default);

Vue.component('solicitud-component', require('./components/solicitud_demo/SolicitudDemoComponent').default);
Vue.component('listado-solisaca-component', require('./components/solicitud_demo/Listado').default);

Vue.component('licencias-disponibles-component', require('./components/estadisticas/LicenciasDisponiblesComponent').default);

Vue.component('buscar-licencia-component', require('./components/licencias/BuscarLicenciaComponent').default);

//Notificacion de Pagos
Vue.component('np-tipopago-component', require('./components/noti_tippago/NotiTipoPagoComponent').default);
Vue.component('np-monedas-component', require('./components/noti_moneda/NotiMonedaComponent').default);
Vue.component('np-cuentas-component', require('./components/noti_cuentas/NotiCuentasComponent').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
