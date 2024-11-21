<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { useViajes } from "@/composables/viajes/useViajes";
import { useProgramacions } from "@/composables/programacions/useProgramacions";
import { watch, ref, computed, defineEmits, onMounted, nextTick } from "vue";
const props = defineProps({
    open_dialog: {
        type: Boolean,
        default: false,
    },
    accion_dialog: {
        type: Number,
        default: 0,
    },
});

const { oViaje, limpiarViaje } = useViajes();
const { getProgramacions } = useProgramacions();

const accion = ref(props.accion_dialog);
const dialog = ref(props.open_dialog);
const listProgramacions = ref([]);
let form = useForm(oViaje.value);
watch(
    () => props.open_dialog,
    async (newValue) => {
        dialog.value = newValue;
        if (dialog.value) {
            cargarProgramacions();
            document
                .getElementsByTagName("body")[0]
                .classList.add("modal-open");
            form = useForm(oViaje.value);
        }
    }
);
watch(
    () => props.accion_dialog,
    (newValue) => {
        accion.value = newValue;
    }
);

const { flash } = usePage().props;

const tituloDialog = computed(() => {
    return accion.value == 0
        ? `<i class="fa fa-plus"></i> Agregar Registro`
        : `<i class="fa fa-edit"></i> Editar Registro`;
});

const enviarFormulario = () => {
    let url =
        form["_method"] == "POST"
            ? route("viajes.store", form.programacion.id)
            : route("viajes.update", form.id);

    form.post(url, {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            dialog.value = false;
            Swal.fire({
                icon: "success",
                title: "Correcto",
                text: `${flash.bien ? flash.bien : "Proceso realizado"}`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            limpiarViaje();
            emits("envio-formulario");
        },
        onError: (err) => {
            console.log("ERROR");
            Swal.fire({
                icon: "info",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : err.error
                        ? err.error
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
        },
    });
};

const emits = defineEmits(["cerrar-dialog", "envio-formulario"]);

watch(dialog, (newVal) => {
    if (!newVal) {
        emits("cerrar-dialog");
    }
});

const cerrarDialog = () => {
    dialog.value = false;
    document.getElementsByTagName("body")[0].classList.remove("modal-open");
};
const cargarProgramacions = async () => {
    listProgramacions.value = await getProgramacions();
};

onMounted(() => {});
</script>

<template>
    <div
        class="modal fade"
        :class="{
            show: dialog,
        }"
        id="modal-dialog-form"
        :style="{
            display: dialog ? 'block' : 'none',
        }"
    >
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h4 class="modal-title" v-html="tituloDialog"></h4>
                    <button
                        type="button"
                        class="btn-close"
                        @click="cerrarDialog()"
                    ></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="enviarFormulario()">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Programación*</label><br />
                                {{ form.programacion?.full_name }}
                            </div>
                            <div class="col-md-4">
                                <label>Volumen programado*</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.volumen_programado,
                                    }"
                                    v-model="form.volumen_programado"
                                />
                                <ul
                                    v-if="form.errors?.volumen_programado"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.volumen_programado }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Tramo*</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.tramo,
                                    }"
                                    v-model="form.tramo"
                                />
                                <ul
                                    v-if="form.errors?.tramo"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.tramo }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Nómina</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.nomina,
                                    }"
                                    v-model="form.nomina"
                                />
                                <ul
                                    v-if="form.errors?.nomina"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.nomina }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Resolución</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.resolucion,
                                    }"
                                    v-model="form.resolucion"
                                />
                                <ul
                                    v-if="form.errors?.resolucion"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.resolucion }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>DIM</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.dim,
                                    }"
                                    v-model="form.dim"
                                />
                                <ul
                                    v-if="form.errors?.dim"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.dim }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Estado</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.estado,
                                    }"
                                    v-model="form.estado"
                                />
                                <ul
                                    v-if="form.errors?.estado"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.estado }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Observaciones</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.observaciones,
                                    }"
                                    v-model="form.observaciones"
                                />
                                <ul
                                    v-if="form.errors?.observaciones"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.observaciones }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Fecha carga</label>
                                <input
                                    type="date"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.fecha_carga,
                                    }"
                                    v-model="form.fecha_carga"
                                />
                                <ul
                                    v-if="form.errors?.fecha_carga"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.fecha_carga }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Volumen cargado</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.volumen_cargado,
                                    }"
                                    v-model="form.volumen_cargado"
                                />
                                <ul
                                    v-if="form.errors?.volumen_cargado"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.volumen_cargado }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Total</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.total,
                                    }"
                                    v-model="form.total"
                                />
                                <ul
                                    v-if="form.errors?.total"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.total }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>CRE de carga N°</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.cre_carga,
                                    }"
                                    v-model="form.cre_carga"
                                />
                                <ul
                                    v-if="form.errors?.cre_carga"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.cre_carga }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Volumen recepcionado</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.volumen_recepcionado,
                                    }"
                                    v-model="form.volumen_recepcionado"
                                />
                                <ul
                                    v-if="form.errors?.volumen_recepcionado"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.volumen_recepcionado }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Total 3</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.total2,
                                    }"
                                    v-model="form.total2"
                                />
                                <ul
                                    v-if="form.errors?.total2"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.total2 }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Mermas</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.mermas,
                                    }"
                                    v-model="form.mermas"
                                />
                                <ul
                                    v-if="form.errors?.mermas"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.mermas }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Diferencia en litros</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.dif_litros,
                                    }"
                                    v-model="form.dif_litros"
                                />
                                <ul
                                    v-if="form.errors?.dif_litros"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.dif_litros }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Merma 0.15% Y.P.F.B.</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.merma_ypfb,
                                    }"
                                    v-model="form.merma_ypfb"
                                />
                                <ul
                                    v-if="form.errors?.merma_ypfb"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.merma_ypfb }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Mermas por cobrar</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.merma_cobrar,
                                    }"
                                    v-model="form.merma_cobrar"
                                />
                                <ul
                                    v-if="form.errors?.merma_cobrar"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.merma_cobrar }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Volumen a facturar</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.volumen_facturar,
                                    }"
                                    v-model="form.volumen_facturar"
                                />
                                <ul
                                    v-if="form.errors?.volumen_facturar"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.volumen_facturar }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Fecha de descarga</label>
                                <input
                                    type="date"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.fecha_descarga,
                                    }"
                                    v-model="form.fecha_descarga"
                                />
                                <ul
                                    v-if="form.errors?.fecha_descarga"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.fecha_descarga }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Según CRE N°</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.segun_cre,
                                    }"
                                    v-model="form.segun_cre"
                                />
                                <ul
                                    v-if="form.errors?.segun_cre"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.segun_cre }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Factura/Lote</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.factura_lote,
                                    }"
                                    v-model="form.factura_lote"
                                />
                                <ul
                                    v-if="form.errors?.factura_lote"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.factura_lote }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Arica-Tambo Quemado-La Paz</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.atq_lapaz,
                                    }"
                                    v-model="form.atq_lapaz"
                                />
                                <ul
                                    v-if="form.errors?.atq_lapaz"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.atq_lapaz }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Mes servicio</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.mes_servicio,
                                    }"
                                    v-model="form.mes_servicio"
                                />
                                <ul
                                    v-if="form.errors?.mes_servicio"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.mes_servicio }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>DIM</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.dim2,
                                    }"
                                    v-model="form.dim2"
                                />
                                <ul
                                    v-if="form.errors?.dim2"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.dim2 }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>CRT</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.crt,
                                    }"
                                    v-model="form.crt"
                                />
                                <ul
                                    v-if="form.errors?.crt"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.crt }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>VOL CRT M3</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.vol_crtm3,
                                    }"
                                    v-model="form.vol_crtm3"
                                />
                                <ul
                                    v-if="form.errors?.vol_crtm3"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.vol_crtm3 }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Peso CRT</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.peso_crt,
                                    }"
                                    v-model="form.peso_crt"
                                />
                                <ul
                                    v-if="form.errors?.peso_crt"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.peso_crt }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Planta de carga según CRT</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.planta_carga_crt,
                                    }"
                                    v-model="form.planta_carga_crt"
                                />
                                <ul
                                    v-if="form.errors?.planta_carga_crt"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.planta_carga_crt }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Fecha cruce frontera</label>
                                <input
                                    type="date"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.fecha_cruce_frontera,
                                    }"
                                    v-model="form.fecha_cruce_frontera"
                                />
                                <ul
                                    v-if="form.errors?.fecha_cruce_frontera"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.fecha_cruce_frontera }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>MIC/DTA</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.mic_dta,
                                    }"
                                    v-model="form.mic_dta"
                                />
                                <ul
                                    v-if="form.errors?.mic_dta"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.mic_dta }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Volumen según MIC</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.vol_mic,
                                    }"
                                    v-model="form.vol_mic"
                                />
                                <ul
                                    v-if="form.errors?.vol_mic"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.vol_mic }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Peso según MIC</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.peso_mic,
                                    }"
                                    v-model="form.peso_mic"
                                />
                                <ul
                                    v-if="form.errors?.peso_mic"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.peso_mic }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Parte de recepción</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.parte_recepcion,
                                    }"
                                    v-model="form.parte_recepcion"
                                />
                                <ul
                                    v-if="form.errors?.parte_recepcion"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.parte_recepcion }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Volumen según parte M3</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.vol_parte_mic,
                                    }"
                                    v-model="form.vol_parte_mic"
                                />
                                <ul
                                    v-if="form.errors?.vol_parte_mic"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.vol_parte_mic }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Volumen según parte LTS</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.vol_parte_lts,
                                    }"
                                    v-model="form.vol_parte_lts"
                                />
                                <ul
                                    v-if="form.errors?.vol_parte_lts"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.vol_parte_lts }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Peso parte</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.peso_parte,
                                    }"
                                    v-model="form.peso_parte"
                                />
                                <ul
                                    v-if="form.errors?.peso_parte"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.peso_parte }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Observaciones</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.observaciones2,
                                    }"
                                    v-model="form.observaciones2"
                                />
                                <ul
                                    v-if="form.errors?.observaciones2"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.observaciones2 }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Número de solicitud HR</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.nro_solicitud_hr,
                                    }"
                                    v-model="form.nro_solicitud_hr"
                                />
                                <ul
                                    v-if="form.errors?.nro_solicitud_hr"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.nro_solicitud_hr }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Número de ruta</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.nro_ruta,
                                    }"
                                    v-model="form.nro_ruta"
                                />
                                <ul
                                    v-if="form.errors?.nro_ruta"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.nro_ruta }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Fecha de HR</label>
                                <input
                                    type="date"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.fecha_hr,
                                    }"
                                    v-model="form.fecha_hr"
                                />
                                <ul
                                    v-if="form.errors?.fecha_hr"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.fecha_hr }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Observaciones</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.observaciones3,
                                    }"
                                    v-model="form.observaciones3"
                                />
                                <ul
                                    v-if="form.errors?.observaciones3"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.observaciones3 }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Número factura ALBO/DAB</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.nro_fac_albodab,
                                    }"
                                    v-model="form.nro_fac_albodab"
                                />
                                <ul
                                    v-if="form.errors?.nro_fac_albodab"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.nro_fac_albodab }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Fecha de factura</label>
                                <input
                                    type="date"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.fecha_factura,
                                    }"
                                    v-model="form.fecha_factura"
                                />
                                <ul
                                    v-if="form.errors?.fecha_factura"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.fecha_factura }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Importe en BS*</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.importe_bs,
                                    }"
                                    v-model="form.importe_bs"
                                />
                                <ul
                                    v-if="form.errors?.importe_bs"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.importe_bs }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Observaciones</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.observaciones4,
                                    }"
                                    v-model="form.observaciones4"
                                />
                                <ul
                                    v-if="form.errors?.observaciones4"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.observaciones4 }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Observaciones General</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.observaciones_general,
                                    }"
                                    v-model="form.observaciones_general"
                                />
                                <ul
                                    v-if="form.errors?.observaciones_general"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.observaciones_general }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <a
                        href="javascript:;"
                        class="btn btn-white"
                        @click="cerrarDialog()"
                        ><i class="fa fa-times"></i> Cerrar</a
                    >
                    <button
                        type="button"
                        @click="enviarFormulario()"
                        class="btn btn-primary"
                    >
                        <i class="fa fa-save"></i>
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
