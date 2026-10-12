<template>
    <UserLayout>
        <main>
            <div class="mx-auto flex items-center justify-center">
                <h1
                    class="text-base font-bold tracking-wide text-gray-900 dark:text-white uppercase"
                >
                    PENGAJUAN TIKET
                </h1>
            </div>

            <form @submit.prevent="submit" class="mx-auto my-7 px-4">
                <!-- DEPARTEMEN (otomatis dari akun, tidak bisa diubah) -->
                <div class="mb-5">
                    <label for="department" :class="labelClass">
                        Departemen
                        <span class="text-xs text-red-500"> * </span>
                    </label>
                    <input
                        type="text"
                        id="department"
                        class="block w-full h-10 px-3 text-sm text-heading dark:text-white bg-white dark:bg-gray-800 border border-default-medium dark:border-gray-700 rounded-sm shadow-xs placeholder:text-body dark:placeholder-gray-400 focus:ring-brand focus:border-brand disabled:opacity-50"
                        :value="user.department?.department_name"
                        disabled
                    />
                </div>

                <!-- KATEGORI KENDALA -->
                <div class="mb-5">
                    <span :class="labelClass">
                        Kategori Kendala
                        <span class="text-xs text-red-500"> * </span>
                    </span>
                    <div class="flex flex-wrap gap-2">
                        <label
                            v-for="category in categories"
                            :key="category"
                            class="cursor-pointer"
                        >
                            <input
                                type="radio"
                                name="category"
                                :value="category"
                                v-model="form.category"
                                class="sr-only peer"
                            />
                            <div :class="[chipClass, categoryActiveClass]">
                                {{ category }}
                            </div>
                        </label>
                    </div>
                    <span v-if="form.errors.category" :class="errorClass">
                        {{ form.errors.category }}
                    </span>
                </div>

                <!-- TINGKAT PRIORITAS -->
                <div class="mb-5">
                    <span :class="labelClass">
                        Tingkat Prioritas
                        <span class="text-xs text-red-500"> * </span>
                    </span>
                    <div class="flex flex-wrap gap-2">
                        <label
                            v-for="priority in priorityOptions"
                            :key="priority.value"
                            class="cursor-pointer select-none"
                        >
                            <input
                                type="radio"
                                name="priority"
                                :value="priority.value"
                                v-model="form.priority"
                                class="sr-only peer"
                            />
                            <div :class="[chipClass, priority.activeClass]">
                                {{ priority.label }}
                            </div>
                        </label>
                    </div>
                    <span v-if="form.errors.priority" :class="errorClass">
                        {{ form.errors.priority }}
                    </span>
                </div>

                <!-- FOTO -->
                <div class="mb-5">
                    <span :class="labelClass">Foto</span>

                    <!-- Input file disembunyikan, dibuka lewat klik pada kotak di bawah -->
                    <input
                        ref="fileInput"
                        type="file"
                        accept="image/*"
                        capture="environment"
                        class="hidden"
                        @change="handleFileChange"
                    />
                    <div
                        @click="openPhotoPicker"
                        class="relative w-full h-32 bg-gray-50 dark:bg-gray-800 border-2 border-dashed border-gray-400 dark:border-gray-600 rounded-sm flex flex-col items-center justify-center cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors overflow-hidden"
                    >
                        <img
                            v-if="previewUrl"
                            :src="previewUrl"
                            class="w-full h-full object-contain"
                            alt="Preview Foto"
                        />
                        <div
                            v-else
                            class="flex flex-col items-center text-gray-500 dark:text-gray-400"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-camera preview-icon mb-1"
                            >
                                <path
                                    d="M13.997 4a2 2 0 0 1 1.76 1.05l.486.9A2 2 0 0 0 18.003 7H20a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h1.997a2 2 0 0 0 1.759-1.048l.489-.904A2 2 0 0 1 10.004 4z"
                                />
                                <circle cx="12" cy="13" r="3" />
                            </svg>
                            <span class="text-xs font-medium">Ambil Foto</span>
                        </div>
                    </div>
                    <span v-if="form.errors.photo" :class="errorClass">
                        {{ form.errors.photo }}
                    </span>
                </div>

                <!-- DESKRIPSI -->
                <div class="mb-5">
                    <label for="description" :class="labelClass">
                        Deskripsi
                        <span class="text-xs text-red-500"> * </span>
                    </label>
                    <textarea
                        id="description"
                        rows="4"
                        v-model="form.description"
                        class="bg-white dark:bg-gray-800 border border-default-medium dark:border-gray-700 text-heading dark:text-white text-sm rounded-sm focus:ring-brand focus:border-brand block w-full p-4 shadow-xs placeholder:text-body dark:placeholder-gray-400"
                        placeholder="Jelaskan kendala kamu..."
                    ></textarea>
                    <span v-if="form.errors.description" :class="errorClass">
                        {{ form.errors.description }}
                    </span>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="inline-flex items-center justify-center gap-1.5 h-10 px-4 text-sm font-semibold text-white bg-primary-500 hover:bg-primary-600 border border-transparent rounded-sm shadow-xs focus:outline-none focus:ring-4 focus:ring-brand-medium active:scale-95 transition duration-200 w-full disabled:opacity-60"
                >
                    Ajukan Tiket
                </button>
            </form>
        </main>
    </UserLayout>
</template>

<script setup>
import { ref, onBeforeUnmount, computed, watch } from "vue";
import UserLayout from "../../Layouts/UserLayout.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import Swal from "sweetalert2";

// Data user yang sedang login (dibagikan Inertia ke semua halaman)
const page = usePage();
const user = computed(() => page.props.auth.user);

// ==================== DATA TETAP (konstanta) ====================
const categories = ["Hardware", "Software", "Jaringan", "Printer", "Lainnya"];

// `activeClass` = warna tombol saat dipilih
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

// Class yang dipakai berulang di form
const labelClass =
    "block mb-2.5 text-sm font-medium text-heading dark:text-gray-200";
const errorClass = "text-xs text-red-500 mt-1 block";

// Tombol pilihan bulat (kategori & prioritas) memakai dasar class yang sama
const chipClass =
    "inline-flex items-center justify-center h-10 px-4 bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm rounded-full border border-gray-300 dark:border-gray-700 transition-all duration-200 ease-in-out active:scale-95 peer-checked:shadow-md peer-checked:scale-[1.02]";
const categoryActiveClass =
    "peer-checked:bg-dark peer-checked:text-white peer-checked:border-dark dark:peer-checked:bg-gray-100 dark:peer-checked:text-gray-900 dark:peer-checked:border-gray-100";

// ==================== NOTIFIKASI (SWEETALERT) ====================
const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
});

// Pesan dari controller (->with('success'/'error', ...)) tampil sebagai toast
watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.success)
            Toast.fire({ icon: "success", title: flash.success });
        if (flash?.error) Toast.fire({ icon: "error", title: flash.error });
    },
    { deep: true },
);

// ==================== FORM ====================
// useForm dari Inertia: menyimpan isi form, error validasi, dan status "sedang mengirim"
const form = useForm({
    category: "",
    priority: "Low",
    photo: null,
    description: "",
});

// ==================== FOTO ====================
const fileInput = ref(null); // mengacu ke <input type="file"> yang disembunyikan
const previewUrl = ref(null); // alamat sementara untuk menampilkan foto yang dipilih

const openPhotoPicker = () => {
    fileInput.value?.click();
};

// Hapus alamat sementara foto dari memori browser
const clearPreview = () => {
    if (previewUrl.value) URL.revokeObjectURL(previewUrl.value);
    previewUrl.value = null;
};

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (!file) return;

    clearPreview(); // buang preview foto sebelumnya
    form.photo = file;
    previewUrl.value = URL.createObjectURL(file);
};

// Bersihkan saat halaman ditutup (mencegah memory leak)
onBeforeUnmount(clearPreview);

// ==================== KIRIM FORM ====================
const submit = () => {
    form.post("/user/ticket", {
        forceFormData: true, // wajib supaya file foto ikut terkirim
        onSuccess: () => {
            form.reset();
            clearPreview();
        },
        // Kalau gagal, isian form tetap ada supaya tidak perlu mengetik ulang
        onError: (errors) => {
            Swal.fire({
                icon: "error",
                title: "Gagal mengajukan tiket",
                text:
                    Object.values(errors)[0] ??
                    "Periksa kembali data yang diisi.",
            });
        },
    });
};
</script>
