<script setup>
import { useApp } from "@/composables/useApp";
import { computed, onMounted, ref } from "vue";
import { Head, usePage } from "@inertiajs/vue3";
import { useEmpresas } from "@/composables/empresas/useEmpresas";
import Highcharts from "highcharts";
import exporting from "highcharts/modules/exporting";
exporting(Highcharts);
Highcharts.setOptions({
    lang: {
        downloadPNG: "Descargar PNG",
        downloadJPEG: "Descargar JPEG",
        downloadPDF: "Descargar PDF",
        downloadSVG: "Descargar SVG",
        printChart: "Imprimir gráfico",
        contextButtonTitle: "Menú de exportación",
        viewFullscreen: "Pantalla completa",
        exitFullscreen: "Salir de pantalla completa",
    },
});
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
    axios
        .get(route("reportes.r_predicciones"), { params: form.value })
        .then((response) => {
            // Create the chart
            Highcharts.chart("container", {
                chart: {
                    type: "column",
                },
                title: {
                    align: "center",
                    text: "Viajes por volumenes",
                },
                subtitle: {
                    align: "left",
                    text: "",
                },
                accessibility: {
                    announceNewData: {
                        enabled: true,
                    },
                },
                xAxis: {
                    type: "category",
                },
                yAxis: {
                    title: {
                        text: "Total",
                    },
                },
                legend: {
                    enabled: false,
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: "{point.y:.2f}",
                        },
                    },
                },

                tooltip: {
                    headerFormat:
                        '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat:
                        '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b><br/>',
                },

                series: [
                    {
                        name: "Volumen",
                        colorByPoint: true,
                        data: response.data.data1,
                    },
                ],
            });

            // graf 2
            Highcharts.chart("container2", {
                chart: {
                    type: "column",
                },
                title: {
                    align: "center",
                    text: "Facturación por empresa",
                },
                subtitle: {
                    align: "left",
                    text: "",
                },
                accessibility: {
                    announceNewData: {
                        enabled: true,
                    },
                },
                xAxis: {
                    type: "category",
                },
                yAxis: {
                    title: {
                        text: "Total",
                    },
                },
                legend: {
                    enabled: false,
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: "{point.y:.2f}",
                        },
                    },
                },

                tooltip: {
                    headerFormat:
                        '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat:
                        '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b><br/>',
                },

                series: [
                    {
                        name: "Facturación",
                        colorByPoint: true,
                        data: response.data.data2,
                    },
                ],
            });

            let t = Math.floor(Math.random() * (4000 - 1500 + 1)) + 1500;
            setTimeout(() => {
                generando.value = false;
            }, t);
        })
        .catch((error) => {
            console.log(error);
            generando.value = false;
            Swal.fire({
                icon: "info",
                title: "Error",
                text: `Ocurrió un error inesperado, intente nuevamente por favor`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
        });
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
    <Head title="Reporte Predicciones"></Head>
    <!-- BEGIN breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:;">Inicio</a></li>
        <li class="breadcrumb-item active">Reportes > Predicciones</li>
    </ol>
    <!-- END breadcrumb -->
    <!-- BEGIN page-header -->
    <h1 class="page-header">Reportes > Predicciones</h1>
    <!-- END page-header -->
    <div class="row contenedor_pred">
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
                            <!-- <div class="col-md-12 mt-10px">
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
                            </div> -->
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
        <div class="loading" v-if="generando">
            Cargando<i class="fa fa-spinner fa-spin"></i>
        </div>
                <div class="col-12 mt-3" v-show="!generando" id="container"></div>
                <div class="col-12 mt-3" v-show="!generando" id="container2"></div>
    </div>
</template>
<style scoped>
.oculto {
    display: none;
}
.contenedor_pred {
    position: relative;
}

.loading {
    z-index: 3000;
    position: absolute;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    font-size: 2em;
    gap: 5px;
    background-color: rgba(0, 0, 0, 0.863);
}
</style>
