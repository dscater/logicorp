<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { useAsignacions } from "@/composables/asignacions/useAsignacions";
import { useEmpresas } from "@/composables/empresas/useEmpresas";
import { useContratos } from "@/composables/contratos/useContratos";
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
const { getContratos } = useContratos();
const accion = ref(props.accion_dialog);
const dialog = ref(props.open_dialog);
const oContrato = ref(null);
const listEmpresas = ref([]);
const listContratos = ref([]);
let form = useForm(oAsignacion);
watch(
    () => props.open_dialog,
    async (newValue) => {
        dialog.value = newValue;
        if (dialog.value) {
            cargarEmpresas();
            cargarContratos();
            document
                .getElementsByTagName("body")[0]
                .classList.add("modal-open");
            form = useForm(oAsignacion);
        }
    }
);
watch(
    () => props.accion_dialog,
    async (newValue) => {
        accion.value = newValue;
        if (accion.value == 1) {
            if (form.contrato_id != "") {
                oContrato.value = await obtieneContrato();
            }
        }
    }
);

const { flash } = usePage().props;

const tituloDialog = computed(() => {
    return accion.value == 0
        ? `<i class="fa fa-plus"></i> Agregar Registro`
        : `<i class="fa fa-list"></i> Editar Registro`;
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
    listEmpresas.value = await getEmpresas();
};

const cargarContratos = async () => {
    listContratos.value = await getContratos();
};

const obtieneContrato = async () => {
    try {
        const response = await axios.get(
            route("contratos.show", form.contrato_id)
        );
        if (response && response.data) {
            return response.data;
        }
        return null;
    } catch (error) {
        console.log("no se pudo obtener el contrato");
    }
};

const cambioDeContrato = async () => {
    if (form.contrato_id != "") {
        oContrato.value = await obtieneContrato();
        if (oContrato.value) {
            // generar asignacion_detalles
            generarAsignacionDetalles(oContrato.value.contrato_detalles);
            return true;
        }
    }
    reiniciarDatosDetalles();
    return false;
};

const generarAsignacionDetalles = (contrato_detalles) => {
    contrato_detalles.forEach((element) => {
        form.asignacion_detalles.push({
            id: 0,
            asignacion_id: 0,
            contrato_detalle_id: element.id,
            asignacion_empresas: [],
            contrato_detalle: element,
        });
    });
};

const reiniciarDatosDetalles = () => {
    form.asignacion_detalles = [];
};

const agregarEmpresa = (index_detalle) => {
    form.asignacion_detalles[index_detalle].asignacion_empresas.push({
        id: 0,
        asignacion_detalle_id: "",
        empresa_id: "",
        p_adjudicacion: "",
        cantidad: "",
        cantidad_entero: "",
    });
};

const eliminarDetale = (index_detalle, index_empresa) => {
    let id =
        form.asignacion_detalles[index_detalle].asignacion_empresas[
            index_empresa
        ].id;
    if (id != 0) {
        form.eliminados.push(id);
    }
    form.asignacion_detalles[index_detalle].asignacion_empresas.splice(
        index_empresa,
        1
    );
};

const realizaCalculoCantidades = (e, index_detalle, index_empresa) => {
    const value = e.target.value;
    if (value > 0 && value < 100) {
        const cantidad =
            form.asignacion_detalles[index_detalle].contrato_detalle.cantidad;
        let porcentaje = value / 100;
        let cantidad_decimales = porcentaje * cantidad;
        cantidad_decimales = parseFloat(cantidad_decimales).toFixed(2);
        const cantidad_entero = parseInt(cantidad_decimales);
        // console.log(`Cantidad: ${cantidad}`);
        // console.log(`Decimales: ${cantidad_decimales}`);
        // console.log(`Entero: ${cantidad_entero}`);
        // ASIGNAR VALORES
        form.asignacion_detalles[index_detalle].asignacion_empresas[
            index_empresa
        ].cantidad = cantidad_decimales;

        form.asignacion_detalles[index_detalle].asignacion_empresas[
            index_empresa
        ].cantidad_entero = cantidad_entero;
    } else {
        form.asignacion_detalles[index_detalle].asignacion_empresas[
            index_empresa
        ].cantidad = 0;

        form.asignacion_detalles[index_detalle].asignacion_empresas[
            index_empresa
        ].cantidad_entero = 0;

        Swal.fire({
            icon: "info",
            title: "Error",
            text: `Debes ingresar un valor entre 0 y 100`,
            confirmButtonColor: "#3085d6",
            confirmButtonText: `Aceptar`,
        });
    }
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
                            <div class="col-md-12" v-if="accion == 0">
                                <label>Seleccionar código de contrato*</label>
                                <select
                                    class="form-select"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.contrato_id,
                                    }"
                                    v-model="form.contrato_id"
                                    @change="cambioDeContrato"
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
                            <div class="col-md-12" v-else>
                                <label>Código de contrato*</label>
                                <input
                                    class="form-control"
                                    :value="oContrato?.codigo"
                                    readonly
                                />
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
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(
                                                item, index_empresa
                                            ) in item_detalle.asignacion_empresas"
                                        >
                                            <td class="p-0">
                                                <select
                                                    class="form-select"
                                                    v-model="item.empresa_id"
                                                >
                                                    <option value="">
                                                        - Seleccione -
                                                    </option>
                                                    <option
                                                        v-for="item in listEmpresas"
                                                        :value="item.id"
                                                    >
                                                        {{ item.razon_social }}
                                                    </option>
                                                </select>
                                            </td>
                                            <td class="p-0">
                                                <input
                                                    type="number"
                                                    step="0.01"
                                                    class="form-control"
                                                    v-model="
                                                        item.p_adjudicacion
                                                    "
                                                    @change="
                                                        realizaCalculoCantidades(
                                                            $event,
                                                            index_detalle,
                                                            index_empresa
                                                        )
                                                    "
                                                    @keyup.prevent="
                                                        realizaCalculoCantidades(
                                                            $event,
                                                            index_detalle,
                                                            index_empresa
                                                        )
                                                    "
                                                />
                                            </td>
                                            <td class="p-0">
                                                <input
                                                    type="number"
                                                    step="0.01"
                                                    class="form-control"
                                                    v-model="item.cantidad"
                                                />
                                            </td>
                                            <td class="p-0">
                                                <input
                                                    type="number"
                                                    step="1"
                                                    class="form-control"
                                                    v-model="
                                                        item.cantidad_entero
                                                    "
                                                />
                                            </td>
                                            <td class="p-0">
                                                <button
                                                    @click="
                                                        eliminarDetale(
                                                            index_detalle,
                                                            index_empresa
                                                        )
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
                                                form.errors[
                                                    'asignacion_detalles' +
                                                        index_detalle
                                                ]
                                            "
                                        >
                                            <td
                                                colspan="5"
                                                class="text-danger text-center"
                                            >
                                                {{
                                                    form.errors[
                                                        "asignacion_detalles" +
                                                            index_detalle
                                                    ]
                                                }}
                                            </td>
                                        </tr>
                                        <tr
                                            v-if="
                                                form.errors[
                                                    'asignacion_detalles_can' +
                                                        index_detalle
                                                ]
                                            "
                                        >
                                            <td
                                                colspan="5"
                                                class="text-danger text-center"
                                            >
                                                {{
                                                    form.errors[
                                                        "asignacion_detalles_can" +
                                                            index_detalle
                                                    ]
                                                }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5">
                                                <button
                                                    @click.prevent="
                                                        agregarEmpresa(
                                                            index_detalle
                                                        )
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
