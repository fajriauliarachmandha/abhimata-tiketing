<template>
    <AdminLayout>
        <main class="py-6">
            <!-- Search & Add Data -->
            <div class="flex items-center justify-between space-x-2 py-4">
                <label for="input-group-1" class="sr-only">Pencarian</label>
                <div class="relative">
                    <div
                        class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none"
                    >
                        <svg
                            class="w-4 h-4 text-body"
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
                                stroke-width="2"
                                d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"
                            />
                        </svg>
                    </div>
                    <input
                        type="text"
                        id="input-group-1"
                        v-model="searchQuery"
                        class="block w-full max-w-96 ps-9 pe-3 py-4 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body"
                        placeholder="Pencarian . . ."
                    />
                </div>
            </div>

            <!-- Table Container -->
            <div
                class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default"
            >
                <table
                    class="w-full text-sm text-left rtl:text-right text-body"
                >
                    <thead
                        class="text-sm text-body bg-neutral-secondary-medium border-b border-t border-default-medium"
                    >
                        <tr>
                            <th scope="col" class="px-6 py-3 font-medium">
                                Nomer Tiket
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
                            <th scope="col" class="px-6 py-3 font-medium">
                                Tanggal Selesai
                            </th>
                            <th scope="col" class="px-6 py-3 font-medium">
                                Tanggal Dibuat
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="tickets in paginatedTickets"
                            :key="tickets.id"
                            class="bg-neutral-primary-soft border-b border-default hover:bg-neutral-secondary-medium"
                        >
                            <td
                                class="px-6 py-4 font-semibold text-heading whitespace-nowrap"
                            >
                                {{ tickets.ticket_number }}
                            </td>
                            <td
                                class="px-6 py-4 font-semibold text-heading whitespace-nowrap"
                            >
                                {{ tickets.department.department_name }}
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
                                    :src="`/storage/${tickets.photo}`"
                                    class="w-full h-full object-contain"
                                    alt="Preview Foto"
                                />
                            </td>
                            <td
                                class="px-6 py-4 font-semibold text-heading whitespace-nowrap"
                            >
                                {{ tickets.status }}
                            </td>
                            <td
                                class="px-6 py-4 font-semibold text-heading whitespace-nowrap"
                            >
                                {{ formatDate(tickets.resolved_at) }}
                            </td>
                            <td
                                class="px-6 py-4 font-semibold text-heading whitespace-nowrap"
                            >
                                {{ formatDate(tickets.created_at) }}
                            </td>
                        </tr>
                        <tr v-if="paginatedTickets.length === 0">
                            <td
                                colspan="3"
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
                    <span class="font-semibold text-heading">
                        {{ currentPage }}</span
                    >
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
                        class="inline-flex items-center text-body bg-neutral-secondary-medium border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading shadow-xs font-medium leading-5 rounded-s-base text-sm px-3 py-2 focus:outline-none"
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
                        class="inline-flex items-center text-body bg-neutral-secondary-medium border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading shadow-xs font-medium leading-5 rounded-e-base text-sm px-3 py-2 focus:outline-none"
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
import { router, useForm } from "@inertiajs/vue3";
import AdminLayout from "../../Layouts/AdminLayout.vue";

const props = defineProps({
    tickets: Array,
});

const searchQuery = ref("");

// search
const filteredTickets = computed(() => {
    if (!searchQuery.value.trim()) {
        return props.tickets;
    }
    const q = searchQuery.value.toLowerCase();
    return props.tickets.filter(
        (ticket) =>
            ticket.department?.department_name?.toLowerCase().includes(q) ||
            ticket.creator?.name?.toLowerCase().includes(q) ||
            ticket.technician?.name?.toLowerCase().includes(q) ||
            ticket.category?.toLowerCase().includes(q) ||
            ticket.status?.toLowerCase().includes(q),
    );
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

watch(searchQuery, () => {
    currentPage.value = 1; // Reset to first page on search
});

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
</script>
