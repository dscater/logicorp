<script setup>
import { useApp } from "@/composables/useApp";
import { Head, Link } from "@inertiajs/vue3";
import { useViajes } from "@/composables/viajes/useViajes";
import { initDataTable } from "@/composables/datatable.js";
import { ref, onMounted, onBeforeUnmount } from "vue";
import PanelToolbar from "@/Components/PanelToolbar.vue";
import Formulario from "./Formulario.vue";
import Detalle from "./Detalle.vue";
const { setLoading } = useApp();
const props = defineProps({
    programacion: {
        type: Object,
        default: null,
    },
});
onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);
});

const { getViajes, setViaje, limpiarViaje, deleteViaje, oViaje } = useViajes();

const columns = [
    {
        title: "",
        data: "id",
    },
    {
        title: "VOLUMEN PROGRAMADO",
        data: "volumen_programado",
    },
    {
        title: "TRAMO",
        data: "tramo",
    },
    {
        title: "NÓMINA",
        data: "nomina",
    },
    {
        title: "RESOLUCIÓN",
        data: "resolucion",
    },
    {
        title: "FECHA DE REGISTRO",
        data: "fecha_registro_t",
    },
    {
        title: "ACCIONES",
        data: null,
        render: function (data, type, row) {
            return `
                <button class="mx-0 rounded-0 btn btn-primary detalle" data-id="${
                    row.id
                }"><i class="fa fa-list"></i></button>
                <button class="mx-0 rounded-0 btn btn-warning editar" data-id="${
                    row.id
                }"><i class="fa fa-edit"></i></button>
                <button class="mx-0 rounded-0 btn btn-danger eliminar"
                 data-id="${row.id}" 
                 data-nombre="${row.volumen_programado}|${row.tramo}" 
                 data-url="${route(
                     "viajes.destroy",
                     row.id
                 )}"><i class="fa fa-trash"></i></button>
            `;
        },
    },
];
const loading = ref(false);
const accion_dialog = ref(0);
const open_dialog = ref(false);
const accion_dialog_det = ref(0);
const open_dialog_det = ref(false);

const agregarRegistro = () => {
    limpiarViaje();
    oViaje.value.programacion_id = props.programacion.id;
    oViaje.value.programacion = props.programacion;
    accion_dialog.value = 0;
    open_dialog.value = true;
};

const accionesRow = () => {
    // detalle
    $("#table-viaje").on("click", "button.detalle", function (e) {
        e.preventDefault();
        let id = $(this).attr("data-id");
        axios.get(route("viajes.show", id)).then((response) => {
            setViaje(response.data, true);
            accion_dialog_det.value = 1;
            open_dialog_det.value = true;
        });
    });
    // editar
    $("#table-viaje").on("click", "button.editar", function (e) {
        e.preventDefault();
        let id = $(this).attr("data-id");
        axios.get(route("viajes.show", id)).then((response) => {
            setViaje(response.data, true);
            accion_dialog.value = 1;
            open_dialog.value = true;
        });
    });
    // eliminar
    $("#table-viaje").on("click", "button.eliminar", function (e) {
        e.preventDefault();
        let nombre = $(this).attr("data-nombre");
        let id = $(this).attr("data-id");
        Swal.fire({
            title: "¿Quierés eliminar este registro?",
            html: `<strong>${nombre}</strong>`,
            showCancelButton: true,
            confirmButtonColor: "#B61431",
            confirmButtonText: "Si, eliminar",
            cancelButtonText: "No, cancelar",
            denyButtonText: `No, cancelar`,
        }).then(async (result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                let respuesta = await deleteViaje(id);
                if (respuesta && respuesta.sw) {
                    updateDatatable();
                }
            }
        });
    });
};

var datatable = null;
const datatableInitialized = ref(false);
const updateDatatable = () => {
    datatable.ajax.reload();
};

onMounted(async () => {
    datatable = initDataTable(
        "#table-viaje",
        columns,
        route("viajes.api", props.programacion.id)
    );
    datatableInitialized.value = true;
    accionesRow();
});
onBeforeUnmount(() => {
    if (datatable) {
        datatable.clear();
        datatable.destroy(false); // Destruye la instancia del DataTable
        datatable = null;
        datatableInitialized.value = false;
    }
});
</script>
<template>
    <Head title="Viajes"></Head>

    <!-- BEGIN breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:;">Inicio</a></li>
        <li class="breadcrumb-item">
            <Link :href="route('programacions.index')">Programación</Link>
        </li>
        <li class="breadcrumb-item active">Viajes</li>
    </ol>
    <!-- END breadcrumb -->
    <!-- BEGIN page-header -->
    <h1 class="page-header">Programación > Viajes</h1>
    <!-- END page-header -->

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN panel -->
            <div class="panel panel-inverse">
                <!-- BEGIN panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title btn-nuevo">
                        <Link
                            :href="route('programacions.index')"
                            class="btn btn-outline-default d-inline-block"
                        >
                            <i class="fa fa-arrow-left"></i> Volver
                        </Link>
                        <button
                            type="button"
                            class="btn btn-primary mx-2"
                            @click="agregarRegistro"
                        >
                            <i class="fa fa-plus"></i> Nuevo
                        </button>
                    </h4>
                    <panel-toolbar
                        :mostrar_loading="loading"
                        @loading="updateDatatable"
                    />
                </div>
                <!-- END panel-heading -->
                <!-- BEGIN panel-body -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4>
                                        Programación:
                                        {{ props.programacion.full_name }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table
                        id="table-viaje"
                        width="100%"
                        class="table table-striped table-bordered align-middle text-nowrap tabla_datos"
                    >
                        <thead>
                            <tr>
                                <th width="2%"></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th width="2%"></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!-- END panel-body -->
            </div>
            <!-- END panel -->
        </div>
    </div>

    <Formulario
        :open_dialog="open_dialog"
        :accion_dialog="accion_dialog"
        @envio-formulario="updateDatatable"
        @cerrar-dialog="open_dialog = false"
    ></Formulario>

    <Detalle
        :open_dialog="open_dialog_det"
        :accion_dialog="accion_dialog_det"
        @cerrar-dialog="open_dialog_det = false"
    ></Detalle>
</template>
