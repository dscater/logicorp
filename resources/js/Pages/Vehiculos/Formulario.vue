<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
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

const { oVehiculo, limpiarVehiculo } = useVehiculos();
const { getConductors } = useConductors();
const accion = ref(props.accion_dialog);
const dialog = ref(props.open_dialog);

const listConductors = ref([]);
const foto = ref(null);
function cargaArchivo(e, key) {
    form[key] = null;
    form[key] = e.target.files[0];
}

let form = useForm(oVehiculo.value);
watch(
    () => props.open_dialog,
    async (newValue) => {
        dialog.value = newValue;
        if (dialog.value) {
            cargarConductors();
            foto.value = null;
            document
                .getElementsByTagName("body")[0]
                .classList.add("modal-open");
            form = useForm(oVehiculo.value);
        }
    }
);
watch(
    () => props.accion_dialog,
    (newValue) => {
        accion.value = newValue;
    }
);

const cargarConductors = async () => {
    listConductors.value = await getConductors();
};

const { flash } = usePage().props;

const tituloDialog = computed(() => {
    return accion.value == 0
        ? `<i class="fa fa-plus"></i>Agregar Registro`
        : `<i class="fa fa-edit"></i>Editar Registro`;
});

const enviarFormulario = () => {
    let url =
        form["_method"] == "POST"
            ? route("vehiculos.store")
            : route("vehiculos.update", form.id);

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
            limpiarVehiculo();
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
                                <label>Marca*</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.marca,
                                    }"
                                    v-model="form.marca"
                                />
                                <ul
                                    v-if="form.errors?.marca"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.marca }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Modelo*</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.modelo,
                                    }"
                                    v-model="form.modelo"
                                />
                                <ul
                                    v-if="form.errors?.modelo"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.modelo }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Año*</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.anio,
                                    }"
                                    v-model="form.anio"
                                />
                                <ul
                                    v-if="form.errors?.anio"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.anio }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Placa*</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.placa,
                                    }"
                                    v-model="form.placa"
                                />
                                <ul
                                    v-if="form.errors?.placa"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.placa }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Nro. chasis*</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.nro_chasis,
                                    }"
                                    v-model="form.nro_chasis"
                                />
                                <ul
                                    v-if="form.errors?.nro_chasis"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.nro_chasis }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Color*</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.color,
                                    }"
                                    v-model="form.color"
                                />
                                <ul
                                    v-if="form.errors?.color"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.color }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Foto</label>
                                <input
                                    type="file"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.foto,
                                    }"
                                    ref="foto"
                                    @change="cargaArchivo($event, 'foto')"
                                />

                                <ul
                                    v-if="form.errors?.foto"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.foto }}
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
                        </div>
                        <div class="row mt-15px">
                            <div class="col-12">
                                <h4>TANQUE</h4>
                            </div>
                            <div class="col-md-4">
                                <label>Nro. Bin</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.nro_bin,
                                    }"
                                    v-model="form.nro_bin"
                                />
                                <ul
                                    v-if="form.errors?.nro_bin"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.nro_bin }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Nro. chasis tanque</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.nro_cha_tanque,
                                    }"
                                    v-model="form.nro_cha_tanque"
                                />
                                <ul
                                    v-if="form.errors?.nro_cha_tanque"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.nro_cha_tanque }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Marca tanque</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.marca_tanque,
                                    }"
                                    v-model="form.marca_tanque"
                                />
                                <ul
                                    v-if="form.errors?.marca_tanque"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.marca_tanque }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Capacidad</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.capacidad_tanque,
                                    }"
                                    v-model="form.capacidad_tanque"
                                />
                                <ul
                                    v-if="form.errors?.capacidad_tanque"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.capacidad_tanque }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Nro. compartimiento</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.nro_compartamiento,
                                    }"
                                    v-model="form.nro_compartamiento"
                                />
                                <ul
                                    v-if="form.errors?.nro_compartamiento"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.nro_compartamiento }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Volumen (Litros)*</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.volumen_tanque,
                                    }"
                                    v-model="form.volumen_tanque"
                                />
                                <ul
                                    v-if="form.errors?.volumen_tanque"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.volumen_tanque }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Ejes</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.ejes_tanque,
                                    }"
                                    v-model="form.ejes_tanque"
                                />
                                <ul
                                    v-if="form.errors?.ejes_tanque"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.ejes_tanque }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Nro. precientos</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.nro_precientos,
                                    }"
                                    v-model="form.nro_precientos"
                                />
                                <ul
                                    v-if="form.errors?.nro_precientos"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.nro_precientos }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <label>Tipo</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.tipo_tanque,
                                    }"
                                    v-model="form.tipo_tanque"
                                />
                                <ul
                                    v-if="form.errors?.tipo_tanque"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.tipo_tanque }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row mt-15px">
                            <div class="col-12">
                                <h4>CONDUCTOR</h4>
                            </div>
                            <div class="col-md-12">
                                <label>Seleccionar conductor*</label>
                                <select
                                    class="form-control"
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
