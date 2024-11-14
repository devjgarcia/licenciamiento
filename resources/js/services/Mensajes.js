import Vue from 'vue'
import BootstrapVue from "bootstrap-vue";

Vue.use( BootstrapVue )
const vm = new Vue({})

const mensajes = {
    success : ( mensaje ) => {        
        
        vm.$bvToast.toast( mensaje, {
            title: ``,
            autoHideDelay: 5000,
            variant: 'success',
            solid: true,
            toaster: 'b-toaster-top-right',
            appendToast: false
        })
    },
    error : ( mensaje ) => {        
        
        vm.$bvToast.toast( mensaje, {
            title: ``,
            autoHideDelay: 5000,
            variant: 'danger',
            solid: true,
            toaster: 'b-toaster-top-right',
            appendToast: true,
            toastClass: {zIndex:9999999}
        })
    },
    parsearErrores: (error) => {
        let errores_parse = '';
        if (error.response.data.errors) {
            let errores =  Object.entries(error.response.data.errors);
            
            errores.forEach(([key, value]) => {
                errores_parse += value + '.'
            });
        }

        vm.$bvToast.toast( errores_parse, {
            title: ``,
            autoHideDelay: 15000,
            variant: 'danger',
            solid: true,
            toaster: 'b-toaster-top-right',
            appendToast: true,
            toastClass: {zIndex:9999999}
        })
    }
}

export default mensajes