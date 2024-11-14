<template>
    <div>
        <b-row>
            <b-col cols="12">
                <b-card>
                    <template #header>
                        <b-row>
                            <b-col md="6" sm="12" xl="6">
                                <b-form-input v-model="input_buscar" placeholder="Buscar..."></b-form-input>
                            </b-col>
                            <b-col md="6" sm="12" xl="6" class="text-lg-right">
                                <b-button
                                    variant="success"
                                    @click="agregarUsuario"
                                >
                                    <i class="fa fa-user-plus"></i> 
                                    Agregar Usuario
                                </b-button>
                            </b-col>
                        </b-row>
                    </template>
                    <b-table 
                        striped 
                        hover 
                        :items="obtenerListado" 
                        :fields="campos_tabla"
                        :busy="cargando"
                        :per-page="por_pagina"
                        :current-page="pagina_actual"
                        ref="table"
                        :filter="input_buscar"
                        class="table-sm"
                    >
                        <template #table-busy>
                            <div class="text-center my-2">
                                <b-spinner class="align-middle"></b-spinner>
                                <strong>Cargando...</strong>
                            </div>
                        </template>
                        
                        <template #cell(estado)="data">
                            <b-badge 
                                :variant="data.item.class_estado"
                            >
                                {{ data.item.status }}
                            </b-badge>
                        </template>

                        <template #cell(actions)="data">
                            <b-button 
                                size="sm" 
                                variant="default"
                                @click="data.toggleDetails"
                            >
                                <i 
                                    v-if="data.detailsShowing"
                                    class="fa fa-eye-slash"
                                    title="Mostrar otros detalles" 
                                ></i>
                                <i 
                                    v-else
                                    class="fa fa-eye" 
                                    title="Ocultar detalles" 
                                ></i>
                            </b-button>
                            <b-button variant="default" class="btn-sm" @click="editarUsuario(data.item)">
                                <i class="fa fa-edit"></i>
                            </b-button>
                            <b-button variant="danger" class="btn-sm" @click="confirmarEliminar(data.item)">
                                <i class="fa fa-trash"></i>
                            </b-button>
                        </template>

                        <template #row-details="row">
                            <b-card>
                                <b-row class="mb-2">
                                    <b-col sm="12" md="4" class="">
                                        <b>Dirección: </b>
                                        {{ row.item.direction }}
                                    </b-col>
                                    <b-col sm="12" md="4" class="">
                                        <b>Teléfono: </b>
                                        {{ row.item.phone }}
                                    </b-col>
                                    <b-col sm="12" md="4" class="">
                                        <b>Última Actualización: </b>
                                        {{ row.item.updated_at }}
                                    </b-col>
                                </b-row>
                            </b-card>
                        </template>
                    </b-table>
                    <div class="mt-3">
                        <b-pagination
                        align="center"
                        v-model="pagina_actual"
                        :total-rows="obtenerCantidadFilas"
                        :per-page="por_pagina"
                        class="text-center"
                        ></b-pagination>
                    </div>
                </b-card>
            </b-col>
        </b-row>
    </div>
</template>
<script>
import Usuario from '../../services/Usuarios'
import Mensajes from '../../services/Mensajes'

export default {
    props: ['listado_usuarios'],
    data() {
        return {
            cargando: true,
            input_buscar: '',
            por_pagina: 20,
            pagina_actual: 1,
            campos_tabla: Usuario.obtenerCamposListado(),
            listado: [],
        }
    },
    computed: {
        obtenerListado() {
            return this.listado
        },
        obtenerCantidadFilas() {
            return this.listado.length
        },
    },
    created() {
        this.listado = this.listado_usuarios
        this.cargando = false
    },
    methods: {
        agregarUsuario() {
            this.$emit('crear_usuario', null)
        },
        editarUsuario( usuario ) {
            const usuEditar = {
                id: usuario.id,
                name: usuario.name,
                rif: usuario.rif,
                direction: usuario.direction,
                phone: usuario.phone,
                email: usuario.email,
                password: '',
                role_id: usuario.role_id,
                status: usuario.status,
                photo: usuario.photo,
                file_photo: null,
                status: usuario.status,
            }
            this.$emit('editar_usuario', usuEditar)
        },
        cargarUsuarioAListado( usuario ) {
            this.listado.push( usuario );
            this.$refs.table.refresh();
        },
        actualizarUsuarioEnListado( usuario ) {
            const posicion = this.listado.findIndex( f => f.id === usuario.id );
            this.listado[posicion] = usuario;
            this.$refs.table.refresh();
        },
        confirmarEliminar( usuario ){
            this.$bvModal.msgBoxConfirm(`¿Está seguro de eliminar este Usuario?`, {
                title: 'Confirme esta operación',
                size: 'md',
                buttonSize: 'sm',
                okVariant: 'danger',
                okTitle: 'Sí, eliminar',
                cancelTitle: 'No',
                footerClass: 'p-2',
                hideHeaderClose: false,
                centered: true
            })
            .then(value => {                
                if( value === true ){
                    this.eliminarUsuario( usuario )
                }
            })
        },
        async eliminarUsuario( usuario ) {
            await Usuario.eliminarUsuario( usuario )
                    .then( response => {
                        
                        const posicion = this.listado.findIndex( f => f.id === usuario.id );
                        this.listado.splice( posicion, 1 );

                        Mensajes.success(`Usuario ${usuario.name} eliminado`)
                    })
        }
    }
}
</script>