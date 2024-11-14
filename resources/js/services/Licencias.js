import axios from 'axios'

const licencias = {
    obtenerCabeceraTabla: () => {
        return [
            { 
                label: 'Cód',
                key: 'codigo',
                sortable: true 
            },
            { 
                label: 'Cliente', 
                key: 'frkcliente',
                sortable: true
            },
            { 
                label: 'Inicio',  
                key: 'inicio',
                sortable: true
            },
            { 
                label: 'Vencimiento', 
                key: 'vencimiento',
                sortable: true
            },
            { 
                label: 'Estatus', 
                key: 'estado',
                sortable: false
            },
            { 
                label: 'Ult. Actualización', 
                key: 'actualizacion',
                sortable: true
            },
            { 
                label: 'Actions', 
                key: 'actions', 
                sortable: false 
            },
        ]
    },
    obtenerCabeceraTablaPorActivar: () => {
        return [
            { 
                label: '',
                key: 'selected',
                sortable: false,
            },
            { 
                label: 'Cód',
                key: 'codigo',
                sortable: true,
            },
            { 
                label: 'Cliente', 
                key: 'frkcliente',
                sortable: true,
            },
            { 
                label: 'Creación',  
                key: 'creacion',
                sortable: true,
            },
            { 
                label: 'Vencimiento',  
                key: 'vencimiento',
                sortable: true,
            },
            { 
                label: 'Pais',  
                key: 'nombre_pais',
                sortable: true,
            },
            { 
                label: 'Datos de Pago',  
                key: 'datos_pago',
                sortable: false,
            },
            { 
                label: 'Datos de Contacto',  
                key: 'datos_contacto',
                sortable: false,
            },
        ]
    },
    obtenerCabeceraTablaDetalleActiva: () => {
        return [
            { 
                label: 'SM',
                key: 'codigo',
                sortable: true,
            },
            { 
                label: 'Cliente', 
                key: 'frkcliente',
                sortable: true,
            },
            { 
                label: 'Correo', 
                key: 'correo',
                sortable: true,
            },
            { 
                label: 'Vencimiento',  
                key: 'vencimiento',
                sortable: true,
            },
            { 
                label: 'Nuevo Vencimiento',  
                key: 'fecvenlicact',
                sortable: true,
            },
            { 
                label: 'Empleados Solicitados',  
                key: 'totempxact',
                sortable: true,
            },
            { 
                label: 'Total Cancelado',  
                key: 'totalxlicact',
                sortable: true,
            },
        ]
    },
    obtenerLicenciasAllType: async ( estado = false ) => {
        return await axios.get(`/licencias?estado=${estado ?? null}&is_ajax=1`).then( response => {
            
            return response.data.licencias;
        })
        .catch( error => {
            console.log( error )
        })
    },
    obtenerLicencias: async ( estado = false ) => {
        return await axios.get(`/getLice?estado=${estado ?? null}`).then( response => {
            
            return response.data.licencias;
        })
        .catch( error => {
            console.log( error )
        })
    },
    obtenerLicenciasXActivar: async ( tipo_activa = 1 ) => {
        return await axios.get(`/getLiceXActivar?tipo_act=${tipo_activa}`).then( response => {
            
            return response.data.licencias;
        })
        .catch( error => {
            console.log( error )
        })
    },
    obtenerEstadosLicencia: async () => {
        return await axios.get(`/obtenerEstados`).then(response => {
            return response.data.estados;
        });
    }
}

export default licencias