<template>
    <AdminLayout>
        <main>
            <!-- Search & Filters -->
            <div
                class="flex flex-col gap-3 py-4 md:flex-row md:items-center md:justify-between"
            >
                <!-- Kiri: Search, Filter Bulan, Filter Tahun -->
                <div
                    class="flex flex-col sm:flex-row items-center gap-2 w-full md:w-auto"
                >
                    <!-- Input Search -->
                    <div class="relative w-full sm:w-64">
                        <div
                            class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none"
                        >
                            <MinimalisticMagnifierIcon
                                size="20"
                                class="text-gray-400"
                            />
                        </div>
                        <input
                            type="text"
                            v-model="searchQuery"
                            class="block w-full h-10 ps-9 pe-3 text-sm text-heading bg-white border border-default-medium rounded-sm shadow-xs placeholder:text-body focus:ring-brand focus:border-brand dark:bg-gray-900 dark:text-white dark:border-gray-700 dark:placeholder:text-gray-500"
                            placeholder="Pencarian . . ."
                        />
                    </div>

                    <!-- Select Bulan -->
                    <select
                        v-model="selectedMonth"
                        class="block w-full sm:w-36 h-10 text-sm text-heading bg-white border border-default-medium rounded-sm shadow-xs focus:ring-brand focus:border-brand dark:bg-gray-900 dark:text-white dark:border-gray-700"
                    >
                        <option value="">Semua Bulan</option>
                        <option value="01">Januari</option>
                        <option value="02">Februari</option>
                        <option value="03">Maret</option>
                        <option value="04">April</option>
                        <option value="05">Mei</option>
                        <option value="06">Juni</option>
                        <option value="07">Juli</option>
                        <option value="08">Agustus</option>
                        <option value="09">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>

                    <!-- Select Tahun -->
                    <select
                        v-model="selectedYear"
                        class="block w-full sm:w-32 h-10 text-sm text-heading bg-white border border-default-medium rounded-sm shadow-xs focus:ring-brand focus:border-brand dark:bg-gray-900 dark:text-white dark:border-gray-700"
                    >
                        <option value="">Semua Tahun</option>
                        <option
                            v-for="year in availableYears"
                            :key="year"
                            :value="year"
                        >
                            {{ year }}
                        </option>
                    </select>
                </div>

                <!-- Kanan: Button Export -->
                <button
                    type="button"
                    @click="exportExcel"
                    :disabled="filteredTickets.length === 0"
                    class="inline-flex shrink-0 items-center justify-center gap-1.5 h-10 px-4 text-sm font-semibold text-white bg-green-600 hover:bg-green-700 border border-transparent rounded-sm shadow-xs focus:outline-none focus:ring-4 focus:ring-green-200 active:scale-95 transition duration-200 disabled:opacity-60 disabled:pointer-events-none"
                >
                    <svg
                        class="w-5 h-5"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 15v2a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-2m-8 1V4m0 12-4-4m4 4 4-4"
                        />
                    </svg>
                    <span>Export Excel</span>
                </button>
            </div>

            <!-- Table Container -->
            <div
                class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-sm border border-default"
            >
                <table
                    class="w-full text-sm text-left rtl:text-right text-body"
                >
                    <thead
                        class="text-sm text-body bg-neutral-secondary-medium border-b border-t border-default-medium"
                    >
                        <tr>
                            <th scope="col" class="px-6 py-3 font-medium">
                                No.
                            </th>
                            <th scope="col" class="px-6 py-3 font-medium">
                                Nomer Tiket
                            </th>
                            <th scope="col" class="px-6 py-3 font-medium">
                                Tanggal Dibuat
                            </th>
                            <th scope="col" class="px-6 py-3 font-medium">
                                Tanggal Selesai
                            </th>
                            <th scope="col" class="px-6 py-3 font-medium">
                                Department
                            </th>
                            <th scope="col" class="px-6 py-3 font-medium">
                                Dibuat Oleh
                            </th>
                            <th scope="col" class="px-6 py-3 font-medium">
                                Teknisi
                            </th>
                            <th scope="col" class="px-6 py-3 font-medium">
                                Kategori
                            </th>
                            <th scope="col" class="px-6 py-3 font-medium">
                                Prioritas
                            </th>
                            <th scope="col" class="px-6 py-3 font-medium">
                                Deskripsi
                            </th>
                            <th scope="col" class="px-6 py-3 font-medium">
                                Foto
                            </th>
                            <th scope="col" class="px-6 py-3 font-medium">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(tickets, index) in paginatedTickets"
                            :key="tickets.id"
                            class="bg-neutral-primary-soft border-b border-default hover:bg-neutral-secondary-medium"
                        >
                            <td
                                class="px-6 py-4 font-semibold text-heading whitespace-nowrap"
                            >
                                {{
                                    (currentPage - 1) * itemsPerPage + index + 1
                                }}
                            </td>
                            <td
                                class="px-6 py-4 font-semibold text-heading whitespace-nowrap"
                            >
                                {{ tickets.ticket_number }}
                            </td>
                            <td
                                class="px-6 py-4 font-semibold text-heading whitespace-nowrap"
                            >
                                {{ formatDate(tickets.created_at) }}
                            </td>
                            <td
                                class="px-6 py-4 font-semibold text-heading whitespace-nowrap"
                            >
                                {{ formatDate(tickets.resolved_at) }}
                            </td>
                            <td
                                class="px-6 py-4 font-semibold text-heading whitespace-nowrap"
                            >
                                {{ tickets.department?.department_name ?? "-" }}
                            </td>
                            <td
                                class="px-6 py-4 font-semibold text-heading whitespace-nowrap"
                            >
                                {{ tickets.creator?.name ?? "-" }}
                            </td>
                            <td
                                class="px-6 py-4 font-semibold text-heading whitespace-nowrap"
                            >
                                {{
                                    tickets.technician
                                        ? tickets.technician.name
                                        : "Belum Ditugaskan"
                                }}
                            </td>
                            <td
                                class="px-6 py-4 font-semibold text-heading whitespace-nowrap"
                            >
                                {{ tickets.category }}
                            </td>
                            <td
                                class="px-6 py-4 font-semibold text-heading whitespace-nowrap"
                            >
                                {{ tickets.priority }}
                            </td>
                            <td class="px-6 py-4 border-b border-default">
                                <div
                                    class="font-semibold text-heading whitespace-normal line-clamp-2 w-64"
                                >
                                    {{ tickets.description }}
                                </div>
                            </td>
                            <td
                                class="px-6 py-4 font-semibold text-heading whitespace-nowrap"
                            >
                                <img
                                    v-if="tickets.photo"
                                    :src="`/storage/${tickets.photo}`"
                                    class="w-full h-full object-contain"
                                    alt="Preview Foto"
                                />
                                <span v-else>-</span>
                            </td>
                            <td
                                class="px-6 py-4 font-semibold text-heading whitespace-nowrap"
                            >
                                {{ tickets.status }}
                            </td>
                        </tr>
                        <tr v-if="paginatedTickets.length === 0">
                            <td
                                colspan="12"
                                class="px-6 py-4 text-center text-body"
                            >
                                Tidak ada data yang cocok.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col items-center pt-5">
                <span class="text-sm text-body">
                    Menampilkan Halaman
                    <span class="font-semibold text-heading">{{
                        currentPage
                    }}</span>
                    dari
                    <span class="font-semibold text-heading">{{
                        filteredTickets.length
                    }}</span>
                    Data
                </span>
                <!-- Buttons -->
                <div class="inline-flex mt-4 -space-x-px">
                    <!-- Previous Button -->
                    <button
                        type="button"
                        @click="prevPage"
                        :disabled="currentPage === 1"
                        class="inline-flex items-center text-body bg-neutral-secondary-medium border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading shadow-xs font-medium leading-5 rounded-s-sm text-sm px-3 py-2 focus:outline-none"
                    >
                        <svg
                            class="w-4 h-4 me-1.5 -ms-0.5 rtl:rotate-180"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 12h14M5 12l4-4m-4 4 4 4"
                            />
                        </svg>
                        Previous
                    </button>
                    <!-- Next Button -->
                    <button
                        type="button"
                        @click="nextPage"
                        :disabled="
                            currentPage === totalPages || totalPages === 0
                        "
                        class="inline-flex items-center text-body bg-neutral-secondary-medium border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading shadow-xs font-medium leading-5 rounded-e-sm text-sm px-3 py-2 focus:outline-none"
                    >
                        Next
                        <svg
                            class="w-4 h-4 ms-1.5 -me-0.5 rtl:rotate-180"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 12H5m14 0-4 4m4-4-4-4"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </main>
    </AdminLayout>
</template>

<script setup>
import { computed, ref, watch } from "vue";
import * as XLSX from "xlsx";
import AdminLayout from "../../Layouts/AdminLayout.vue";
import { MinimalisticMagnifierIcon } from "@solar-icons/vue/linear/minimalistic-magnifier";

const props = defineProps({
    tickets: Array,
});

// State untuk filter
const searchQuery = ref("");
const selectedMonth = ref("");
const selectedYear = ref("");

// Menghasilkan daftar tahun secara dinamis berdasarkan data tiket
// atau bisa juga hardcode (misal: ['2023', '2024', '2025'])
const availableYears = computed(() => {
    if (!props.tickets) return [];

    // Ambil semua tahun dari created_at
    const years = props.tickets
        .filter((t) => t.created_at) // Pastikan tanggal ada
        .map((t) => new Date(t.created_at).getFullYear().toString());

    // Hilangkan duplikat dan urutkan menurun (terbaru di atas)
    return [...new Set(years)].sort((a, b) => b - a);
});

// Reset ke halaman 1 setiap kali filter berubah
watch([searchQuery, selectedMonth, selectedYear], () => {
    currentPage.value = 1;
});

// Logika Filter Berlapis (Search AND Bulan AND Tahun)
const filteredTickets = computed(() => {
    return props.tickets.filter((ticket) => {
        // 1. Cek Pencarian Teks
        let matchesSearch = true;
        if (searchQuery.value.trim()) {
            const q = searchQuery.value.toLowerCase();
            matchesSearch =
                ticket.department?.department_name?.toLowerCase().includes(q) ||
                ticket.creator?.name?.toLowerCase().includes(q) ||
                ticket.technician?.name?.toLowerCase().includes(q) ||
                ticket.category?.toLowerCase().includes(q) ||
                ticket.status?.toLowerCase().includes(q) ||
                ticket.ticket_number?.toLowerCase().includes(q);
        }

        // 2. Cek Tanggal (harus ada created_at untuk memfilter bulan/tahun)
        let matchesMonth = true;
        let matchesYear = true;

        if (ticket.created_at) {
            const ticketDate = new Date(ticket.created_at);
            // padStart(2, '0') memastikan bulan jadi "01", "02", dst
            const ticketMonth = String(ticketDate.getMonth() + 1).padStart(
                2,
                "0",
            );
            const ticketYear = String(ticketDate.getFullYear());

            if (selectedMonth.value) {
                matchesMonth = ticketMonth === selectedMonth.value;
            }
            if (selectedYear.value) {
                matchesYear = ticketYear === selectedYear.value;
            }
        } else if (selectedMonth.value || selectedYear.value) {
            // Jika tiket tidak punya tanggal, tapi difilter, buang dari hasil
            return false;
        }

        // Tiket lolos hanya jika memenuhi KETIGA syarat filter
        return matchesSearch && matchesMonth && matchesYear;
    });
});

const formatDate = (dateString) => {
    if (!dateString) {
        return "-";
    }
    const date = new Date(dateString);
    return new Intl.DateTimeFormat("id-ID", {
        day: "2-digit",
        month: "long",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    }).format(date);
};

// pagination
const currentPage = ref(1);
const itemsPerPage = ref(10);

const totalPages = computed(() => {
    return Math.ceil(filteredTickets.value.length / itemsPerPage.value);
});

const paginatedTickets = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return filteredTickets.value.slice(start, end);
});

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
};

const prevPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
};

// ==================== EXPORT EXCEL (DI BROWSER) ====================
// Yang diekspor SEMUA hasil pencarian (filteredTickets), bukan hanya halaman yang tampil
const exportExcel = () => {
    // 1. Ubah data tiket menjadi baris Excel (kunci objek = judul kolom)
    const rows = filteredTickets.value.map((ticket) => ({
        "Nomor Tiket": ticket.ticket_number,
        "Tanggal Dibuat": formatDate(ticket.created_at),
        "Tanggal Selesai": formatDate(ticket.resolved_at),
        Departemen: ticket.department?.department_name ?? "-",
        "Dibuat Oleh": ticket.creator?.name ?? "-",
        Teknisi: ticket.technician?.name ?? "Belum Ditugaskan",
        Kategori: ticket.category,
        Prioritas: ticket.priority,
        Deskripsi: ticket.description,
        Status: ticket.status,
    }));

    // 2. Buat sheet, lalu atur lebar tiap kolom
    const worksheet = XLSX.utils.json_to_sheet(rows);
    worksheet["!cols"] = [
        { wch: 26 }, // Nomor Tiket
        { wch: 26 }, // Tanggal Dibuat
        { wch: 26 }, // Tanggal Selesai
        { wch: 30 }, // Departemen
        { wch: 22 }, // Dibuat Oleh
        { wch: 22 }, // Teknisi
        { wch: 14 }, // Kategori
        { wch: 12 }, // Prioritas
        { wch: 50 }, // Deskripsi
        { wch: 14 }, // Status
    ];

    // 3. Masukkan sheet ke workbook, lalu unduh sebagai file .xlsx
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, "Riwayat Tiket");

    const today = new Date().toISOString().slice(0, 10);
    XLSX.writeFile(workbook, `riwayat-tiket-${today}.xlsx`);
};
</script>
