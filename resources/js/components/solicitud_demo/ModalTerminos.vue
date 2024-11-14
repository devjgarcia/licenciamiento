<template>
    <div>
        <b-modal 
            id="modal-politicas" 
            size="xl" 
            scrollable 
            no-fade
            no-close-on-esc
            no-close-on-backdrop
            title="Políticas"
            ref="modal_politica"
            @ok="aceptar"
        >
            <b-row>
                <b-col md="12">
                    <b-col md="12" class="text-center py-4">
                        <b>Baja hasta el final del texto para seleccionar que estás de acuerdo</b>
                    </b-col>
                    <h5 class="text-center">
                        ACUERDO DE LICENCIA DE USUARIO FINAL PARA LA VERSIÓN DEMO DEL SOFTWARE “SACAMOVIL”.
                    </h5>
                    <p align="left" class="text-justify">
                        Importante: Este acuerdo de licencia para usuario final, sea éste persona física o jurídica (en adelante, el “Contrato”), constituye un <br>
                        acuerdo entre SACA C.A., (en adelante, SACAMOVIL) y el usuario (Usted, en adelante, el licenciatario) y regula las condiciones de uso   <br>
                        de la versión DEMO del Software SACAMOVIL, de acuerdo con las condiciones que aquí se establecen.
                    </p>
                    <p align="left" class="text-justify">
                        La aceptación de este Acuerdo de Licencia le atribuye la condición de licenciatario o Usuario Final y expresa la aceptación plena y sin <br>
                        reservas por su parte de todos y cada uno de los términos de este Acuerdo. Este Acuerdo es legalmente vinculante y al descargar, <br>
                        instalar y/o utilizar el Software de cualquier manera, estará aceptando todos los términos y condiciones del mismo, en su propio <br>
                        nombre y/o en el de la entidad que usted represente o para cuyo sistema de información haya adquirido el Software SACAMOVIL. <br>
                        <br>
                        Si no está de acuerdo con los términos de este Acuerdo, no proceda a la instalación y/o activación o utilice de cualquier forma el <br>
                        Software o cualquier elemento asociado a éste. El licenciatario reconoce expresamente que los derechos de explotación tanto del <br>
                        Software asociado al presente contrato, como de la Propiedad Intelectual e Industrial vinculados al mismo y que no le han sido  <br>
                        transferidos en virtud del presente contrato son propiedad de SACAMOVIL. El Software amparado en el presente contrato de licencia <br>
                        está protegido por la normativa nacional e internacional sobre copyright.
                    </p>
                    <p align="left">
                        La aceptación por parte del licenciatario del presente contrato de licencia no supone la adquisición del Software ni el derecho a <br>
                        usarlo de cualquier forma no prevista en el presente acuerdo sino, exclusivamente, la concesión de una licencia de la versión DEMO <br>
                        del mismo con las funcionalidades disponibles en dicha versión, todo ello, según las presentes Condiciones de Licencia:
                    </p>
                    <p align="left">
                        1. Objeto. - El objeto del contrato establece las condiciones de licenciamiento temporal, para el acceso y evaluación del software <br>
                        SACAMOVIL (en adelante, “el Software“), y las interfaces con todos los componentes de la versión DEMO del software, la <br>
                        documentación técnica y/o los manuales de usuario, tanto en su forma impresa como en su versión electrónica u “online”.
                    </p>
                    <p align="left">
                        1.1. La licencia objeto del presente acuerdo es concedida exclusivamente para el uso del licenciatario en términos de evaluación del <br>
                        software, por un tiempo definido de sesenta (60) días. SACAMOVIL garantiza al licenciatario el derecho de usar una copia del <br>
                        Software adquirido legalmente, durante el plazo previsto y de acuerdo con las previsiones del presente contrato. De igual forma, <br>
                        el software DEMO de SACAMOVIL, estará limitado a un máximo de un *1 (usuario por Licencia de DEMO). <br> 
                        Con una carga máxima de diez (10) trabajadores para pruebas.
                        <br>
                    </p>
                    <p align="left">
                        2. Concesión de Licencia. - Mediante el presente contrato, SACAMOVIL le concede una licencia limitada de uso, no exclusiva e  <br>
                        intransferible, de una versión de evaluación del Software sujeta a los términos y condiciones indicados en el presente contrato. <br>
                        No se permite el uso del Software de ninguna otra manera que no esté expresamente autorizada por esta licencia. El licenciatario <br>
                        no podrá copiar los manuales ni otros materiales impresos o en versión online asociados al Software.
                    </p>
                    <p align="left">
                        2.1. El período efectivo de utilización de esta licencia empezará desde la fecha en la que se facilite al licenciatario por parte <br>
                        de SACAMOVIL el acceso a la versión de evaluación del software y se extenderá durante el tiempo previsto (60), en cuyo momento los <br>
                        derechos otorgados por esta licencia cesarán.
                    </p>
                    <p align="left">
                        2.2. SACAMOVIL no realizará ningún aviso o advertencia previa sobre la finalización del período de evaluación, siendo el licenciatario <br>
                        responsable de verificar el mismo y tomar las medidas oportunas para salvaguardar los datos que hubiera podido generar durante la <br>
                        utilización del Software.      
                    </p>
                    <p align="left">
                        2.3. La licencia NO PERMITE modificar, transformar, descompilar o utilizar técnicas de ingeniería inversa en el software. El software <br>
                        se podrá emplear únicamente para recuperar los datos de archivos que hayan sido creados por el licenciatario durante el plazo  <br>
                        concedido para la evaluación del mismo, quedando expresamente prohibida su utilización en la prestación de servicios a terceros, <br>
                        así como la reventa o cesión de uso a terceros de este software por parte del licenciatario.  
                    </p>
                    <p align="left">
                        2.4. La licencia NO PERMITE la distribución o comunicación pública del software, ni otorga ningún tipo de derecho, título o propiedad <br>
                        sobre las marcas comerciales o de servicio, logotipos o nombres comerciales de SACAMOVIL. Si el software se distribuye bajo la <br>
                        etiqueta o versión de “Evaluación-Demo”, con independencia de lo establecido en este Contrato, su licencia solo le permite utilizarlo <br>
                        si Ud. es un licenciatario final, y solo con fines de demostración, prueba o evaluación. Esta versión del Software no permite usarlo  <br>
                        con fines de producción ni ningún otro salvo el descrito. El incumplimiento de cualquiera de las anteriores condiciones conllevará la <br>
                        rescisión del contrato y la anulación automática de la licencia.    
                    </p>
                    <p align="left">
                        3. Garantías. - El software y la documentación con las instrucciones de uso en cualquiera de sus versiones o formatos, se <br>
                        proporcionan tal cual, sin ninguna garantía expresa o implícita de ningún tipo, incluyendo cualquier garantía de comerciabilidad <br>
                        o idoneidad para un propósito particular.   
                    </p>
                    <p align="left">
                        3.1. SACAMOVIL garantiza que el Software que vende o distribuye está libre de defectos materiales bajo un uso normal de acuerdo <br>
                        con sus manuales o instrucciones a partir de la fecha en que se habilita el acceso al licenciatario. Esta garantía no será <br>
                        válida si el Software: (a) se ha modificado, excepto si tal modificación ha sido realizada por SACAMOVIL, (b) no se ha instalado, <br>
                        usado, reparado o mantenido según las instrucciones suministradas por SACAMOVIL, (c) se ha usado indebidamente, de forma <br>
                        negligente o por accidente; o (d) su licencia es solo válida para versiones de evaluación, prueba o demostración.    
                    </p>
                    <p align="left">
                        4. Limitaciones adicionales. - El licenciatario no podrá alquilar, licenciar, vender o transferir a terceros el acceso al <br>
                        software o su licencia de uso. El software puede contener medidas tecnológicas de protección para evitar las copias no <br>
                        autorizadas del mismo. Cualquier actividad del licenciatario que suponga la elusión o desactivación de cualquier tecnología <br>
                        de protección de copia implementada en el Software, supondrá la terminación del presente contrato, sin perjuicio de las <br>
                        acciones legales que pudieran derivarse.   
                    </p>
                    <p align="left">
                        5. Limitación de Responsabilidad. - SACAMOVIL no asumirá responsabilidad alguna por la pérdida de ingresos, beneficios o <br>
                        información, así como por daños o perjuicios que se deriven o estén relacionados con el uso del software o con la <br>
                        imposibilidad de utilizarlo, incluso en aquellos casos en los que se haya advertido a SACAMOVIL de la posibilidad de que <br>
                        se produzcan tales daños. SACAMOVIL  queda eximida de cualquier obligación derivada del presente contrato en el caso de <br>
                        que deje de comercializar el software objeto del mismo.   
                    </p>
                    <p align="left">
                        6. Duración del acuerdo. - Si el software se distribuye bajo la etiqueta o versión de “Evaluación/Demostración”, la <br>
                        licencia será válida durante sesenta (60) días y no incluirá servicios de soporte y/o mantenimiento, ni garantía o <br>
                        indemnización por cualquier concepto. Tenga en cuenta que la versión Demo/Evaluación del Software puede desactivarse <br>
                        por sí solo una vez que caduque la “clave” de licencia. La versión Demo/Evaluación del Software no podrá usarse bajo <br>
                        ninguna circunstancia después de haber caducado o haberse dado de baja su licencia. Si desea utilizar el Software <br>
                        después de terminado el período para su evaluación, tendrá que adquirir una copia o versión válida del mismo en la <br>
                        versión que SACAMOVIL o sus distribuidores comercialicen en cada momento. Sin perjuicio de cualquier otro derecho, <br>
                        esta licencia dejará de tener validez y eficacia si el licenciatario incumple cualquiera de las condiciones recogidas <br>
                        en la misma.   
                    </p>
                    <p align="left">
                        7. Disposiciones finales. <br>
                        7.1. Legislación Aplicable: El presente Acuerdo de Licencia se regirá por la legislación de la Republica Bolivariana de Venezuela. <br>
                    </p>
                    <h6>LEY ESPECIAL CONTRA LOS DELITOS INFORMÁTICOS.</h6> 
                    <br>
                    <p>
                        7.2. Validez de cláusulas. - Si algún tribunal de arbitraje o algún tribunal jurisdiccional declara algún término o <br>
                        disposición del presente acuerdo como no válido, la carencia de validez se limitará solo al término o disposición en <br>
                        cuestión, conservando el resto de las cláusulas del presente Contrato plena vigencia durante su período de validez.
                    </p>
                </b-col>
                <b-col md="12" class="text-center py-2 mt-1">
                    <b-form-checkbox v-model="de_acuerdo" size="lg"><h5 class="text-success">Estoy de acuerdo</h5></b-form-checkbox>
                </b-col>
                
            </b-row>
            <template #modal-footer="{ ok, cancel }">                
                <b-button 
                    :variant="(de_acuerdo) ? `success` : `secondary`" 
                    @click="ok()"
                    :disabled="!de_acuerdo"
                >
                    Aceptar y continuar
                </b-button>
                
                <b-button variant="default" @click="cancel()">
                    Cancelar
                </b-button>
            </template>
        </b-modal>
    </div>
</template>

<script>
export default {
    data() {
        return {
            de_acuerdo: false,
        }
    },
    methods: {
        mostrarModal(){
            this.$refs.modal_politica.show()
        },
        aceptar() {
            this.de_acuerdo = false
            this.$emit('aceptar', true)
        },
    }
}
</script>