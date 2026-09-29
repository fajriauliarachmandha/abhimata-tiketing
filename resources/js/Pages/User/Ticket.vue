<template>
    <UserLayout>
        <main class="py-2">
            <h1
                class="text-base font-bold tracking-wide text-gray-900 uppercase"
            >
                PENGAJUAN TIKET
            </h1>

            <form class="max-w-md mx-auto my-7 px-4">
                <div class="mb-5">
                    <label
                        for="departement"
                        class="block mb-2.5 text-sm font-medium text-heading"
                        >Departement</label
                    >
                    <input
                        type="text"
                        id="departement"
                        class="bg-neutral-secondary-medium border border-dark-medium text-heading text-sm rounded-base block w-full px-4 py-4 shadow-xs placeholder:text-body"
                        placeholder="Departement"
                        required
                        :value="user.department.department_name"
                        disabled
                    />
                </div>

                <div class="mb-5">
                    <label
                        class="block mb-2.5 text-sm font-medium text-heading"
                    >
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
                                v-model="selectedCategory"
                                class="sr-only peer"
                            />
                            <div
                                class="px-4 py-4 bg-gray-100 text-gray-700 text-sm rounded-full border border-gray-200 transition-all duration-200 ease-in-out active:scale-95 peer-checked:bg-dark peer-checked:text-white peer-checked:border-dark peer-checked:shadow-md peer-checked:scale-[1.02]"
                            >
                                {{ item.label }}
                            </div>
                        </label>
                    </div>
                </div>

                <div class="mb-5">
                    <label
                        class="block mb-2.5 text-sm font-medium text-heading"
                    >
                        Tingkat Urgensi
                    </label>

                    <div class="flex flex-wrap gap-2">
                        <label
                            v-for="item in urgencyOptions"
                            :key="item.value"
                            class="cursor-pointer select-none"
                        >
                            <input
                                type="radio"
                                name="tingkat_urgensi"
                                :value="item.value"
                                v-model="selectedUrgency"
                                class="sr-only peer"
                            />

                            <!-- Chip dengan class aktif dinamis dari item.activeClass -->
                            <div
                                class="px-4 py-4 text-sm font-medium rounded-full border transition-all duration-200 ease-in-out active:scale-95 bg-gray-100 text-gray-700 border-gray-200 peer-checked:shadow-sm"
                                :class="item.activeClass"
                            >
                                {{ item.label }}
                            </div>
                        </label>
                    </div>
                </div>

                <div class="mb-5">
                    <!-- Label -->
                    <label
                        class="block mb-2.5 text-sm font-medium text-heading"
                    >
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

                    <!-- Area Kotak Putus-putus (Dapat Diklik) -->
                    <div
                        @click="openCamera"
                        class="relative w-full h-32 bg-gray-200 border-2 border-dashed border-gray-400 rounded-lg flex flex-col items-center justify-center cursor-pointer hover:bg-gray-300 transition-colors overflow-hidden"
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
                        class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-4 shadow-xs placeholder:text-body"
                        placeholder="Jelaskan kendala barang..."
                    ></textarea>
                </div>

                <button
                    type="submit"
                    class="text-white bg-primary-500 box-border border border-transparent hover:bg-primary-600 focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-full text-sm px-4 py-4 focus:outline-none my-4 w-full"
                >
                    Submit
                </button>
            </form>
        </main></UserLayout
    >
</template>

<script setup>
import { ref, onBeforeUnmount, computed } from "vue";
import UserLayout from "../../Layouts/UserLayout.vue";
import { usePage } from "@inertiajs/vue3";

const categoryOptions = [
    { label: "Hardware", value: "hardware" },
    { label: "Software", value: "software" },
    { label: "Jaringan", value: "jaringan" },
    { label: "Printer", value: "printer" },
    { label: "Lain-Lain", value: "lain-lain" },
];

const urgencyOptions = [
    {
        label: "Low",
        value: "low",
        activeClass:
            "peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:border-emerald-500",
    },
    {
        label: "Medium",
        value: "medium",
        activeClass:
            "peer-checked:bg-amber-400 peer-checked:text-gray-900 peer-checked:border-amber-400",
    },
    {
        label: "High",
        value: "high",
        activeClass:
            "peer-checked:bg-orange-500 peer-checked:text-white peer-checked:border-orange-500",
    },
    {
        label: "Critical",
        value: "critical",
        activeClass:
            "peer-checked:bg-red-500 peer-checked:text-white peer-checked:border-red-500",
    },
];

const fileInput = ref(null);
const previewUrl = ref(null);

const openCamera = () => {
    fileInput.value?.click();
};

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        if (previewUrl.value) URL.revokeObjectURL(previewUrl.value);
        selectedFile.value = file;
        previewUrl.value = URL.createObjectURL(file);
    }
};

// Bersihkan object URL saat komponen di-unmount (cegah memory leak)
onBeforeUnmount(() => {
    if (previewUrl.value) URL.revokeObjectURL(previewUrl.value);
});

const submit = () => {
    form.post("/user/ticket", {
        ForceFormData: true,
        onSuccess: () => {
            form.reset();
            previewUrl.value = null;
        },
    });
};
const page = usePage();
const user = computed(() => page.props.auth.user);
</script>
