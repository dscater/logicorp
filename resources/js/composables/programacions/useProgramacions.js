import axios from "axios";
import { onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oProgramacion = ref({
    id: 0,
    contrato_id: "",
    empresa_id: "",
    asociacion_id: "",
    producto_id: "",
    proveedor_id: "",
    vehiculo_id: "",
    conductor_id: "",
    origen_destino: "",
    frontera: "",
    fecha_programacion: "",
    descripcion: "",
    _method: "POST",
});

export const useProgramacions = () => {
    const { flash } = usePage().props;
    const getProgramacions = async () => {
        try {
            const response = await axios.get(route("programacions.listado"), {
                headers: { Accept: "application/json" },
            });
            return response.data.programacions;
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

    const getProgramacionsByTipo = async (data) => {
        try {
            const response = await axios.get(route("programacions.byTipo"), {
                headers: { Accept: "application/json" },
                params: data,
            });
            return response.data.programacions;
        } catch (error) {
            console.error("Error:", error);
            throw error; // Puedes manejar el error según tus necesidades
        }
    };

    const getProgramacionsApi = async (data) => {
        try {
            const response = await axios.get(
                route("programacions.paginado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.programacions;
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
    const saveProgramacion = async (data) => {
        try {
            const response = await axios.post(
                route("programacions.store", data),
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

    const deleteProgramacion = async (id) => {
        try {
            const response = await axios.delete(
                route("programacions.destroy", id),
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

    const setProgramacion = (item = null) => {
        if (item) {
            oProgramacion.value.id = item.id;
            oProgramacion.value.contrato_id = item.contrato_id;
            oProgramacion.value.empresa_id = item.empresa_id;
            oProgramacion.value.asociacion_id = item.asociacion_id;
            oProgramacion.value.producto_id = item.producto_id;
            oProgramacion.value.proveedor_id = item.proveedor_id;
            oProgramacion.value.vehiculo_id = item.vehiculo_id;
            oProgramacion.value.conductor_id = item.conductor_id;
            oProgramacion.value.origen_destino = item.origen_destino;
            oProgramacion.value.frontera = item.frontera;
            oProgramacion.value.fecha_programacion = item.fecha_programacion;
            oProgramacion.value.descripcion = item.descripcion;
            oProgramacion.value._method = "PUT";
            return oProgramacion;
        }
        return false;
    };

    const limpiarProgramacion = () => {
        oProgramacion.value.id = 0;
        oProgramacion.value.contrato_id = "";
        oProgramacion.value.empresa_id = "";
        oProgramacion.value.asociacion_id = "";
        oProgramacion.value.producto_id = "";
        oProgramacion.value.proveedor_id = "";
        oProgramacion.value.vehiculo_id = "";
        oProgramacion.value.conductor_id = "";
        oProgramacion.value.origen_destino = "";
        oProgramacion.value.frontera = "";
        oProgramacion.value.fecha_programacion = "";
        oProgramacion.value.descripcion = "";
        oProgramacion.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oProgramacion,
        getProgramacions,
        getProgramacionsApi,
        saveProgramacion,
        deleteProgramacion,
        setProgramacion,
        limpiarProgramacion,
        getProgramacionsByTipo,
    };
};
