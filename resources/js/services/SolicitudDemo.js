import axios from 'axios'

const solicitud = {

    obtenerPais: async () => {
        // Usaremos la IP de un servidor de google como ejemplo
        const ip = `74.125.224.72`;

        // La api de cors-anywhere espera como argument la URL de destino
        let CorsAnyWhereUrl = `https://cors-anywhere.herokuapp.com/`;

        // La URL de geoplugin que espera la IP de usuario
        let GeoPluginUrl =  `http://www.geoplugin.net/json.gp?ip=`;

        // Hacer llamada usando jQuery
        return await axios.get(`http://api.wipmania.com/jsonp?callback=?`)
            .then( response => {return response} )

            //("Hola visitante de "+ response.geoplugin_countryName)
    },

    obtenerListado: async () => {
        return await axios.get('/demosoli-listado').then( response => {

            console.log( response );
            return response.data.soli_saca;
        })
        .catch( error => {
            console.log( error )
        })
    },

    obtenerCabeceraTabla: () => {
        return [
            { 
                label: 'Fecha', 
                key: 'created_at',
                sortable: true
            },
            { 
                label: 'Empresa', 
                key: 'empresa',
                sortable: true
            },
            { 
                label: 'Correo', 
                key: 'correo',
                sortable: true
            },
            { 
                label: 'Encargado', 
                key: 'nombres',
                sortable: true
            },
            { 
                label: 'Telefono', 
                key: 'telefono',
                sortable: true
            },
            { 
                label: 'Pais', 
                key: 'pais',
                sortable: true
            },
            { 
                label: 'Correo enviado', 
                key: 'correo_env',
                sortable: false
            },
            { 
                label: 'Solicitud procesada', 
                key: 'completado',
                sortable: false
            },
            { 
                label: 'Actions', 
                key: 'actions', 
                sortable: false 
            },
        ]
    },
}

export default solicitud