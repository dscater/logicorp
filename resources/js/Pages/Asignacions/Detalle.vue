<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { useAsignacions } from "@/composables/asignacions/useAsignacions";
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

const { oAsignacion, limpiarAsignacion } = useAsignacions();
const { getEmpresas } = useEmpresas();
const { getProveedors } = useProveedors();
const { getProductos } = useProductos();
const accion = ref(props.accion_dialog);
const dialog = ref(props.open_dialog);
const listEmpresas = ref([]);
const listProveedors = ref([]);
const listProductos = ref([]);
let form = useForm(oAsignacion);
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
            form = useForm(oAsignacion);
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
        : `<i class="fa fa-edit"></i>Detalle del Registro`;
});

const enviarFormulario = () => {
    let url =
        form["_method"] == "POST"
            ? route("asignacions.store")
            : route("asignacions.update", form.id);

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
            limpiarAsignacion();
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
    form.asignacion_detalles.push({
        id: 0,
        asignacion_id: "",
        proveedor_id: "",
        producto_id: "",
        tramo: "",
        frontera: "",
        cantidad: "",
    });
};

const eliminarDetale = (index) => {
    let id = form.asignacion_detalles[index].id;
    if (id != 0) {
        form.eliminados.push(id);
    }
    form.asignacion_detalles.splice(index, 1);
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
                            <div class="col-12">
                                <p>
                                    <strong>Código de contrato: </strong
                                    >{{ form.contrato?.codigo }}
                                </p>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <h5>Detalles</h5>
                            </div>

                            <div
                                class="col-12"
                                style="overflow: auto"
                                v-for="(
                                    item_detalle, index_detalle
                                ) in form.asignacion_detalles"
                            >
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="bg-primary">
                                            <th class="text-white">
                                                Proveedor
                                            </th>
                                            <th class="text-white" colspan="4">
                                                {{
                                                    item_detalle
                                                        .contrato_detalle
                                                        .proveedor?.razon_social
                                                }}
                                            </th>
                                        </tr>
                                        <tr class="bg-primary">
                                            <th class="text-white">Cantidad</th>
                                            <th class="text-white" colspan="4">
                                                {{
                                                    item_detalle
                                                        .contrato_detalle
                                                        .cantidad
                                                }}
                                            </th>
                                        </tr>
                                        <tr class="bg-primary">
                                            <th class="text-white">Producto</th>
                                            <th class="text-white" colspan="4">
                                                {{
                                                    item_detalle
                                                        .contrato_detalle
                                                        .producto.nombre
                                                }}
                                            </th>
                                        </tr>
                                        <tr class="bg-primary">
                                            <th class="text-white">
                                                Tramo / Frontera
                                            </th>
                                            <th class="text-white" colspan="4">
                                                {{
                                                    item_detalle
                                                        .contrato_detalle.tramo
                                                }}
                                                /
                                                {{
                                                    item_detalle
                                                        .contrato_detalle
                                                        .frontera
                                                }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Empresa/Sociedad</th>
                                            <th>% de adjudicación</th>
                                            <th>Cantidad decimales</th>
                                            <th>Cantidad Entero m3</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(
                                                item, index_empresa
                                            ) in item_detalle.asignacion_empresas"
                                        >
                                            <td>
                                                {{ item.empresa?.razon_social }}
                                            </td>
                                            <td>
                                                {{ item.p_adjudicacion }}
                                            </td>
                                            <td>
                                                {{ item.cantidad }}
                                            </td>
                                            <td>
                                                {{ item.cantidad_entero }}
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
                </div>
            </div>
        </div>
    </div>
</template>
