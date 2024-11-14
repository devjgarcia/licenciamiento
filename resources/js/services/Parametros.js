import axios from 'axios'

const parametros = {
    obtenerCabeceraTabla: () => {
        return [
            { 
                label: 'Cód',
                key: 'codigo' 
            },
            { 
                label: 'Nombre', 
                key: 'nombre' 
            },
            { 
                label: 'Valor',  
                key: 'valor' 
            },
            { 
                label: 'Estatus', 
                key: 'estatus' 
            },
            { 
                label: 'Acciones', 
                key: 'acciones', 
                sortable: false 
            },
        ]
    },

    obtenerParametros: async () => {
        return await axios.get('/parametros').then( response => {
            return response.data.parametros;
        })
        .catch( error => {
            console.log( error )
        })
    },

    guardarParametro: async ( datos ) => {

        if( datos.id !== undefined && datos.id > 0 ){
            return await axios.put(`/parametros/${datos.id}`, datos).then( response => {
                    return response.data.parametro;
                })
                .catch( error => {
                    console.log( error )
                })
        }
        else{
            return await axios.post(`/parametros`, datos).then( response => {
                    return response.data.parametro;
                })
                .catch( error => {
                    console.log( error )
                })
        }
    },
}

export default parametros