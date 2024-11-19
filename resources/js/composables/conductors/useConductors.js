import axios from "axios";
import { onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oConductor = ref({
    id: 0,
    nombre: "",
    paterno: "",
    materno: "",
    ci: "",
    ci_exp: "",
    nacionalidad: "",
    fecha_nac: "",
    sexo: "",
    estado_civil: "",
    nro_licencia: "",
    categoria: "",
    fecha_emision: "",
    fecha_vencimiento: "",
    fono: "",
    foto: null,
    observacion: "",
    _method: "POST",
});

export const useConductors = () => {
    const { flash } = usePage().props;
    const getConductors = async () => {
        try {
            const response = await axios.get(route("conductors.listado"), {
                headers: { Accept: "application/json" },
            });
            return response.data.conductors;
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

    const getConductorsByTipo = async (data) => {
        try {
            const response = await axios.get(route("conductors.byTipo"), {
                headers: { Accept: "application/json" },
                params: data,
            });
            return response.data.conductors;
        } catch (error) {
            console.error("Error:", error);
            throw error; // Puedes manejar el error según tus necesidades
        }
    };

    const getConductorsApi = async (data) => {
        try {
            const response = await axios.get(
                route("conductors.paginado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.conductors;
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
    const saveConductor = async (data) => {
        try {
            const response = await axios.post(route("conductors.store", data), {
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

    const deleteConductor = async (id) => {
        try {
            const response = await axios.delete(
                route("conductors.destroy", id),
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

    const setConductor = (item = null) => {
        if (item) {
            oConductor.value.id = item.id;
            oConductor.value.nombre = item.nombre;
            oConductor.value.paterno = item.paterno;
            oConductor.value.materno = item.materno;
            oConductor.value.ci = item.ci;
            oConductor.value.ci_exp = item.ci_exp;
            oConductor.value.nacionalidad = item.nacionalidad;
            oConductor.value.fecha_nac = item.fecha_nac;
            oConductor.value.sexo = item.sexo;
            oConductor.value.estado_civil = item.estado_civil;
            oConductor.value.nro_licencia = item.nro_licencia;
            oConductor.value.categoria = item.categoria;
            oConductor.value.fecha_emision = item.fecha_emision;
            oConductor.value.fecha_vencimiento = item.fecha_vencimiento;
            oConductor.value.fono = item.fono;
            oConductor.value.foto = item.foto;
            oConductor.value.observacion = item.observacion;
            oConductor.value._method = "PUT";
            return oConductor;
        }
        return false;
    };

    const limpiarConductor = () => {
        oConductor.value.id = 0;
        oConductor.value.nombre = "";
        oConductor.value.paterno = "";
        oConductor.value.materno = "";
        oConductor.value.ci = "";
        oConductor.value.ci_exp = "";
        oConductor.value.nacionalidad = "";
        oConductor.value.fecha_nac = "";
        oConductor.value.sexo = "";
        oConductor.value.estado_civil = "";
        oConductor.value.nro_licencia = "";
        oConductor.value.categoria = "";
        oConductor.value.fecha_emision = "";
        oConductor.value.fecha_vencimiento = "";
        oConductor.value.fono = "";
        oConductor.value.foto = null;
        oConductor.value.observacion = "";
        oConductor.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oConductor,
        getConductors,
        getConductorsApi,
        saveConductor,
        deleteConductor,
        setConductor,
        limpiarConductor,
        getConductorsByTipo,
    };
};
