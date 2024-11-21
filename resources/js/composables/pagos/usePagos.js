import axios from "axios";
import { onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oPago = ref({
    id: 0,
    programacion_id: "",
    viaje_id: "",
    mes_anio: "",
    cto: "",
    fecha: "",
    retencion: "",
    desc_merma: "",
    total_pagado: "",
    _method: "POST",
});

export const usePagos = () => {
    const { flash } = usePage().props;
    const getPagos = async () => {
        try {
            const response = await axios.get(route("pagos.listado"), {
                headers: { Accept: "application/json" },
            });
            return response.data.pagos;
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

    const getPagosByTipo = async (data) => {
        try {
            const response = await axios.get(route("pagos.byTipo"), {
                headers: { Accept: "application/json" },
                params: data,
            });
            return response.data.pagos;
        } catch (error) {
            console.error("Error:", error);
            throw error; // Puedes manejar el error según tus necesidades
        }
    };

    const getPagosApi = async (data) => {
        try {
            const response = await axios.get(route("pagos.paginado", data), {
                headers: { Accept: "application/json" },
            });
            return response.data.pagos;
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
    const savePago = async (data) => {
        try {
            const response = await axios.post(route("pagos.store", data), {
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

    const deletePago = async (id) => {
        try {
            const response = await axios.delete(route("pagos.destroy", id), {
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

    const setPago = (item = null, programacion = false) => {
        if (item) {
            oPago.value.id = item.id;
            oPago.value.programacion_id = item.programacion_id;
            oPago.value.viaje_id = item.viaje_id;
            oPago.value.mes_anio = item.mes_anio;
            oPago.value.cto = item.cto;
            oPago.value.fecha = item.fecha;
            oPago.value.retencion = item.retencion;
            oPago.value.desc_merma = item.desc_merma;
            oPago.value.total_pagado = item.total_pagado;
            if (programacion) {
                oPago.value.programacion = item.programacion;
            }
            oPago.value._method = "PUT";
            return oPago;
        }
        return false;
    };

    const limpiarPago = () => {
        oPago.value.id = 0;
        oPago.value.programacion_id = "";
        oPago.value.viaje_id = "";
        oPago.value.mes_anio = "";
        oPago.value.cto = "";
        oPago.value.fecha = "";
        oPago.value.retencion = "";
        oPago.value.desc_merma = "";
        oPago.value.total_pagado = "";
        oPago.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oPago,
        getPagos,
        getPagosApi,
        savePago,
        deletePago,
        setPago,
        limpiarPago,
        getPagosByTipo,
    };
};
