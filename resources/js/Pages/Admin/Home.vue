<template>
    <AdminLayout>
        <main>
            <!-- Header: profil (kiri) + filter (kanan di laptop) -->
            <div class="lg:flex lg:items-center lg:justify-between lg:gap-6">
                <div class="flex items-center gap-2.5 ps-0.5">
                    <img
                        :src="avatarUrl"
                        alt="avatar"
                        class="w-10 h-10 lg:w-14 lg:h-14 rounded-full ring-2 ring-green-500"
                    />
                    <div class="leading-tight">
                        <div class="font-bold text-sm lg:text-lg text-heading">
                            Hallo, {{ user.name }}
                        </div>
                        <div class="text-xs lg:text-sm text-body">
                            {{
                                user.role === "department"
                                    ? user.department?.department_name
                                    : "Departemen IT Support"
                            }}
                        </div>
                        <!-- <span
                        class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-800 text-xs font-medium px-1.5 py-0.5 rounded-full"
                    >
                        <StarIcon size="12" />
                        17 Stars
                    </span> -->
                    </div>
                </div>

                <!-- Filter -->
                <div
                    class="sticky top-[72px] z-10 flex gap-2 py-2 -mx-4 px-4 overflow-x-auto lg:static lg:mx-0 lg:px-0 lg:py-0 lg:overflow-visible"
                >
                    <button
                        v-for="status in statusFilter"
                        :key="status.value"
                        type="button"
                        @click="activeFilter = status.value"
                        class="px-3 py-2 text-xs lg:text-sm rounded-full whitespace-nowrap transition active:scale-95"
                        :class="
                            activeFilter === status.value
                                ? 'bg-dark text-white'
                                : 'bg-white text-gray-700 hover:bg-gray-200'
                        "
                    >
                        {{ status.label }}
                    </button>
                </div>
            </div>

            <div
                class="pt-4 pb-4 font-semibold text-lg lg:text-2xl text-heading"
            >
                Daftar Order
            </div>

            <ul
                class="max-w-md space-y-4 lg:max-w-none lg:space-y-0 lg:grid lg:grid-cols-2 xl:grid-cols-3 lg:gap-4"
            >
                <li
                    v-if="filteredTickets.length === 0"
                    class="py-6 text-center text-sm text-body dark:text-gray-400 lg:col-span-full"
                >
                    Belum ada perbaikan.
                </li>

                <li
                    v-for="ticket in filteredTickets"
                    :key="ticket.id"
                    class="h-full"
                >
                    <Link
                        :href="`/admin/ticket/${ticket.id}`"
                        class="block h-full"
                    >
                        <!-- drop-shadow di pembungkus, supaya bayangan ikut bentuk berlekuk -->
                        <div class="flex h-full flex-col drop-shadow-lg">
                            <!-- Bagian atas -->
                            <div
                                class="ticket-top flex flex-1 flex-col rounded-t-2xl bg-white dark:bg-gray-800 p-5 pb-6"
                            >
                                <div
                                    class="flex items-start justify-between gap-3"
                                >
                                    <div class="min-w-0 leading-tight">
                                        <p
                                            class="text-[11px] text-gray-400 dark:text-gray-500"
                                        >
                                            Departemen
                                        </p>
                                        <p
                                            class="text-sm font-semibold text-gray-900 dark:text-white truncate"
                                        >
                                            {{
                                                ticket.department
                                                    ?.department_name ?? "-"
                                            }}
                                        </p>
                                    </div>
                                    <span
                                        class="shrink-0 text-xs font-medium px-2 py-0.5 rounded-full"
                                        :class="getStatusClass(ticket.status)"
                                    >
                                        {{ ticket.status }}
                                    </span>
                                </div>

                                <p
                                    class="mt-2 text-sm text-gray-900 dark:text-gray-200 break-words line-clamp-3"
                                >
                                    {{ ticket.description }}
                                </p>

                                <span
                                    class="inline-flex w-fit items-center gap-1.5 rounded-full bg-gray-100 dark:bg-gray-700 px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 mt-1"
                                >
                                    <ProgrammingIcon size="15" />
                                    {{ ticket.category }}
                                </span>

                                <div class="mt-auto pt-1 leading-tight">
                                    <p
                                        class="text-[11px] text-gray-400 dark:text-gray-500"
                                    >
                                        Teknisi
                                    </p>
                                    <p
                                        class="text-sm font-semibold text-gray-900 dark:text-white"
                                    >
                                        {{
                                            ticket.technician
                                                ? ticket.technician.name
                                                : "Belum ada teknisi"
                                        }}
                                    </p>
                                </div>
                            </div>

                            <!-- Bagian bawah -->
                            <div
                                class="ticket-bottom relative rounded-b-2xl bg-white dark:bg-gray-800 p-5 pt-6"
                            >
                                <!-- garis putus-putus, tepat di batas kedua bagian -->
                                <div
                                    class="absolute inset-x-5 top-0 border-t-2 border-dashed border-gray-200 dark:border-gray-700"
                                ></div>

                                <div class="flex flex-wrap items-center gap-2">
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 dark:bg-gray-700 px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300"
                                    >
                                        <CalendarIcon size="15" />
                                        {{ formatDate(ticket.created_at) }}
                                    </span>
                                    <span
                                        v-if="getEtaStatus(ticket)"
                                        class="inline-flex items-center gap-1.5 rounded-full bg-red-100 dark:bg-red-900/40 px-3 py-1.5 text-xs font-medium text-red-700 dark:text-red-400"
                                    >
                                        <ClockCircleIcon size="15" />
                                        {{ getEtaStatus(ticket) }}
                                    </span>
                                </div>

                                <div
                                    class="mt-1 flex items-center justify-between gap-2"
                                >
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-medium"
                                        :class="
                                            getPriorityClass(ticket.priority)
                                        "
                                    >
                                        <DangerTriangleIcon size="15" />
                                        Prioritas: {{ ticket.priority }}
                                    </span>
                                    <p
                                        class="mt-4 text-center font-mono text-xs tracking-wider text-gray-500 dark:text-gray-400"
                                    >
                                        {{ ticket.ticket_number }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </Link>
                </li>
            </ul>
        </main>
    </AdminLayout>
</template>

<script setup>
import { computed, ref } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import { ProgrammingIcon } from "@solar-icons/vue/bold/programming";
import { CalendarIcon } from "@solar-icons/vue/bold/calendar";
import { ClockCircleIcon } from "@solar-icons/vue/bold/clock-circle";
import { DangerTriangleIcon } from "@solar-icons/vue/bold/danger-triangle";
import AdminLayout from "../../Layouts/AdminLayout.vue";

const props = defineProps({
    tickets: Array,
});

const page = usePage();
const user = computed(() => page.props.auth.user);

const randomSeed = ref(Math.random().toString(36).substring(2, 10));
const avatarUrl = ref(
    `https://api.dicebear.com/10.x/sprouts/svg?tags=animation&seed=${randomSeed.value}`,
);

// Filter
const statusFilter = [
    { label: "Semua", value: "all" },
    { label: "Open", value: "open" },
    { label: "Di Proses", value: "in progress" },
    { label: "Selesai", value: "done" },
];
const activeFilter = ref("all");

const filteredTickets = computed(() => {
    if (activeFilter.value === "all") {
        return props.tickets;
    }
    return props.tickets.filter((t) => t.status === activeFilter.value);
});

// Warna
const getPriorityClass = (priority) => {
    switch (priority) {
        case "Low":
            return "bg-yellow-100 text-yellow-800 ";
        case "Medium":
            return "bg-amber-100 text-amber-800";
        case "High":
            return "bg-orange-100 text-orange-800 ";
        case "Critical":
            return "bg-red-100 text-red-800 ";
        default:
            return "bg-gray-100 text-gray-800";
    }
};

const getStatusClass = (status) => {
    switch (status) {
        case "accepted":
        case "done":
            return "bg-green-100 text-green-800 border border-green-300";
        case "in progress":
            return "bg-amber-100 text-amber-800 border border-amber-300";
        case "cancelled":
            return "bg-red-100 text-red-800 border border-red-300";
        default:
            return "bg-gray-100 text-gray-800 border border-gray-300";
    }
};

// Tanggal dan keterlambatan
const formatDate = (date) => {
    if (!date) return "-";
    return new Date(date).toLocaleDateString("id-ID", {
        day: "numeric",
        month: "short",
        year: "numeric",
    });
};

const getEtaStatus = (ticket) => {
    if (
        !ticket.eta ||
        ["resolved", "cancelled", "done"].includes(ticket.status)
    ) {
        return null;
    }

    const diffInHours = Math.floor(
        (new Date() - new Date(ticket.eta)) / (1000 * 60 * 60),
    );

    if (diffInHours < 1) return null;
    if (diffInHours >= 24)
        return `Lebih lambat ${Math.floor(diffInHours / 24)} hari`;
    return `Lebih lambat ${diffInHours} jam`;
};
</script>
<style scoped>
/* Bagian atas: lekukan di sudut kiri-bawah dan kanan-bawah */
.ticket-top {
    -webkit-mask:
        radial-gradient(circle 12px at 0 100%, #0000 98%, #000) left / 51% 100%
            no-repeat,
        radial-gradient(circle 12px at 100% 100%, #0000 98%, #000) right / 51%
            100% no-repeat;
    mask:
        radial-gradient(circle 12px at 0 100%, #0000 98%, #000) left / 51% 100%
            no-repeat,
        radial-gradient(circle 12px at 100% 100%, #0000 98%, #000) right / 51%
            100% no-repeat;
}

/* Bagian bawah: lekukan di sudut kiri-atas dan kanan-atas */
.ticket-bottom {
    -webkit-mask:
        radial-gradient(circle 12px at 0 0, #0000 98%, #000) left / 51% 100%
            no-repeat,
        radial-gradient(circle 12px at 100% 0, #0000 98%, #000) right / 51% 100%
            no-repeat;
    mask:
        radial-gradient(circle 12px at 0 0, #0000 98%, #000) left / 51% 100%
            no-repeat,
        radial-gradient(circle 12px at 100% 0, #0000 98%, #000) right / 51% 100%
            no-repeat;
}
</style>
