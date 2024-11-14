import axios from 'axios'

const noti_moneda = {
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

    obtenerListado: async ( only_active = false ) => {

        const url = (only_active !== false) ? `/noti-moneda?only_active=1` : `/noti-moneda`;

        return await axios.get( url ).then( response => {
            return response.data.noti_monedas;
        })
        .catch( error => {
            console.log( error )
        })
    },

    guardar: async ( datos ) => {

        if( datos.id !== undefined && datos.id > 0 ){
            return await axios.put(`/noti-moneda/${datos.id}`, datos).then( response => {
                    return response.data.noti_moneda;
                })
                .catch( error => {
                    console.log( error )
                })
        }
        else{
            return await axios.post(`/noti-moneda`, datos).then( response => {
                    return response.data.noti_moneda;
                })
                .catch( error => {
                    console.log( error )
                })
        }
    },

}

export default noti_moneda