<script setup>
import { useApp } from "@/composables/useApp";
import { Head, Link, router } from "@inertiajs/vue3";
import { useProgramacions } from "@/composables/programacions/useProgramacions";
import { initDataTable } from "@/composables/datatable.js";
import { ref, onMounted, onBeforeUnmount } from "vue";
import PanelToolbar from "@/Components/PanelToolbar.vue";
import Formulario from "./Formulario.vue";
const { setLoading } = useApp();
onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);
});

const {
    getProgramacions,
    setProgramacion,
    limpiarProgramacion,
    deleteProgramacion,
} = useProgramacions();

const columns = [
    {
        title: "",
        data: "id",
    },
    {
        title: "COD. CONTRATO",
        data: "contrato.codigo",
    },
    {
        title: "EMPRESA",
        data: "empresa.razon_social",
    },
    {
        title: "ASOCIACIÓN",
        data: "asociacion.razon_social",
    },
    {
        title: "PRODUCTO",
        data: "producto.nombre",
    },
    {
        title: "PROVEEDOR",
        data: "proveedor.razon_social",
    },
    {
        title: "VEHICULO",
        data: "vehiculo.full_name",
    },
    {
        title: "CONDUCTOR",
        data: "conductor.full_name",
    },
    {
        title: "ORIGEN/DESTNIO",
        data: "origen_destino",
    },
    {
        title: "FRONTERA",
        data: "frontera",
    },
    {
        title: "FECHA DE PROGRAMACIÓN",
        data: "fecha_programacion_t",
    },
    {
        title: "DESCRIPCIÓN",
        data: "descripcion",
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
                <button class="mx-0 rounded-0 btn btn-success viajes" title="Viajes" data-id="${
                    row.id
                }">V</button>
                
                <button class="mx-0 rounded-0 btn btn-info pagos" title="Pagos" data-id="${
                    row.id
                }">P</button>
                <button class="mx-0 rounded-0 btn btn-warning editar" data-id="${
                    row.id
                }"><i class="fa fa-edit"></i></button>
                <button class="mx-0 rounded-0 btn btn-danger eliminar"
                 data-id="${row.id}" 
                 data-nombre="${row.contrato.codigo}|${
                row.empresa.razon_social
            }|${row.asociacion.razon_social}" 
                 data-url="${route(
                     "programacions.destroy",
                     row.id
                 )}"><i class="fa fa-trash"></i></button>
            `;
        },
    },
];
const loading = ref(false);
const accion_dialog = ref(0);
const open_dialog = ref(false);

const agregarRegistro = () => {
    limpiarProgramacion();
    accion_dialog.value = 0;
    open_dialog.value = true;
};

const accionesRow = () => {
    // viajes
    $("#table-programacion").on("click", "button.viajes", function (e) {
        e.preventDefault();
        let id = $(this).attr("data-id");
        router.get(route("viajes.index", id));
    });
    // pagos
    $("#table-programacion").on("click", "button.pagos", function (e) {
        e.preventDefault();
        let id = $(this).attr("data-id");
        router.get(route("pagos.index", id));
    });
    // editar
    $("#table-programacion").on("click", "button.editar", function (e) {
        e.preventDefault();
        let id = $(this).attr("data-id");
        axios.get(route("programacions.show", id)).then((response) => {
            setProgramacion(response.data);
            accion_dialog.value = 1;
            open_dialog.value = true;
        });
    });
    // eliminar
    $("#table-programacion").on("click", "button.eliminar", function (e) {
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
                let respuesta = await deleteProgramacion(id);
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
        "#table-programacion",
        columns,
        route("programacions.api")
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
    <Head title="Programación"></Head>

    <!-- BEGIN breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:;">Inicio</a></li>
        <li class="breadcrumb-item active">Programación</li>
    </ol>
    <!-- END breadcrumb -->
    <!-- BEGIN page-header -->
    <h1 class="page-header">Programación</h1>
    <!-- END page-header -->

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN panel -->
            <div class="panel panel-inverse">
                <!-- BEGIN panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title btn-nuevo">
                        <button
                            type="button"
                            class="btn btn-primary"
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
                    <table
                        id="table-programacion"
                        width="100%"
                        class="table table-striped table-bordered align-middle text-nowrap tabla_datos"
                    >
                        <thead></thead>
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
</template>
