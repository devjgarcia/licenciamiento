import axios from 'axios'

const noti_cuenta = {
    obtenerCabeceraTabla: () => {
        return [
            { 
                label: '#',
                key: 'id',
                sortable: true 
            },
            { 
                label: 'Cód', 
                key: 'codigo',
                sortable: true
            },
            { 
                label: 'Nombre', 
                key: 'nombre',
                sortable: true
            },
            { 
                label: 'Moneda', 
                key: 'tipo_moneda',
                sortable: true
            },
            { 
                label: 'Estatus', 
                key: 'estatus',
                sortable: false
            },
            { 
                label: 'Actions', 
                key: 'actions', 
                sortable: false 
            },
        ]
    },

    obtenerListado: async () => {
        return await axios.get('/noti-cuenta').then( response => {
            return response.data.noti_cuentas;
        })
        .catch( error => {
            console.log( error )
        })
    },

    guardar: async ( datos ) => {

        if( datos.id !== undefined && datos.id > 0 ){
            return await axios.put(`/noti-cuenta/${datos.id}`, datos).then( response => {
                    return response.data.noti_cuenta;
                })
                .catch( error => {
                    console.log( error )
                })
        }
        else{
            return await axios.post(`/noti-cuenta`, datos).then( response => {
                    return response.data.noti_cuenta;
                })
                .catch( error => {
                    console.log( error )
                })
        }
    },

}

export default noti_cuenta