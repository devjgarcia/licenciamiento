import axios from 'axios'

const usuarios = {
    obtenerUsuarios: async () => {
        return await axios.get(`/getUsers`)
                        .then( response => { return response.data.usuarios } )
                        .catch( error => console.log(error) )
    },
    obtenerCamposListado: () => {
        return [
            { 
                label: '#',
                key: 'id',
                sortable: false
            },
            { 
                label: 'Nombres y apellidos', 
                key: 'name',
                sortable: true
            },
            { 
                label: 'Doc. de Identidad',  
                key: 'rif',
                sortable: true
            },
            { 
                label: 'Correo',  
                key: 'email',
                sortable: true
            },
            { 
                label: 'Rol',  
                key: 'role.name_role',
                sortable: true
            },
            { 
                label: 'Estatus',  
                key: 'estado',
                sortable: false
            },
            { 
                label: 'Acciones',  
                key: 'actions',
                sortable: false
            },
        ]
    },
    obtenerRolesUsuario: async () => { 
        return await axios.get(`/getRoles`)
                        .then( response => { return response.data.roles } )
    },
    obtenerEstadosUsuario: async () => { 
        return await axios.get(`/getEstadosUsuario`)
                        .then( response => { return response.data.estados } )
    },
    eliminarUsuario: async ( usuario ) => { 
        return await axios.delete(`/usuario/${usuario.id}`)
                            .then( response => {return true} )
    }
}

export default usuarios