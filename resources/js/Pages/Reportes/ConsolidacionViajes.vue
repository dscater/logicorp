<script setup>
import { useApp } from "@/composables/useApp";
import { computed, onMounted, ref } from "vue";
import { Head, usePage } from "@inertiajs/vue3";
import { useEmpresas } from "@/composables/empresas/useEmpresas";
const { setLoading } = useApp();

const { getEmpresas } = useEmpresas();

const obtenerFechaActual = () => {
    const fecha = new Date();
    const anio = fecha.getFullYear();
    const mes = String(fecha.getMonth() + 1).padStart(2, "0"); // Mes empieza desde 0
    const dia = String(fecha.getDate()).padStart(2, "0"); // Día del mes

    return `${anio}-${mes}-${dia}`;
};

const form = ref({
    empresa_id: "todos",
    asociacion_id: "todos",
    fecha_ini: obtenerFechaActual(),
    fecha_fin: obtenerFechaActual(),
});

const generando = ref(false);
const txtBtn = computed(() => {
    if (generando.value) {
        return "Generando Reporte...";
    }
    return "Generar Reporte";
});

const listEmpresas = ref([]);
const listAsociacions = ref([]);

const generarReporte = () => {
    generando.value = true;
    const url = route("reportes.r_consolidacion_viajes", form.value);
    window.open(url, "_blank");
    setTimeout(() => {
        generando.value = false;
    }, 500);
};

const cargarEmpresas = async () => {
    listEmpresas.value = await getEmpresas({ tipo: "EMPRESA" });
    listEmpresas.value.unshift({ id: "todos", razon_social: "TODOS" });
};

const cargarAsociacions = async () => {
    listAsociacions.value = await getEmpresas({ tipo: "ASOCIACIÓN" });
    listAsociacions.value.unshift({ id: "todos", razon_social: "TODOS" });
};

const cargarListas = () => {
    cargarEmpresas();
    cargarAsociacions();
};

onMounted(() => {
    cargarListas();
    setTimeout(() => {
        setLoading(false);
    }, 300);
});
</script>
<template>
    <Head title="Reporte Consolidación de viajes"></Head>
    <!-- BEGIN breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:;">Inicio</a></li>
        <li class="breadcrumb-item active">
            Reportes > Consolidación de viajes
        </li>
    </ol>
    <!-- END breadcrumb -->
    <!-- BEGIN page-header -->
    <h1 class="page-header">Reportes > Consolidación de viajes</h1>
    <!-- END page-header -->
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form @submit.prevent="generarReporte">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Seleccionar empresa*</label>
                                <select
                                    v-model="form.empresa_id"
                                    class="form-control"
                                >
                                    <option
                                        v-for="item in listEmpresas"
                                        :value="item.id"
                                    >
                                        {{ item.razon_social }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-12 mt-10px">
                                <label>Seleccionar asociación*</label>
                                <select
                                    v-model="form.asociacion_id"
                                    class="form-control"
                                >
                                    <option
                                        v-for="item in listAsociacions"
                                        :value="item.id"
                                    >
                                        {{ item.razon_social }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-12 mt-10px">
                                <label>Rango de fechas</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input
                                            type="date"
                                            class="form-control"
                                            v-model="form.fecha_ini"
                                        />
                                    </div>
                                    <div class="col-md-6">
                                        <input
                                            type="date"
                                            class="form-control"
                                            v-model="form.fecha_fin"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 text-center mt-3">
                                <button
                                    class="btn btn-primary"
                                    block
                                    @click="generarReporte"
                                    :disabled="generando"
                                    v-text="txtBtn"
                                ></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
