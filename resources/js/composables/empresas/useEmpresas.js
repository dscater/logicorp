import axios from "axios";
import { onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oEmpresa = ref({
    id: 0,
    razon_social: "",
    nit: "",
    nom_representante: "",
    ap_representante: "",
    fono: "",
    correo: "",
    descripcion: "",
    tipo: "",
    _method: "POST",
});

export const useEmpresas = () => {
    const { flash } = usePage().props;
    const getEmpresas = async () => {
        try {
            const response = await axios.get(route("empresas.listado"), {
                headers: { Accept: "application/json" },
            });
            return response.data.empresas;
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

    const getEmpresasByTipo = async (data) => {
        try {
            const response = await axios.get(route("empresas.byTipo"), {
                headers: { Accept: "application/json" },
                params: data,
            });
            return response.data.empresas;
        } catch (error) {
            console.error("Error:", error);
            throw error; // Puedes manejar el error según tus necesidades
        }
    };

    const getEmpresasApi = async (data) => {
        try {
            const response = await axios.get(
                route("empresas.paginado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.empresas;
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
    const saveEmpresa = async (data) => {
        try {
            const response = await axios.post(route("empresas.store", data), {
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

    const deleteEmpresa = async (id) => {
        try {
            const response = await axios.delete(
                route("empresas.destroy", id),
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

    const setEmpresa = (item = null) => {
        if (item) {
            oEmpresa.value.id = item.id;
            oEmpresa.value.razon_social = item.razon_social;
            oEmpresa.value.nit = item.nit;
            oEmpresa.value.nom_representante = item.nom_representante;
            oEmpresa.value.ap_representante = item.ap_representante;
            oEmpresa.value.fono = item.fono;
            oEmpresa.value.correo = item.correo;
            oEmpresa.value.descripcion = item.descripcion;
            oEmpresa.value.tipo = item.tipo;
            oEmpresa.value._method = "PUT";
            return oEmpresa;
        }
        return false;
    };

    const limpiarEmpresa = () => {
        oEmpresa.value.id = 0;
        oEmpresa.value.razon_social = "";
        oEmpresa.value.nit = "";
        oEmpresa.value.nom_representante = "";
        oEmpresa.value.ap_representante = "";
        oEmpresa.value.fono = "";
        oEmpresa.value.correo = "";
        oEmpresa.value.descripcion = "";
        oEmpresa.value.tipo = "";
        oEmpresa.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oEmpresa,
        getEmpresas,
        getEmpresasApi,
        saveEmpresa,
        deleteEmpresa,
        setEmpresa,
        limpiarEmpresa,
        getEmpresasByTipo,
    };
};
