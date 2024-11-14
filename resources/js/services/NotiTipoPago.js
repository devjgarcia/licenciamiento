import axios from 'axios'

const tipo_pago = {
    obtenerCabeceraTabla: () => {
        return [
            { 
                label: '#',
                key: 'id',
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

    obtenerListado: async () => {
        return await axios.get('/tipo-pagos').then( response => {
            return response.data.tipo_pagos;
        })
        .catch( error => {
            console.log( error )
        })
    },

    guardar: async ( datos ) => {

        if( datos.id !== undefined && datos.id > 0 ){
            return await axios.put(`/tipo-pagos/${datos.id}`, datos).then( response => {
                    return response.data.tipo_pago;
                })
                .catch( error => {
                    console.log( error )
                })
        }
        else{
            return await axios.post(`/tipo-pagos`, datos).then( response => {
                    return response.data.tipo_pago;
                })
                .catch( error => {
                    console.log( error )
                })
        }
    },

}

export default tipo_pago