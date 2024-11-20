<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { useContratos } from "@/composables/contratos/useContratos";
import { useEmpresas } from "@/composables/empresas/useEmpresas";
import { useProveedors } from "@/composables/proveedors/useProveedors";
import { useProductos } from "@/composables/productos/useProductos";
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

const { oContrato, limpiarContrato } = useContratos();
const { getEmpresas } = useEmpresas();
const { getProveedors } = useProveedors();
const { getProductos } = useProductos();
const accion = ref(props.accion_dialog);
const dialog = ref(props.open_dialog);
const listEmpresas = ref([]);
const listProveedors = ref([]);
const listProductos = ref([]);
let form = useForm(oContrato);
watch(
    () => props.open_dialog,
    async (newValue) => {
        dialog.value = newValue;
        if (dialog.value) {
            cargarEmpresas();
            cargarProveedors();
            cargarProductos();
            document
                .getElementsByTagName("body")[0]
                .classList.add("modal-open");
            form = useForm(oContrato);
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
        : `<i class="fa fa-list"></i>Editar Registro`;
});

const enviarFormulario = () => {
    let url =
        form["_method"] == "POST"
            ? route("contratos.store")
            : route("contratos.update", form.id);

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
            limpiarContrato();
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

const cargarEmpresas = async () => {
    listEmpresas.value = await getEmpresas({ tipo: "ASOCIACIÓN" });
};

const cargarProveedors = async () => {
    listProveedors.value = await getProveedors();
};

const cargarProductos = async () => {
    listProductos.value = await getProductos();
};

const agregarDetalle = () => {
    form.contrato_detalles.push({
        id: 0,
        contrato_id: "",
        proveedor_id: "",
        producto_id: "",
        tramo: "",
        frontera: "",
        cantidad: "",
    });
};

const eliminarDetale = (index) => {
    let id = form.contrato_detalles[index].id;
    if (id != 0) {
        form.eliminados.push(id);
    }
    form.contrato_detalles.splice(index, 1);
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
                                <label>Nro. lote*</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.nro_lote,
                                    }"
                                    v-model="form.nro_lote"
                                />
                                <ul
                                    v-if="form.errors?.nro_lote"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.nro_lote }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Seleccionar asosiación*</label>
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
                                <label>Porcentaje asignado*</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.p_asignado,
                                    }"
                                    v-model="form.p_asignado"
                                    step="0.01"
                                />
                                <ul
                                    v-if="form.errors?.p_asignado"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.p_asignado }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <h5>Detalles</h5>
                            </div>

                            <div class="col-12" style="overflow: auto">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Proveedor</th>
                                            <th>Tramo</th>
                                            <th>Frontera</th>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(
                                                item, index
                                            ) in form.contrato_detalles"
                                        >
                                            <td class="p-0">
                                                <select
                                                    class="form-select"
                                                    v-model="item.proveedor_id"
                                                >
                                                    <option value="">
                                                        - Seleccione -
                                                    </option>
                                                    <option
                                                        v-for="item in listProveedors"
                                                        :value="item.id"
                                                    >
                                                        {{ item.razon_social }}
                                                    </option>
                                                </select>
                                            </td>
                                            <td class="p-0">
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    v-model="item.tramo"
                                                />
                                            </td>
                                            <td class="p-0">
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    v-model="item.frontera"
                                                />
                                            </td>
                                            <td class="p-0">
                                                <select
                                                    class="form-select"
                                                    v-model="item.producto_id"
                                                >
                                                    <option value="">
                                                        - Seleccione -
                                                    </option>
                                                    <option
                                                        v-for="item in listProductos"
                                                        :value="item.id"
                                                    >
                                                        {{ item.nombre }}
                                                    </option>
                                                </select>
                                            </td>
                                            <td class="p-0">
                                                <input
                                                    type="number"
                                                    class="form-control"
                                                    v-model="item.cantidad"
                                                />
                                            </td>
                                            <td class="p-0">
                                                <button
                                                    @click="
                                                        eliminarDetale(index)
                                                    "
                                                    type="button"
                                                    class="btn btn-danger btn-sm"
                                                >
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr
                                            v-if="
                                                form.errors?.contrato_detalles
                                            "
                                        >
                                            <td
                                                colspan="6"
                                                class="text-danger text-center"
                                            >
                                                {{
                                                    form.errors
                                                        ?.contrato_detalles
                                                }}
                                            </td>
                                        </tr>
                                        <tr
                                            v-if="
                                                form.errors?.contrato_detalles_can
                                            "
                                        >
                                            <td
                                                colspan="6"
                                                class="text-danger text-center"
                                            >
                                                {{
                                                    form.errors
                                                        ?.contrato_detalles_can
                                                }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="6">
                                                <button
                                                    @click.prevent="
                                                        agregarDetalle
                                                    "
                                                    type="button"
                                                    class="btn btn-default w-100"
                                                >
                                                    <i class="fa fa-plus"></i>
                                                    Agregar
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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
