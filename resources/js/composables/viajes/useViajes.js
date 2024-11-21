import axios from "axios";
import { onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oViaje = ref({
    id: 0,
    programacion_id: "",
    volumen_programado: "",
    tramo: "",
    nomina: "",
    resolucion: "",
    dim: "",
    estado: "",
    observaciones: "",
    fecha_carga: "",
    volumen_cargado: "",
    total: "",
    cre_carga: "",
    volumen_recepcionado: "",
    total2: "",
    mermas: "",
    dif_litros: "",
    merma_ypfb: "",
    merma_cobrar: "",
    volumen_facturar: "",
    fecha_descarga: "",
    segun_cre: "",
    factura_lote: "",
    atq_lapaz: "",
    mes_servicio: "",
    dim2: "",
    crt: "",
    vol_crtm3: "",
    peso_crt: "",
    planta_carga_crt: "",
    fecha_cruce_frontera: "",
    mic_dta: "",
    vol_mic: "",
    peso_mic: "",
    parte_recepcion: "",
    vol_parte_mic: "",
    vol_parte_lts: "",
    peso_parte: "",
    observaciones2: "",
    nro_solicitud_hr: "",
    nro_ruta: "",
    fecha_hr: "",
    observaciones3: "",
    nro_fac_albodab: "",
    fecha_factura: "",
    importe_bs: "",
    observaciones4: "",
    observaciones_general: "",
    _method: "POST",
});

export const useViajes = () => {
    const { flash } = usePage().props;
    const getViajes = async () => {
        try {
            const response = await axios.get(route("viajes.listado"), {
                headers: { Accept: "application/json" },
            });
            return response.data.viajes;
        } catch (err) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : err.response?.data
                        ? err.response?.data?.message
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            throw err; // Puedes manejar el error según tus necesidades
        }
    };

    const getViajesByTipo = async (data) => {
        try {
            const response = await axios.get(route("viajes.byTipo"), {
                headers: { Accept: "application/json" },
                params: data,
            });
            return response.data.viajes;
        } catch (error) {
            console.error("Error:", error);
            throw error; // Puedes manejar el error según tus necesidades
        }
    };

    const getViajesApi = async (data) => {
        try {
            const response = await axios.get(route("viajes.paginado", data), {
                headers: { Accept: "application/json" },
            });
            return response.data.viajes;
        } catch (err) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : err.response?.data
                        ? err.response?.data?.message
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            throw err; // Puedes manejar el error según tus necesidades
        }
    };
    const saveViaje = async (data) => {
        try {
            const response = await axios.post(route("viajes.store", data), {
                headers: { Accept: "application/json" },
            });
            Swal.fire({
                icon: "success",
                title: "Correcto",
                text: `${flash.bien ? flash.bien : "Proceso realizado"}`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            return response.data;
        } catch (err) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : err.response?.data
                        ? err.response?.data?.message
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            throw err; // Puedes manejar el error según tus necesidades
        }
    };

    const deleteViaje = async (id) => {
        try {
            const response = await axios.delete(route("viajes.destroy", id), {
                headers: { Accept: "application/json" },
            });
            Swal.fire({
                icon: "success",
                title: "Correcto",
                text: `${flash.bien ? flash.bien : "Proceso realizado"}`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            return response.data;
        } catch (err) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : err.response?.data
                        ? err.response?.data?.message
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            throw err; // Puedes manejar el error según tus necesidades
        }
    };

    const setViaje = (item = null, programacion = false) => {
        if (item) {
            oViaje.value.id = item.id;
            oViaje.value.programacion_id = item.programacion_id;
            oViaje.value.volumen_programado = item.volumen_programado;
            oViaje.value.tramo = item.tramo;
            oViaje.value.nomina = item.nomina;
            oViaje.value.resolucion = item.resolucion;
            oViaje.value.dim = item.dim;
            oViaje.value.estado = item.estado;
            oViaje.value.observaciones = item.observaciones;
            oViaje.value.fecha_carga = item.fecha_carga;
            oViaje.value.volumen_cargado = item.volumen_cargado;
            oViaje.value.total = item.total;
            oViaje.value.cre_carga = item.cre_carga;
            oViaje.value.volumen_recepcionado = item.volumen_recepcionado;
            oViaje.value.total2 = item.total2;
            oViaje.value.mermas = item.mermas;
            oViaje.value.dif_litros = item.dif_litros;
            oViaje.value.merma_ypfb = item.merma_ypfb;
            oViaje.value.merma_cobrar = item.merma_cobrar;
            oViaje.value.volumen_facturar = item.volumen_facturar;
            oViaje.value.fecha_descarga = item.fecha_descarga;
            oViaje.value.segun_cre = item.segun_cre;
            oViaje.value.factura_lote = item.factura_lote;
            oViaje.value.atq_lapaz = item.atq_lapaz;
            oViaje.value.mes_servicio = item.mes_servicio;
            oViaje.value.dim2 = item.dim2;
            oViaje.value.crt = item.crt;
            oViaje.value.vol_crtm3 = item.vol_crtm3;
            oViaje.value.peso_crt = item.peso_crt;
            oViaje.value.planta_carga_crt = item.planta_carga_crt;
            oViaje.value.fecha_cruce_frontera = item.fecha_cruce_frontera;
            oViaje.value.mic_dta = item.mic_dta;
            oViaje.value.vol_mic = item.vol_mic;
            oViaje.value.peso_mic = item.peso_mic;
            oViaje.value.parte_recepcion = item.parte_recepcion;
            oViaje.value.vol_parte_mic = item.vol_parte_mic;
            oViaje.value.vol_parte_lts = item.vol_parte_lts;
            oViaje.value.peso_parte = item.peso_parte;
            oViaje.value.observaciones2 = item.observaciones2;
            oViaje.value.nro_solicitud_hr = item.nro_solicitud_hr;
            oViaje.value.nro_ruta = item.nro_ruta;
            oViaje.value.fecha_hr = item.fecha_hr;
            oViaje.value.observaciones3 = item.observaciones3;
            oViaje.value.nro_fac_albodab = item.nro_fac_albodab;
            oViaje.value.fecha_factura = item.fecha_factura;
            oViaje.value.importe_bs = item.importe_bs;
            oViaje.value.observaciones4 = item.observaciones4;
            oViaje.value.observaciones_general = item.observaciones_general;
            if (programacion) {
                oViaje.value.programacion = item.programacion;
            }
            oViaje.value._method = "PUT";
            return oViaje;
        }
        return false;
    };

    const limpiarViaje = () => {
        oViaje.value.id = 0;
        oViaje.value.programacion_id = "";
        oViaje.value.volumen_programado = "";
        oViaje.value.tramo = "";
        oViaje.value.nomina = "";
        oViaje.value.resolucion = "";
        oViaje.value.dim = "";
        oViaje.value.estado = "";
        oViaje.value.observaciones = "";
        oViaje.value.fecha_carga = "";
        oViaje.value.volumen_cargado = "";
        oViaje.value.total = "";
        oViaje.value.cre_carga = "";
        oViaje.value.volumen_recepcionado = "";
        oViaje.value.total2 = "";
        oViaje.value.mermas = "";
        oViaje.value.dif_litros = "";
        oViaje.value.merma_ypfb = "";
        oViaje.value.merma_cobrar = "";
        oViaje.value.volumen_facturar = "";
        oViaje.value.fecha_descarga = "";
        oViaje.value.segun_cre = "";
        oViaje.value.factura_lote = "";
        oViaje.value.atq_lapaz = "";
        oViaje.value.mes_servicio = "";
        oViaje.value.dim2 = "";
        oViaje.value.crt = "";
        oViaje.value.vol_crtm3 = "";
        oViaje.value.peso_crt = "";
        oViaje.value.planta_carga_crt = "";
        oViaje.value.fecha_cruce_frontera = "";
        oViaje.value.mic_dta = "";
        oViaje.value.vol_mic = "";
        oViaje.value.peso_mic = "";
        oViaje.value.parte_recepcion = "";
        oViaje.value.vol_parte_mic = "";
        oViaje.value.vol_parte_lts = "";
        oViaje.value.peso_parte = "";
        oViaje.value.observaciones2 = "";
        oViaje.value.nro_solicitud_hr = "";
        oViaje.value.nro_ruta = "";
        oViaje.value.fecha_hr = "";
        oViaje.value.observaciones3 = "";
        oViaje.value.nro_fac_albodab = "";
        oViaje.value.fecha_factura = "";
        oViaje.value.importe_bs = "";
        oViaje.value.observaciones4 = "";
        oViaje.value.observaciones_general = "";
        oViaje.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oViaje,
        getViajes,
        getViajesApi,
        saveViaje,
        deleteViaje,
        setViaje,
        limpiarViaje,
        getViajesByTipo,
    };
};
