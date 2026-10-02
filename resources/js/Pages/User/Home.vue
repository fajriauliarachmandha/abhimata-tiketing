<template>
    <UserLayout>
        <main class="py-2">
            <div class="flex items-center gap-2.5 ps-0.5">
                <img
                    :src="avatarUrl"
                    alt="avatar"
                    id="avatar"
                    class="w-10 h-10 rounded-full cursor-pointer ring-2 ring-green-500"
                />

                <div class="flex flex-col">
                    <div class="leading-tight">
                        <div
                            class="font-bold text-sm text-heading leading-tight"
                        >
                            Hallo, {{ user.name }}
                        </div>
                        <div
                            class="text-xs font-normal font-secondary text-body leading-tight"
                        >
                            {{
                                user.role === "department"
                                    ? user.department?.department_name
                                    : "Departemen IT Support"
                            }}
                        </div>
                        <span
                            class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-800 text-xs font-medium px-1.5 py-0.5 rounded-full font-secondary"
                        >
                            <StarIcon size="12" />
                            17 Stars
                        </span>
                    </div>
                </div>
            </div>

            <div class="sticky top-0 z-10 bg-white flex gap-2 py-2 -mx-4 px-4">
                <button
                    v-for="status in statusFilter"
                    :key="status.value"
                    type="button"
                    @click="activeFilter = status.value"
                    class="px-2 py-2 font-light text-xs font-secondary rounded-full transition-all duration-200 ease-in-out active:scale-95"
                    :class="
                        activeFilter === status.value
                            ? 'bg-dark text-white'
                            : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                    "
                >
                    {{ status.label }}
                </button>
            </div>

            <div
                class="pt-4 pb-4 font-semibold text-lg text-heading leading-tight"
            >
                Daftar Order
            </div>

            <ul role="list" class="max-w-md divide-y divide-default">
                <li
                    v-if="!tickets || tickets.length === 0"
                    class="py-6 text-center text-sm text-body"
                >
                    Belum ada perbaikan.
                </li>
                <Link
                    v-for="ticket in filteredTickets"
                    :key="ticket.id"
                    :href="`/user/ticket/${ticket.id}`"
                    class=""
                >
                    <div
                        class="flex flex-col gap-3 bg-gray-200 rounded-base border border-primary-200 px-4 py-4 my-1"
                    >
                        <div class="items-start">
                            <div class="flex justify-between">
                                <p
                                    class="text-sm font-semibold text-heading truncate"
                                >
                                    Kategori: {{ ticket.category }}
                                </p>
                                <span
                                    class="inline-flex items-center border text-xs font-medium px-1.5 py-0.5 rounded-full shrink-0"
                                    :class="getStatusClass(ticket.status)"
                                >
                                    <span
                                        class="w-2 h-2 me-1 rounded-full animate-pulse"
                                        :class="getDotClass(ticket.status)"
                                    ></span>
                                    {{ ticket.status }}
                                </span>
                            </div>

                            <div class="flex-1 min-w-0 flex flex-col gap-1">
                                <span
                                    class="text-xs font-medium px-1.5 py-0.5"
                                    >{{ ticket.description }}</span
                                >
                                <span
                                    class="bg-warning-soft text-fg-danger text-xs font-medium px-1.5 py-0.5 rounded-full w-fit"
                                    :class="getPriorityClass(ticket.priority)"
                                    >Priortas: {{ ticket.priority }}</span
                                >
                                <span
                                    v-if="getEtaStatus(ticket)"
                                    class="bg-warning-soft text-fg-danger text-xs font-medium px-1.5 py-0.5 rounded-full w-fit"
                                >
                                    {{ getEtaStatus(ticket) }}
                                </span>
                            </div>
                        </div>

                        <div
                            class="w-full py-1 pr-1 pl-4 flex justify-between items-center bg-white rounded-full"
                        >
                            <span class="text-xs font-medium text-gray-700">
                                Teknisi:
                                {{
                                    ticket.technician
                                        ? ticket.technician.name
                                        : "Belum ada teknisi"
                                }}
                                <br />
                                {{ ticket.ticket_number }}
                            </span>

                            <button
                                type="button"
                                class="w-fit text-white bg-primary-500 box-border border border-transparent hover:bg-primary-600 focus:ring-4 focus:ring-primary-600 shadow-xs font-medium leading-5 rounded-full text-sm px-4 py-1.5 focus:outline-none transition-all duration-200 ease-in-out active:scale-95"
                            >
                                Proses
                            </button>
                        </div>
                    </div>
                </Link>
            </ul>
        </main>
    </UserLayout>
</template>

<script setup>
import { computed, ref } from "vue";
import UserLayout from "../../Layouts/UserLayout.vue";
import { Link, usePage } from "@inertiajs/vue3";
import { StarIcon } from "@solar-icons/vue/bold/star";
const randomSeed = ref(Math.random().toString(36).substring(2, 10));
const avatarUrl = ref(
    `https://api.dicebear.com/10.x/sprouts/svg?tags=animation&seed=${randomSeed.value}`,
);
const page = usePage();
const user = computed(() => page.props.auth.user);
const props = defineProps({
    tickets: Array,
});
const getPriorityClass = (priority) => {
    switch (priority) {
        case "Low":
            return "bg-yellow-100 text-yellow-800 border border-yellow-300";
        case "Medium":
            return "bg-amber-100 text-amber-800 border border-amber-300";
        case "High":
            return "bg-orange-100 text-orange-800 border border-orange-300";
        case "Critical":
            return "bg-red-100 text-red-800 border border-red-300";
        default:
            return "bg-gray-100 text-gray-800";
    }
};
const getStatusClass = (status) => {
    switch (status) {
        case "open":
            return "bg-gray-100 text-gray-800 border border-gray-300";
        case "accepted":
            return "bg-green-100 text-green-800 border border-green-300";
        case "in progress":
            return "bg-amber-100 text-amber-800 border border-amber-300";
        case "done":
            return "bg-green-100 text-green-800 border border-green-300";
        case "cancelled":
            return "bg-red-100 text-red-800 border border-red-300";
    }
};
const getDotClass = (status) => {
    switch (status) {
        case "open":
            return "bg-gray-400";
        case "accepted":
        case "done":
            return "bg-green-500";
        case "in progress":
            return "bg-amber-500";
        case "cancelled":
            return "bg-red-500";
        default:
            return "bg-gray-400";
    }
};

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
    return props.tickets.filter(
        (ticket) => ticket.status === activeFilter.value,
    );
});

const getEtaStatus = (ticket) => {
    if (
        !ticket.eta ||
        ticket.status === "resolved" ||
        ticket.status === "cancelled" ||
        ticket.status === "done"
    ) {
        return null;
    }

    const eta = new Date(ticket.eta);
    const now = new Date();
    const diffInMs = now - eta;
    const diffInDays = Math.floor(diffInMs / (1000 * 60 * 60 * 24));

    if (diffInDays < 1) {
        return null;
    }
    return `lebih lambat ${diffInDays} jam`;
};
</script>
