<template>
    <div>
        <b-modal
            id="modal_detalle_activa"
            ref="modal_detalle_activa"
            no-fade
            no-close-on-esc
            no-close-on-backdrop
            size="xl"
            @ok="aprobarActivacion()"
        >
            <template #modal-header="">
                <h5>
                    Activación de Licencias o Recarga de Empleados
                </h5>
            </template>
            <b-overlay id="overlay-vencimiento" :show="guardando" rounded="lg">
                <b-row>
                    <b-col md="12" class="table-responsive">
                        <b-table 
                            striped 
                            hover 
                            :items="obtenerListadoLicencias" 
                            :fields="obtenerCamposTabla"
                            ref="table"
                            class="table-sm"
                        ></b-table>
                    </b-col>
                </b-row>
            </b-overlay>

            <template #modal-footer="{ ok, cancel }">
                <b-button type="button" size="md" variant="success" @click="ok()" :disabled="guardando">
                    Aprobar
                </b-button>
                <b-button type="button" size="md" variant="secondary" @click="cancel()">
                    Cancelar
                </b-button>
            </template>
        </b-modal>
    </div>
</template>

<script>
import axios from 'axios'
import Mensajes from '../../services/Mensajes'
import Licencias from '../../services/Licencias'

export default {
    data() {
        return {
            licencias: [],
            guardando: false,
        }
    },
    computed: {
        obtenerListadoLicencias() {
            if( this.licencias.length > 0 ){
                return this.licencias
            }
        },
        obtenerCamposTabla() {
            return Licencias.obtenerCabeceraTablaDetalleActiva()
        }
    },
    methods: {
        mostrarModal( licencias ){

            this.licencias = licencias
            this.$refs.modal_detalle_activa.show();
        },

        aprobarActivacion() {
            const lice = this.licencias.map( item => {
                return {
                    codigo: item.codigo,
                    correo: item.correo,
                    vencimiento: item.fecvenlicact,
                    empresa: item.empresa,
                    total_empleados: item.totalempxact
                }
            })
            const datos = {
                licencias: lice
            }
            this.$emit(`activar`, datos)

        },

        limpiarModal() {
            this.licencias = []
            this.$refs.modal_detalle_activa.hide();
        }
    }
}

</script>