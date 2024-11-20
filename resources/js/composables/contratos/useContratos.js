import axios from "axios";
import { onMounted, reactive } from "vue";
import { usePage } from "@inertiajs/vue3";

const oContrato = reactive({
    id: 0,
    codigo: "",
    nro_lote: "",
    empresa_id: "",
    p_asignado: "",
    contrato_detalles: [],
    eliminados: [],
    _method: "POST",
});

export const useContratos = () => {
    const { flash } = usePage().props;
    const getContratos = async () => {
        try {
            const response = await axios.get(route("contratos.listado"), {
                headers: { Accept: "application/json" },
            });
            return response.data.contratos;
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

    const getContratosByTipo = async (data) => {
        try {
            const response = await axios.get(route("contratos.byTipo"), {
                headers: { Accept: "application/json" },
                params: data,
            });
            return response.data.contratos;
        } catch (error) {
            console.error("Error:", error);
            throw error; // Puedes manejar el error según tus necesidades
        }
    };

    const getContratosApi = async (data) => {
        try {
            const response = await axios.get(
                route("contratos.paginado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.contratos;
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
    const saveContrato = async (data) => {
        try {
            const response = await axios.post(route("contratos.store", data), {
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

    const deleteContrato = async (id) => {
        try {
            const response = await axios.delete(
                route("contratos.destroy", id),
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

    const setContrato = (item = null, detalle = false) => {
        if (item) {
            oContrato.id = item.id;
            oContrato.codigo = item.codigo;
            oContrato.nro_lote = item.nro_lote;
            oContrato.empresa_id = item.empresa_id;
            oContrato.p_asignado = item.p_asignado;
            oContrato.contrato_detalles = item.contrato_detalles;
            oContrato.eliminados = [];
            oContrato._method = "PUT";
            if (detalle) {
                oContrato.empresa = item.empresa;
                oContrato.fecha_registro_t = item.fecha_registro_t;
            }
            return oContrato;
        }
        return false;
    };

    const limpiarContrato = () => {
        oContrato.id = 0;
        oContrato.codigo = "";
        oContrato.nro_lote = "";
        oContrato.empresa_id = "";
        oContrato.p_asignado = "";
        oContrato.contrato_detalles = [];
        oContrato.eliminados = [];
        oContrato._method = "POST";
    };

    onMounted(() => {});

    return {
        oContrato,
        getContratos,
        getContratosApi,
        saveContrato,
        deleteContrato,
        setContrato,
        limpiarContrato,
        getContratosByTipo,
    };
};
