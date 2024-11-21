<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { usePagos } from "@/composables/pagos/usePagos";
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

const { oPago, limpiarPago } = usePagos();
const { getViajes } = useViajes();

const accion = ref(props.accion_dialog);
const dialog = ref(props.open_dialog);
const listViajes = ref([]);
const oViaje = ref(null);
let form = useForm(oPago.value);
watch(
    () => props.open_dialog,
    async (newValue) => {
        dialog.value = newValue;
        if (dialog.value) {
            document
                .getElementsByTagName("body")[0]
                .classList.add("modal-open");
            form = useForm(oPago.value);
            cargarViajes();
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
        ? `<i class="fa fa-plus"></i>Agregar Registro`
        : `<i class="fa fa-edit"></i>Editar Registro`;
});

const enviarFormulario = () => {
    let url =
        form["_method"] == "POST"
            ? route("pagos.store", form.programacion_id)
            : route("pagos.update", form.id);

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
            limpiarPago();
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
const cargarViajes = async () => {
    let data = {
        programacion_id: form.programacion_id,
        sin_pago: true,
    };

    if (form.id != 0) {
        data = {
            id: form.viaje_id,
            programacion_id: form.programacion_id,
            sin_pago: true,
        };
    }
    console.log(data);
    listViajes.value = await getViajes(data);
};

const getInfoViaje = (e) => {
    const id = e.target.value;
    if (id != "") {
        axios.get(route("viajes.show", id)).then((response) => {
            oViaje.value = response.data;
            calculaValores();
        });
    } else {
        oViaje.value = null;
        calculaValores();
    }
};

const calculaValores = () => {
    if (oViaje.value) {
        const total_pagado = oViaje.value.importe_bs;
        let retencion = total_pagado * 0.07;
        retencion = parseFloat(retencion).toFixed(2);
        form.retencion = retencion;
        form.total_pagado = total_pagado;
    } else {
        reiniciaValores();
    }
};

const reiniciaValores = () => {
    form.retencion = "";
    form.total_pagado = "";
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
                                <label>Seleccionar viaje*</label>
                                <select
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.viaje_id,
                                    }"
                                    v-model="form.viaje_id"
                                    @change="getInfoViaje($event)"
                                >
                                    <option value="">- Seleccione -</option>
                                    <option
                                        v-for="item in listViajes"
                                        :value="item.id"
                                    >
                                        {{ item.volumen_programado }} |
                                        {{ item.tramo }}
                                    </option>
                                </select>
                                <ul
                                    v-if="form.errors?.viaje_id"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.viaje_id }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Mes y Año*</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.mes_anio,
                                    }"
                                    v-model="form.mes_anio"
                                />
                                <ul
                                    v-if="form.errors?.mes_anio"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.mes_anio }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>CTO*</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.cto,
                                    }"
                                    v-model="form.cto"
                                />
                                <ul
                                    v-if="form.errors?.cto"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.cto }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Fecha*</label>
                                <input
                                    type="date"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.fecha,
                                    }"
                                    v-model="form.fecha"
                                />
                                <ul
                                    v-if="form.errors?.fecha"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.fecha }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Retención 7%*</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.retencion,
                                    }"
                                    v-model="form.retencion"
                                    readonly
                                />
                                <ul
                                    v-if="form.errors?.retencion"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.retencion }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Descuento merma</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.desc_merma,
                                    }"
                                    v-model="form.desc_merma"
                                />
                                <ul
                                    v-if="form.errors?.desc_merma"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.desc_merma }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Total pagado*</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.total_pagado,
                                    }"
                                    v-model="form.total_pagado"
                                    readonly
                                />
                                <ul
                                    v-if="form.errors?.total_pagado"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.total_pagado }}
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
