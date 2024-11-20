import axios from "axios";
import { onMounted, reactive } from "vue";
import { usePage } from "@inertiajs/vue3";

const oAsignacion = reactive({
    id: 0,
    contrato_id: "",
    asignacion_detalles: [],
    eliminados: [],
    _method: "POST",
});

export const useAsignacions = () => {
    const { flash } = usePage().props;
    const getAsignacions = async () => {
        try {
            const response = await axios.get(route("asignacions.listado"), {
                headers: { Accept: "application/json" },
            });
            return response.data.asignacions;
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

    const getAsignacionsByTipo = async (data) => {
        try {
            const response = await axios.get(route("asignacions.byTipo"), {
                headers: { Accept: "application/json" },
                params: data,
            });
            return response.data.asignacions;
        } catch (error) {
            console.error("Error:", error);
            throw error; // Puedes manejar el error según tus necesidades
        }
    };

    const getAsignacionsApi = async (data) => {
        try {
            const response = await axios.get(
                route("asignacions.paginado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.asignacions;
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
    const saveAsignacion = async (data) => {
        try {
            const response = await axios.post(
                route("asignacions.store", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
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

    const deleteAsignacion = async (id) => {
        try {
            const response = await axios.delete(
                route("asignacions.destroy", id),
                {
                    headers: { Accept: "application/json" },
                }
            );
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

    const setAsignacion = (item = null, detalle = false) => {
        if (item) {
            oAsignacion.id = item.id;
            oAsignacion.contrato_id = item.contrato_id;
            oAsignacion.asignacion_detalles = item.asignacion_detalles;
            oAsignacion.eliminados = [];
            oAsignacion._method = "PUT";
            if (detalle) {
                oAsignacion.asignacion_detalles = item.asignacion_detalles;
                oAsignacion.contrato = item.contrato;
                oAsignacion.fecha_registro_t = item.fecha_registro_t;
            }
            return oAsignacion;
        }
        return false;
    };

    const limpiarAsignacion = () => {
        oAsignacion.id = 0;
        oAsignacion.contrato_id = "";
        oAsignacion.nro_lote = "";
        oAsignacion.empresa_id = "";
        oAsignacion.p_asignado = "";
        oAsignacion.asignacion_detalles = [];
        oAsignacion.eliminados = [];
        oAsignacion._method = "POST";
    };

    onMounted(() => {});

    return {
        oAsignacion,
        getAsignacions,
        getAsignacionsApi,
        saveAsignacion,
        deleteAsignacion,
        setAsignacion,
        limpiarAsignacion,
        getAsignacionsByTipo,
    };
};
