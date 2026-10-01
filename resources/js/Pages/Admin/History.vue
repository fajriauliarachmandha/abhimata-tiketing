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
                                prioritas
                            </th>
                            <th scope="col" class="px-6 py-3 font-medium">
                                Foto
                            </th>
                            <th scope="col" class="px-6 py-3 font-medium">
                                status
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
                            v-for="tickets in filteredTickets"
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
                            <td
                                class="px-6 py-4 font-semibold text-heading whitespace-nowrap"
                            >
                                {{ tickets.description }}
                            </td>
                            <td
                                class="px-6 py-4 font-semibold text-heading whitespace-nowrap"
                            >
                                {{ tickets.photo }}
                            </td>
                            <td
                                class="px-6 py-4 font-semibold text-heading whitespace-nowrap"
                            >
                                {{ tickets.status }}
                            </td>
                            <td
                                class="px-6 py-4 font-semibold text-heading whitespace-nowrap"
                            >
                                {{ tickets.resolved_at }}
                            </td>
                            <td
                                class="px-6 py-4 font-semibold text-heading whitespace-nowrap"
                            >
                                {{ tickets.created_at }}
                            </td>
                        </tr>
                        <tr v-if="filteredTickets.length === 0">
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
        </main>
    </AdminLayout>
</template>

<script setup>
import { computed, ref } from "vue";
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
            ticket.ticket_number.toLowerCase().includes(q) ||
            ticket.department_id.toLowerCase().includes(q) ||
            ticket.created_by.toLowerCase().includes(q) ||
            ticket.assigned_to.toLowerCase().includes(q) ||
            ticket.category.toLowerCase().includes(q) ||
            ticket.description.toLowerCase().includes(q) ||
            ticket.photo.toLowerCase().includes(q) ||
            ticket.status.toLowerCase().includes(q) ||
            ticket.resolved.toLowerCase().includes(q),
    );
});
</script>
