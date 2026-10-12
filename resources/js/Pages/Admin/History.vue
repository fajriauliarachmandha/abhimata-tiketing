<template>
    <AdminLayout>
        <main>
            <!-- ==================== BAGIAN ATAS: pencarian, filter, export ==================== -->
            <div
                class="flex flex-col gap-3 py-4 md:flex-row md:items-center md:justify-between"
            >
                <div
                    class="flex flex-col sm:flex-row items-center gap-2 w-full md:w-auto"
                >
                    <!-- Kolom pencarian -->
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

                    <!-- Filter bulan: opsinya dibuat dari array `months` di bawah -->
                    <select
                        v-model="selectedMonth"
                        class="block w-full sm:w-36 h-10 text-sm text-heading bg-white border border-default-medium rounded-sm shadow-xs focus:ring-brand focus:border-brand dark:bg-gray-900 dark:text-white dark:border-gray-700"
                    >
                        <option value="">Semua Bulan</option>
                        <option
                            v-for="month in months"
                            :key="month.value"
                            :value="month.value"
                        >
                            {{ month.label }}
                        </option>
                    </select>

                    <!-- Filter tahun: opsinya diambil dari data tiket -->
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

                <!-- Tombol export Excel (nonaktif kalau tidak ada data) -->
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

            <!-- ==================== TABEL ==================== -->
            <div
                class="relative max-w-full overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-sm border border-default"
            >
                <table
                    class="w-full min-w-[720px] text-sm text-left rtl:text-right text-body"
                >
                    <thead
                        class="text-sm text-body bg-neutral-secondary-medium border-b border-t border-default-medium"
                    >
                        <tr>
                            <!-- Judul kolom dibuat dari array `tableHeaders` di bawah -->
                            <th
                                v-for="header in tableHeaders"
                                :key="header"
                                scope="col"
                                class="px-6 py-3 font-medium"
                            >
                                {{ header }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(ticket, index) in paginatedTickets"
                            :key="ticket.id"
                            class="bg-neutral-primary-soft border-b border-default hover:bg-neutral-secondary-medium"
                        >
                            <!-- Nomor urut tetap lanjut di halaman 2, 3, dst -->
                            <td :class="cellClass">
                                {{
                                    (currentPage - 1) * itemsPerPage + index + 1
                                }}
                            </td>
                            <td :class="cellClass">
                                {{ ticket.ticket_number }}
                            </td>
                            <td :class="cellClass">
                                {{ formatDate(ticket.created_at) }}
                            </td>
                            <td :class="cellClass">
                                {{ formatDate(ticket.resolved_at) }}
                            </td>
                            <td :class="cellClass">
                                {{ ticket.department?.department_name ?? "-" }}
                            </td>
                            <td :class="cellClass">
                                {{ ticket.creator?.name ?? "-" }}
                            </td>
                            <td :class="cellClass">
                                {{
                                    ticket.technician?.name ??
                                    "Belum Ditugaskan"
                                }}
                            </td>
                            <td :class="cellClass">{{ ticket.category }}</td>
                            <td :class="cellClass">{{ ticket.priority }}</td>
                            <td class="px-6 py-4 border-b border-default">
                                <div
                                    class="font-semibold text-heading whitespace-normal line-clamp-2 w-64"
                                >
                                    {{ ticket.description }}
                                </div>
                            </td>
                            <td :class="cellClass">
                                <img
                                    v-if="ticket.photo"
                                    :src="`/storage/${ticket.photo}`"
                                    class="w-full h-full object-contain"
                                    alt="Preview Foto"
                                />
                                <span v-else>-</span>
                            </td>
                            <td :class="cellClass">{{ ticket.status }}</td>
                        </tr>

                        <!-- Pesan kalau tidak ada data -->
                        <tr v-if="paginatedTickets.length === 0">
                            <td
                                :colspan="tableHeaders.length"
                                class="px-6 py-4 text-center text-body"
                            >
                                Tidak ada data yang cocok.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- ==================== PAGINATION ==================== -->
            <div class="flex flex-col items-center pt-5">
                <span class="text-sm text-body">
                    Halaman
                    <span class="font-semibold text-heading">{{
                        currentPage
                    }}</span>
                    dari
                    <span class="font-semibold text-heading">{{
                        totalPages
                    }}</span>
                    ({{ filteredTickets.length }} data)
                </span>

                <div class="inline-flex mt-4 -space-x-px">
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

                    <button
                        type="button"
                        @click="nextPage"
                        :disabled="currentPage >= totalPages"
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

// ==================== DATA DARI LARAVEL ====================
// `tickets` dikirim dari controller lewat Inertia::render(...)
const props = defineProps({
    tickets: Array,
});

// ==================== DATA TETAP (konstanta) ====================
const months = [
    { value: "01", label: "Januari" },
    { value: "02", label: "Februari" },
    { value: "03", label: "Maret" },
    { value: "04", label: "April" },
    { value: "05", label: "Mei" },
    { value: "06", label: "Juni" },
    { value: "07", label: "Juli" },
    { value: "08", label: "Agustus" },
    { value: "09", label: "September" },
    { value: "10", label: "Oktober" },
    { value: "11", label: "November" },
    { value: "12", label: "Desember" },
];

const tableHeaders = [
    "No.",
    "Nomor Tiket",
    "Tanggal Dibuat",
    "Tanggal Selesai",
    "Departemen",
    "Dibuat Oleh",
    "Teknisi",
    "Kategori",
    "Prioritas",
    "Deskripsi",
    "Foto",
    "Status",
];

// Class yang dipakai berulang di setiap sel tabel
const cellClass = "px-6 py-4 font-semibold text-heading whitespace-nowrap";

const itemsPerPage = 10;

// ==================== STATE (data yang berubah-ubah) ====================
const searchQuery = ref("");
const selectedMonth = ref("");
const selectedYear = ref("");
const currentPage = ref(1);

// Kalau filter berubah, kembali ke halaman 1
watch([searchQuery, selectedMonth, selectedYear], () => {
    currentPage.value = 1;
});

// ==================== FILTER ====================
// Daftar tahun untuk dropdown, diambil dari tanggal pembuatan tiket (terbaru di atas)
const availableYears = computed(() => {
    const years = props.tickets
        .filter((ticket) => ticket.created_at)
        .map((ticket) => new Date(ticket.created_at).getFullYear().toString());

    return [...new Set(years)].sort((a, b) => b - a);
});

// Apakah tiket cocok dengan kata yang diketik di kolom pencarian?
const matchesSearch = (ticket) => {
    const keyword = searchQuery.value.trim().toLowerCase();
    if (!keyword) return true; // tidak ada pencarian = semua lolos

    const searchableFields = [
        ticket.department?.department_name,
        ticket.creator?.name,
        ticket.technician?.name,
        ticket.category,
        ticket.status,
        ticket.ticket_number,
    ];

    return searchableFields.some((field) =>
        field?.toLowerCase().includes(keyword),
    );
};

// Apakah tiket cocok dengan bulan dan tahun yang dipilih?
const matchesDate = (ticket) => {
    if (!selectedMonth.value && !selectedYear.value) return true; // tidak ada filter tanggal
    if (!ticket.created_at) return false; // difilter tapi tiket tidak punya tanggal

    const date = new Date(ticket.created_at);
    const month = String(date.getMonth() + 1).padStart(2, "0"); // "1" jadi "01"
    const year = String(date.getFullYear());

    const monthOk = !selectedMonth.value || month === selectedMonth.value;
    const yearOk = !selectedYear.value || year === selectedYear.value;

    return monthOk && yearOk;
};

// Tiket yang lolos pencarian DAN filter tanggal
const filteredTickets = computed(() =>
    props.tickets.filter(
        (ticket) => matchesSearch(ticket) && matchesDate(ticket),
    ),
);

// ==================== PAGINATION ====================
const totalPages = computed(() =>
    Math.ceil(filteredTickets.value.length / itemsPerPage),
);

// Hanya tiket untuk halaman yang sedang dibuka
const paginatedTickets = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    return filteredTickets.value.slice(start, start + itemsPerPage);
});

const nextPage = () => {
    if (currentPage.value < totalPages.value) currentPage.value++;
};

const prevPage = () => {
    if (currentPage.value > 1) currentPage.value--;
};

// ==================== FORMAT TANGGAL ====================
const formatDate = (dateString) => {
    if (!dateString) return "-";

    return new Intl.DateTimeFormat("id-ID", {
        day: "2-digit",
        month: "long",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    }).format(new Date(dateString));
};

// ==================== EXPORT EXCEL ====================
// Yang diekspor SEMUA hasil filter (bukan hanya halaman yang tampil)
const exportExcel = () => {
    // 1. Ubah tiap tiket jadi satu baris Excel (nama properti = judul kolom)
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

    // 2. Buat sheet dan atur lebar kolom (urutannya sama dengan `rows`)
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

    // 3. Masukkan ke workbook lalu unduh sebagai .xlsx
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, "Riwayat Tiket");

    const today = new Date().toISOString().slice(0, 10);
    XLSX.writeFile(workbook, `riwayat-tiket-${today}.xlsx`);
};
</script>
