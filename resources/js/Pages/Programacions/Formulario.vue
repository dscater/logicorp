<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { useProgramacions } from "@/composables/programacions/useProgramacions";
import { useContratos } from "@/composables/contratos/useContratos";
import { useEmpresas } from "@/composables/empresas/useEmpresas";
import { useProductos } from "@/composables/productos/useProductos";
import { useProveedors } from "@/composables/proveedors/useProveedors";
import { useVehiculos } from "@/composables/vehiculos/useVehiculos";
import { useConductors } from "@/composables/conductors/useConductors";
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

const { oProgramacion, limpiarProgramacion } = useProgramacions();
const { getContratos } = useContratos();
const { getEmpresas } = useEmpresas();
const { getProductos } = useProductos();
const { getProveedors } = useProveedors();
const { getVehiculos } = useVehiculos();
const { getConductors } = useConductors();
const accion = ref(props.accion_dialog);
const dialog = ref(props.open_dialog);
const listContratos = ref([]);
const listEmpresas = ref([]);
const listAsociacions = ref([]);
const listProductos = ref([]);
const listProveedors = ref([]);
const listVehiculos = ref([]);
const listConductors = ref([]);
let form = useForm(oProgramacion.value);
watch(
    () => props.open_dialog,
    async (newValue) => {
        dialog.value = newValue;
        if (dialog.value) {
            cargarListas();
            document
                .getElementsByTagName("body")[0]
                .classList.add("modal-open");
            form = useForm(oProgramacion.value);
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
            ? route("programacions.store")
            : route("programacions.update", form.id);

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
            limpiarProgramacion();
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

const cargarContratos = async () => {
    listContratos.value = await getContratos();
};
const cargarEmpresas = async () => {
    listEmpresas.value = await getEmpresas({ tipo: "EMPRESA" });
};
const cargarAsociacions = async () => {
    listAsociacions.value = await getEmpresas({ tipo: "ASOCIACIÓN" });
};
const cargarProductos = async () => {
    listProductos.value = await getProductos();
};
const cargarProveedors = async () => {
    listProveedors.value = await getProveedors();
};
const cargarVehiculos = async () => {
    listVehiculos.value = await getVehiculos();
};
const cargarConductors = async () => {
    listConductors.value = await getConductors();
};

const oContrato = ref(null);
const getInfoContrato = () => {
    if (form.contrato_id) {
        axios
            .get(route("contratos.show", form.contrato_id))
            .then((response) => {
                oContrato.value = response.data;
                form.asociacion_id = oContrato.value.empresa_id;
            });
    } else {
        oContrato.value = null;
    }
};

const cargarListas = () => {
    cargarContratos();
    cargarEmpresas();
    cargarAsociacions();
    cargarProductos();
    cargarProveedors();
    cargarVehiculos();
    cargarConductors();
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
                                <label>Seleccionar contrato*</label>
                                <select
                                    class="form-select"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.contrato_id,
                                    }"
                                    v-model="form.contrato_id"
                                    @change="getInfoContrato"
                                >
                                    <option value="">- Seleccione -</option>
                                    <option
                                        v-for="item in listContratos"
                                        :value="item.id"
                                    >
                                        {{ item.codigo }}
                                    </option>
                                </select>
                                <ul
                                    v-if="form.errors?.contrato_id"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.contrato_id }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Seleccionar empresa*</label>
                                <select
                                    class="form-select"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.empresa_id,
                                    }"
                                    v-model="form.empresa_id"
                                >
                                    <option value="">- Seleccione -</option>
                                    <option
                                        v-for="item in listEmpresas"
                                        :value="item.id"
                                    >
                                        {{ item.razon_social }}
                                    </option>
                                </select>
                                <ul
                                    v-if="form.errors?.empresa_id"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.empresa_id }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Asociación*</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :value="oContrato?.empresa.razon_social"
                                    readonly
                                />
                                <!-- <select
                                    class="form-select"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.asociacion_id,
                                    }"
                                    v-model="form.asociacion_id"
                                >
                                    <option value="">- Seleccione -</option>
                                    <option
                                        v-for="item in listAsociacions"
                                        :value="item.id"
                                    >
                                        {{ item.razon_social }}
                                    </option>
                                </select>
                                <ul
                                    v-if="form.errors?.asociacion_id"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.asociacion_id }}
                                    </li>
                                </ul> -->
                            </div>
                            <div class="col-md-4">
                                <label>Seleccionar producto*</label>
                                <select
                                    class="form-select"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.producto_id,
                                    }"
                                    v-model="form.producto_id"
                                >
                                    <option value="">- Seleccione -</option>
                                    <option
                                        v-for="item in listProductos"
                                        :value="item.id"
                                    >
                                        {{ item.nombre }}
                                    </option>
                                </select>
                                <ul
                                    v-if="form.errors?.producto_id"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.producto_id }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Seleccionar proveedor*</label>
                                <select
                                    class="form-select"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.proveedor_id,
                                    }"
                                    v-model="form.proveedor_id"
                                >
                                    <option value="">- Seleccione -</option>
                                    <option
                                        v-for="item in listProveedors"
                                        :value="item.id"
                                    >
                                        {{ item.razon_social }}
                                    </option>
                                </select>
                                <ul
                                    v-if="form.errors?.proveedor_id"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.proveedor_id }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Seleccionar vehículo*</label>
                                <select
                                    class="form-select"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.vehiculo_id,
                                    }"
                                    v-model="form.vehiculo_id"
                                >
                                    <option value="">- Seleccione -</option>
                                    <option
                                        v-for="item in listVehiculos"
                                        :value="item.id"
                                    >
                                        {{ item.full_name }}
                                    </option>
                                </select>
                                <ul
                                    v-if="form.errors?.vehiculo_id"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.vehiculo_id }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Seleccionar conductor*</label>
                                <select
                                    class="form-select"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.conductor_id,
                                    }"
                                    v-model="form.conductor_id"
                                >
                                    <option value="">- Seleccione -</option>
                                    <option
                                        v-for="item in listConductors"
                                        :value="item.id"
                                    >
                                        {{ item.full_name }}
                                    </option>
                                </select>
                                <ul
                                    v-if="form.errors?.conductor_id"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.conductor_id }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Origen/Destino*</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.origen_destino,
                                    }"
                                    v-model="form.origen_destino"
                                />
                                <ul
                                    v-if="form.errors?.origen_destino"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.origen_destino }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Frontera*</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.frontera,
                                    }"
                                    v-model="form.frontera"
                                />
                                <ul
                                    v-if="form.errors?.frontera"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.frontera }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Fecha de programación*</label>
                                <input
                                    type="date"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.fecha_programacion,
                                    }"
                                    v-model="form.fecha_programacion"
                                />
                                <ul
                                    v-if="form.errors?.fecha_programacion"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.fecha_programacion }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Descripción</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.descripcion,
                                    }"
                                    v-model="form.descripcion"
                                />
                                <ul
                                    v-if="form.errors?.descripcion"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.descripcion }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <br />
                                <label class="d-flex align-center gap-2"
                                    >Vehículo reemplazado
                                    <input
                                        type="checkbox"
                                        :true-value="1"
                                        :false-value="0"
                                        v-model="form.reemplazo"
                                /></label>
                            </div>
                            <div class="col-md-4" v-if="form.reemplazo == 1">
                                <label
                                    >Seleccionar vehículo de reemplazo*</label
                                >
                                <select
                                    class="form-select"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.vehiculo_remplazo_id,
                                    }"
                                    v-model="form.vehiculo_remplazo_id"
                                >
                                    <option value="">- Seleccione -</option>
                                    <option
                                        v-for="item in listVehiculos"
                                        :value="item.id"
                                    >
                                        {{ item.full_name }}
                                    </option>
                                </select>
                                <ul
                                    v-if="form.errors?.vehiculo_remplazo_id"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.vehiculo_remplazo_id }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4" v-if="form.reemplazo == 1">
                                <label>Observaciones:</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.observacion_reemplazo,
                                    }"
                                    v-model="form.observacion_reemplazo"
                                />
                                <ul
                                    v-if="form.errors?.observacion_reemplazo"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.observacion_reemplazo }}
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
