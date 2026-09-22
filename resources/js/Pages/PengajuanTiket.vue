<template>
    <MobileLayout>
        <div class="relative flex items-center justify-center bg-white">
            <button
                @click="goBack"
                class="absolute left-2 text-gray-900 hover:text-gray-600 transition-colors focus:outline-none"
                aria-label="Kembali"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="#000000"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="lucide lucide-move-left preview-icon"
                >
                    <path d="M6 8L2 12L6 16" />
                    <path d="M2 12H22" />
                </svg>
            </button>

            <h1
                class="text-base font-bold tracking-wide text-gray-900 uppercase"
            >
                PENGAJUAN TIKET
            </h1>
        </div>

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
                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                    placeholder="name@flowbite.com"
                    required
                    value="Departement PPIC"
                    disabled
                />
            </div>

            <div class="mb-5">
                <label
                    for="kendala"
                    class="block mb-2.5 text-sm font-medium text-heading"
                    >Kategori Kendala</label
                >

                <!-- Daftar Checkbox Chip -->
                <div class="flex flex-wrap gap-2">
                    <label
                        v-for="item in options"
                        :key="item"
                        class="cursor-pointer"
                    >
                        <!-- radio asli disembunyikan dengan `sr-only` -->
                        <input
                            type="radio"
                            name="kategori_kendala"
                            :value="item"
                            v-model="selectedCategories"
                            class="sr-only peer"
                        />

                        <!-- Tampilan Chip (Tampilan Berubah Saat Centang Via `peer-checked`) -->
                        <div
                            class="px-3.5 py-1.5 bg-gray-100 text-gray-700 text-sm rounded-lg border border-gray-200 transition-all duration-200 ease-in-out active:scale-95 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 peer-checked:shadow-md peer-checked:scale-[1.02]"
                        >
                            {{ item }}
                        </div>
                    </label>
                </div>
            </div>

            <div class="mb-5">
                <label class="block mb-2.5 text-sm font-medium text-heading">
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
                            class="px-3.5 py-1.5 text-sm font-medium rounded-lg border transition-all duration-200 ease-in-out active:scale-95 bg-gray-100 text-gray-700 border-gray-200 peer-checked:shadow-sm"
                            :class="item.activeClass"
                        >
                            {{ item.label }}
                        </div>
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

                <!-- Area Kotak Putus-putus (Dapat Diklik) -->
                <div
                    @click="openCamera"
                    class="relative w-full h-32 bg-gray-200 border-2 border-dashed border-gray-400 rounded-lg flex flex-col items-center justify-center cursor-pointer hover:bg-gray-300 transition-colors overflow-hidden"
                >
                    <!-- Tampilan Setelah Foto Diambil (Preview) -->
                    <img
                        v-if="previewUrl"
                        :src="previewUrl"
                        class="w-full h-full object-cover"
                        alt="Preview Foto"
                    />

                    <!-- Tampilan Awal (Kosong) -->
                    <div
                        v-else
                        class="flex flex-col items-center text-gray-500"
                    >
                        <Camera class="w-8 h-8 mb-1" />
                        <span class="text-xs font-medium">Ambil Foto</span>
                    </div>
                </div>
            </div>

            <div class="mb-5">
                <label
                    for="countries"
                    class="block mb-2.5 text-sm font-medium text-heading"
                    >Lokasi Perbaikan</label
                >
                <select
                    id="countries"
                    class="block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body"
                >
                    <option selected>Pilih lokasi</option>
                    <option value="US">Ruang A-1</option>
                    <option value="CA">Ruang A-2</option>
                </select>
            </div>

            <div class="mb-5">
                <label
                    for="message"
                    class="block mb-2.5 text-sm font-medium text-heading"
                    >Deskripsi</label
                >
                <textarea
                    id="message"
                    rows="4"
                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-3.5 shadow-xs placeholder:text-body"
                    placeholder="Jelaskan kendala barang..."
                ></textarea>
            </div>

            <button
                type="submit"
                class="text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none my-4 w-full"
            >
                Submit
            </button>
        </form>
    </MobileLayout>
</template>

<script setup>
import MobileLayout from "@/Layouts/MobileLayout.vue";

const goBack = () => {
    window.history.back();
};

import { ref } from "vue";

// Opsi item sesuai gambar
const options = ["Hardware", "Software", "Jaringan", "Printer", "Lain-Lain"];

// Array penampung item yang dicentang
const selectedCategories = ref("");

const selectedUrgency = ref("low");

// Masing-masing opsi menyimpan Tailwind class terpisah saat 'peer-checked'
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
const selectedFile = ref(null);
const previewUrl = ref(null);

const openCamera = () => {
    fileInput.value?.click();
};

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        selectedFile.value = file;
        previewUrl.value = URL.createObjectURL(file);
    }
};
</script>
