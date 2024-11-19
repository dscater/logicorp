import axios from "axios";
import { onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oVehiculo = ref({
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

export const useVehiculos = () => {
    const { flash } = usePage().props;
    const getVehiculos = async () => {
        try {
            const response = await axios.get(route("vehiculos.listado"), {
                headers: { Accept: "application/json" },
            });
            return response.data.vehiculos;
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

    const getVehiculosByTipo = async (data) => {
        try {
            const response = await axios.get(route("vehiculos.byTipo"), {
                headers: { Accept: "application/json" },
                params: data,
            });
            return response.data.vehiculos;
        } catch (error) {
            console.error("Error:", error);
            throw error; // Puedes manejar el error según tus necesidades
        }
    };

    const getVehiculosApi = async (data) => {
        try {
            const response = await axios.get(
                route("vehiculos.paginado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.vehiculos;
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
    const saveVehiculo = async (data) => {
        try {
            const response = await axios.post(route("vehiculos.store", data), {
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

    const deleteVehiculo = async (id) => {
        try {
            const response = await axios.delete(
                route("vehiculos.destroy", id),
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

    const setVehiculo = (item = null) => {
        if (item) {
            oVehiculo.value.id = item.id;
            oVehiculo.value.nombre = item.nombre;
            oVehiculo.value.paterno = item.paterno;
            oVehiculo.value.materno = item.materno;
            oVehiculo.value.ci = item.ci;
            oVehiculo.value.ci_exp = item.ci_exp;
            oVehiculo.value.nacionalidad = item.nacionalidad;
            oVehiculo.value.fecha_nac = item.fecha_nac;
            oVehiculo.value.sexo = item.sexo;
            oVehiculo.value.estado_civil = item.estado_civil;
            oVehiculo.value.nro_licencia = item.nro_licencia;
            oVehiculo.value.categoria = item.categoria;
            oVehiculo.value.fecha_emision = item.fecha_emision;
            oVehiculo.value.fecha_vencimiento = item.fecha_vencimiento;
            oVehiculo.value.fono = item.fono;
            oVehiculo.value.foto = item.foto;
            oVehiculo.value.observacion = item.observacion;
            oVehiculo.value._method = "PUT";
            return oVehiculo;
        }
        return false;
    };

    const limpiarVehiculo = () => {
        oVehiculo.value.id = 0;
        oVehiculo.value.nombre = "";
        oVehiculo.value.paterno = "";
        oVehiculo.value.materno = "";
        oVehiculo.value.ci = "";
        oVehiculo.value.ci_exp = "";
        oVehiculo.value.nacionalidad = "";
        oVehiculo.value.fecha_nac = "";
        oVehiculo.value.sexo = "";
        oVehiculo.value.estado_civil = "";
        oVehiculo.value.nro_licencia = "";
        oVehiculo.value.categoria = "";
        oVehiculo.value.fecha_emision = "";
        oVehiculo.value.fecha_vencimiento = "";
        oVehiculo.value.fono = "";
        oVehiculo.value.foto = null;
        oVehiculo.value.observacion = "";
        oVehiculo.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oVehiculo,
        getVehiculos,
        getVehiculosApi,
        saveVehiculo,
        deleteVehiculo,
        setVehiculo,
        limpiarVehiculo,
        getVehiculosByTipo,
    };
};
