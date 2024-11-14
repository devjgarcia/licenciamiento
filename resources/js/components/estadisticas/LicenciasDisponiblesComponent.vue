<template>
    <div>
        <b-form-checkbox 
            v-model="actualizacion_continua" 
            name="check-button" 
            switch 
            size="lg"
        >
            Actualización en Linea
        </b-form-checkbox>
        <highcharts v-if="datos_lice !== null" :options="obtenerConfigChart"></highcharts>
    </div>
</template>

<script>
import axios from 'axios'
import {Chart} from 'highcharts-vue'

export default {
    data() {
        return {
            datos_lice: {
                LicDisponibles: 0,
                LicTotales: 0,
            },
            actualizacion_continua: true,
            intervalo: null,
        }
    },

    watch: {
        actualizacion_continua(newVal, old) {

            if( newVal ){
                let ref = this.$refs
                this.intervalo = setInterval(() => {
                    this.obtenerDatos()
                    }, 10000)
            }
            else{
                clearInterval(this.intervalo)
            }
        }

    },

    computed: {
        obtenerConfigChart(){

            if( this.datos_lice ){

                return  {
                    colors:['#28a745', '#007bff'],
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: ''
                    },
                    xAxis: {
                        categories: ['Licencias Saca']
                    },
                    yAxis: {
                        title: {
                            text: 'Cantidad'
                        }
                    },
                    series: [
                        {
                            name: 'Disponibles',
                            data: [this.datos_lice.LicDisponibles]
                        },
                        {
                            name: 'Asignadas',
                            data: [this.datos_lice.LicTotales]
                        }
                    ],
                }
            }
        }
    },

    components: {
        highcharts: Chart 
    },

    created() {
        this.obtenerDatos()
    },

    methods: {
        async obtenerDatos() {
            await axios.get(`/lice_dispo_estadisticas`)
                .then(response => {
                    this.datos_lice = response.data[0]
                })
        }
    }
}
</script>