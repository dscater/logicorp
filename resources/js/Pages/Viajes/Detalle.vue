<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { useViajes } from "@/composables/viajes/useViajes";
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
const accion = ref(props.accion_dialog);
const dialog = ref(props.open_dialog);
let form = useForm(oViaje.value);
watch(
    () => props.open_dialog,
    async (newValue) => {
        dialog.value = newValue;
        if (dialog.value) {
            form = useForm(oViaje.value);
            document
                .getElementsByTagName("body")[0]
                .classList.add("modal-open");
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
        : `<i class="fa fa-edit"></i> Detalle del Registro`;
});

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
                    <div class="row">
                        <div class="col-md-4">
                            <label>Programación</label>
                            {{ form?.programacion?.full_name }}
                        </div>
                        <div class="col-md-4">
                            <label>Volumen programado</label>
                            {{ form?.volumen_programado }}
                        </div>
                        <div class="col-md-4">
                            <label>Tramo</label>
                            {{ form?.tramo }}
                        </div>
                        <div class="col-md-4">
                            <label>Nómina</label>
                            {{ form?.nomina }}
                        </div>
                        <div class="col-md-4">
                            <label>Resolución</label>
                            {{ form?.resolucion }}
                        </div>
                        <div class="col-md-4">
                            <label>DIM</label>
                            {{ form?.dim }}
                        </div>
                        <div class="col-md-4">
                            <label>Estado</label>
                            {{ form?.estado }}
                        </div>
                        <div class="col-md-4">
                            <label>Observaciones</label>
                            {{ form?.observaciones }}
                        </div>
                        <div class="col-md-4">
                            <label>Fecha carga</label>
                            {{ form?.fecha_carga }}
                        </div>
                        <div class="col-md-4">
                            <label>Volumen cargado</label>
                            {{ form?.volumen_cargado }}
                        </div>
                        <div class="col-md-4">
                            <label>Total</label>
                            {{ form?.total }}
                        </div>
                        <div class="col-md-4">
                            <label>CRE de carga N°</label>
                            {{ form?.cre_carga }}
                        </div>
                        <div class="col-md-4">
                            <label>Volumen recepcionado</label>
                            {{ form?.volumen_recepcionado }}
                        </div>
                        <div class="col-md-4">
                            <label>Total 3</label>
                            {{ form?.total2 }}
                        </div>
                        <div class="col-md-4">
                            <label>Mermas</label>
                            {{ form?.mermas }}
                        </div>
                        <div class="col-md-4">
                            <label>Diferencia en litros</label>
                            {{ form?.dif_litros }}
                        </div>
                        <div class="col-md-4">
                            <label>Merma 0.15% Y.P.F.B.</label>
                            {{ form?.merma_ypfb }}
                        </div>
                        <div class="col-md-4">
                            <label>Mermas por cobrar</label>
                            {{ form?.merma_cobrar }}
                        </div>
                        <div class="col-md-4">
                            <label>Volumen a facturar</label>
                            {{ form?.volumen_facturar }}
                        </div>
                        <div class="col-md-4">
                            <label>Fecha de descarga</label>
                            {{ form?.fecha_descarga }}
                        </div>
                        <div class="col-md-4">
                            <label>Según CRE N°</label>
                            {{ form?.segun_cre }}
                        </div>
                        <div class="col-md-4">
                            <label>Factura/Lote</label>
                            {{ form?.factura_lote }}
                        </div>
                        <div class="col-md-4">
                            <label>Arica-Tambo Quemado-La Paz</label>
                            {{ form?.atq_lapaz }}
                        </div>
                        <div class="col-md-4">
                            <label>Mes servicio</label>
                            {{ form?.mes_servicio }}
                        </div>
                        <div class="col-md-4">
                            <label>DIM</label>
                            {{ form?.dim2 }}
                        </div>
                        <div class="col-md-4">
                            <label>CRT</label>
                            {{ form?.crt }}
                        </div>
                        <div class="col-md-4">
                            <label>VOL CRT M3</label>
                            {{ form?.vol_crtm3 }}
                        </div>
                        <div class="col-md-4">
                            <label>Peso CRT</label>
                            {{ form?.peso_crt }}
                        </div>
                        <div class="col-md-4">
                            <label>Planta de carga según CRT</label>
                            {{ form?.planta_carga_crt }}
                        </div>
                        <div class="col-md-4">
                            <label>Fecha cruce frontera</label>
                            {{ form?.fecha_cruce_frontera }}
                        </div>
                        <div class="col-md-4">
                            <label>MIC/DTA</label>
                            {{ form?.mic_dta }}
                        </div>
                        <div class="col-md-4">
                            <label>Volumen según MIC</label>
                            {{ form?.vol_mic }}
                        </div>
                        <div class="col-md-4">
                            <label>Peso según MIC</label>
                            {{ form?.peso_mic }}
                        </div>
                        <div class="col-md-4">
                            <label>Parte de recepción</label>
                            {{ form?.parte_recepcion }}
                        </div>
                        <div class="col-md-4">
                            <label>Volumen según parte M3</label>
                            {{ form?.vol_parte_mic }}
                        </div>
                        <div class="col-md-4">
                            <label>Volumen según parte LTS</label>
                            {{ form?.vol_parte_lts }}
                        </div>
                        <div class="col-md-4">
                            <label>Peso parte</label>
                            {{ form?.peso_parte }}
                        </div>
                        <div class="col-md-4">
                            <label>Observaciones</label>
                            {{ form?.observaciones2 }}
                        </div>
                        <div class="col-md-4">
                            <label>Número de solicitud HR</label>
                            {{ form?.nro_solicitud_hr }}
                        </div>
                        <div class="col-md-4">
                            <label>Número de ruta</label>
                            {{ form?.nro_ruta }}
                        </div>
                        <div class="col-md-4">
                            <label>Fecha de HR</label>
                            {{ form?.fecha_hr }}
                        </div>
                        <div class="col-md-4">
                            <label>Observaciones</label>
                            {{ form?.observaciones3 }}
                        </div>
                        <div class="col-md-4">
                            <label>Número factura ALBO/DAB</label>
                            {{ form?.nro_fac_albodab }}
                        </div>
                        <div class="col-md-4">
                            <label>Fecha de factura</label>
                            {{ form?.fecha_factura }}
                        </div>
                        <div class="col-md-4">
                            <label>Importe en BS.</label>
                            {{ form?.importe_bs }}
                        </div>
                        <div class="col-md-4">
                            <label>Observaciones</label>
                            {{ form?.observaciones4 }}
                        </div>
                        <div class="col-md-4">
                            <label>Observaciones General</label>
                            {{ form?.observaciones_general }}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a
                        href="javascript:;"
                        class="btn btn-white"
                        @click="cerrarDialog()"
                        ><i class="fa fa-times"></i> Cerrar</a
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
label {
    margin-top: 10px;
    display: block;
    font-weight: bold;
}
</style>
