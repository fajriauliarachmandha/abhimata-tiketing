<template>
    <NavUser />
    <main class="min-h-screen bg-gray-100 px-4 pt-20 pb-6 lg:ms-64 lg:pt-24">
        <div class="mx-auto flex items-center justify-center">
            <h1
                class="text-base font-bold tracking-wide text-gray-900 uppercase"
            >
                PENGAJUAN TIKET
            </h1>
        </div>

        <form @submit.prevent="submit" class="max-w-md mx-auto my-7 px-4">
            <div class="mb-5">
                <label
                    for="departement"
                    class="block mb-2.5 text-sm font-medium text-heading"
                    >Departement</label
                >
                <input
                    type="text"
                    id="departement"
                    class="block w-full h-10 px-3 text-sm text-heading bg-white border border-default-medium rounded-sm shadow-xs placeholder:text-body focus:ring-brand focus:border-brand"
                    placeholder="Departement"
                    required
                    :value="user.department.department_name"
                    disabled
                />
            </div>

            <div class="mb-5">
                <label class="block mb-2.5 text-sm font-medium text-heading">
                    Kategori Kendala
                </label>
                <div class="flex flex-wrap gap-2">
                    <label
                        v-for="item in categoryOptions"
                        :key="item.value"
                        class="cursor-pointer"
                    >
                        <input
                            type="radio"
                            name="kategori_kendala"
                            :value="item.value"
                            v-model="form.category"
                            class="sr-only peer"
                        />
                        <div
                            class="inline-flex items-center justify-center h-10 px-4 bg-gray-300 text-gray-700 text-sm rounded-full border border-gray-200 transition-all duration-200 ease-in-out active:scale-95 peer-checked:bg-dark peer-checked:text-white peer-checked:border-dark peer-checked:shadow-md peer-checked:scale-[1.02]"
                        >
                            {{ item.label }}
                        </div>
                    </label>
                </div>
                <span
                    v-if="form.errors.category"
                    class="text-xs text-red-500 mt-1 block"
                >
                    {{ form.errors.category }}
                </span>
            </div>

            <div class="mb-5">
                <label class="block mb-2.5 text-sm font-medium text-heading">
                    Tingkat Prioritas
                </label>

                <div class="flex flex-wrap gap-2">
                    <label
                        v-for="item in priorityOptions"
                        :key="item.value"
                        class="cursor-pointer select-none"
                    >
                        <input
                            type="radio"
                            name="tingkat_prioritas"
                            :value="item.value"
                            v-model="form.priority"
                            class="sr-only peer"
                        />

                        <div
                            class="inline-flex items-center justify-center h-10 px-4 bg-gray-300 text-gray-700 text-sm rounded-full border border-gray-200 transition-all duration-200 ease-in-out active:scale-95 peer-checked:shadow-md peer-checked:scale-[1.02]"
                            :class="item.activeClass"
                        >
                            {{ item.label }}
                        </div>
                        <span
                            v-if="form.errors.priority"
                            class="text-xs text-red-500 mt-1 block"
                        >
                            {{ form.errors.priority }}
                        </span>
                    </label>
                </div>
            </div>

            <div class="mb-5">
                <!-- Label -->
                <label class="block mb-2.5 text-sm font-medium text-heading">
                    Foto
                </label>

                <!-- Input File Tersembunyi (Kamera Langsung) -->
                <input
                    ref="fileInput"
                    type="file"
                    accept="image/*"
                    capture="environment"
                    class="hidden"
                    @change="handleFileChange"
                />
                <div
                    @click="openCamera"
                    class="relative w-full h-32 bg-white border-2 border-dashed border-gray-400 rounded-sm flex flex-col items-center justify-center cursor-pointer hover:bg-gray-300 transition-colors overflow-hidden"
                >
                    <!-- Tampilan Setelah Foto Diambil (Preview) -->
                    <img
                        v-if="previewUrl"
                        :src="previewUrl"
                        class="w-full h-full object-contain"
                        alt="Preview Foto"
                    />

                    <!-- Tampilan Awal (Kosong) -->
                    <div
                        v-else
                        class="flex flex-col items-center text-gray-500"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="#ffffff"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="lucide lucide-camera preview-icon"
                        >
                            <path
                                d="M13.997 4a2 2 0 0 1 1.76 1.05l.486.9A2 2 0 0 0 18.003 7H20a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h1.997a2 2 0 0 0 1.759-1.048l.489-.904A2 2 0 0 1 10.004 4z"
                            />
                            <circle cx="12" cy="13" r="3" />
                        </svg>
                        <span class="text-xs font-medium">Ambil Foto</span>
                    </div>
                </div>
                <span
                    v-if="form.errors.photo"
                    class="text-xs text-red-500 mt-1 block"
                >
                    {{ form.errors.photo }}
                </span>
            </div>

            <div class="mb-5">
                <label
                    for="deskripsi"
                    class="block mb-2.5 text-sm font-medium text-heading"
                    >Deskripsi</label
                >
                <textarea
                    id="deskripsi"
                    rows="4"
                    name="deskripsi"
                    v-model="form.description"
                    class="bg-white border border-default-medium text-heading text-sm rounded-sm focus:ring-brand focus:border-brand block w-full p-4 shadow-xs placeholder:text-body"
                    placeholder="Jelaskan kendala kamu..."
                ></textarea>
                <span
                    v-if="form.errors.description"
                    class="text-xs text-red-500 mt-1 block"
                >
                    {{ form.errors.description }}
                </span>
            </div>

            <button
                type="submit"
                class="inline-flex items-center justify-center gap-1.5 h-10 px-4 text-sm font-semibold text-white bg-primary-500 hover:bg-primary-600 border border-transparent rounded-sm shadow-xs focus:outline-none focus:ring-4 focus:ring-brand-medium active:scale-95 transition duration-200 w-full"
            >
                Ajukan Tiket
            </button>
        </form>
    </main>
</template>

<script setup>
import { ref, onBeforeUnmount, computed } from "vue";
import NavUser from "../../Layouts/NavUser.vue";
import { useForm, usePage } from "@inertiajs/vue3";

const categoryOptions = [
    { label: "Hardware", value: "Hardware" },
    { label: "Software", value: "Software" },
    { label: "Jaringan", value: "Jaringan" },
    { label: "Printer", value: "Printer" },
    { label: "Lainnya", value: "Lainnya" },
];

const priorityOptions = [
    {
        label: "Low",
        value: "Low",
        activeClass:
            "peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:border-emerald-500",
    },
    {
        label: "Medium",
        value: "Medium",
        activeClass:
            "peer-checked:bg-amber-400 peer-checked:text-gray-900 peer-checked:border-amber-400",
    },
    {
        label: "High",
        value: "High",
        activeClass:
            "peer-checked:bg-orange-500 peer-checked:text-white peer-checked:border-orange-500",
    },
    {
        label: "Critical",
        value: "Critical",
        activeClass:
            "peer-checked:bg-red-500 peer-checked:text-white peer-checked:border-red-500",
    },
];

const form = useForm({
    category: "",
    priority: "Low",
    photo: null,
    description: "",
});

const fileInput = ref(null);
const previewUrl = ref(null);

const openCamera = () => {
    fileInput.value?.click();
};

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        if (previewUrl.value) URL.revokeObjectURL(previewUrl.value);
        form.photo = file;
        previewUrl.value = URL.createObjectURL(file);
    }
};

// Bersihkan object URL saat komponen di-unmount (cegah memory leak)
onBeforeUnmount(() => {
    if (previewUrl.value) URL.revokeObjectURL(previewUrl.value);
});

const submit = () => {
    form.post("/user/ticket", {
        forceFormData: true,
        onSuccess: () => {
            form.reset();
            previewUrl.value = null;
        },
    });
};
const page = usePage();
const user = computed(() => page.props.auth.user);
</script>
