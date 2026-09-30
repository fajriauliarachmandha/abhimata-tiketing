<template>
    <UserLayout>
        <main class="py-2">
            <div class="flex items-center gap-2.5">
                <img
                    :src="avatarUrl"
                    alt="avatar"
                    id="avatar"
                    class="w-12 h-12 rounded-full cursor-pointer ring-2 p-1 ring-green-500"
                />
                <div class="flex flex-col">
                    <div
                        class="font-semibold text-lg text-heading leading-tight"
                    >
                        <div>Hallo, {{ user.name }}</div>
                        <div
                            class="text-sm font-normal text-body leading-tight"
                        >
                            {{
                                user.role === "department"
                                    ? user.department?.department_name
                                    : "Departemen IT Support"
                            }}
                        </div>
                        <span
                            class="inline-flex items-center gap-1 bg-brand-softer text-fg-brand-strong text-xs font-medium px-1.5 py-0.5 rounded"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="10"
                                height="10"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-star preview-icon shrink-0"
                            >
                                <path
                                    d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"
                                />
                            </svg>
                            17 Stars
                        </span>
                    </div>
                </div>
            </div>

            <div
                class="pt-6 pb-4 font-semibold text-lg text-heading leading-tight"
            >
                Riwayat Perbaikan
            </div>

            <ul role="list" class="max-w-md divide-y divide-default">
                <li
                    v-if="!tickets || tickets.length === 0"
                    class="py-6 text-center text-sm text-body"
                >
                    Belum ada riwayat perbaikan.
                </li>
                <Link
                    v-for="ticket in tickets"
                    :key="ticket.id"
                    :href="`/user/ticket/${ticket.id}`"
                    class=""
                >
                    <div
                        class="flex items-center space-x-3 rtl:space-x-reverse py-2"
                    >
                        <div class="flex-1 min-w-0 flex flex-col gap-1">
                            <p
                                class="text-sm font-semibold text-heading truncate"
                            >
                                {{ ticket.category }}
                            </p>
                            <span class="text-xs font-medium px-1.5 py-0.5">{{
                                ticket.description
                            }}</span>
                            <span
                                class="bg-warning-soft text-fg-danger text-xs font-medium px-1.5 py-0.5 rounded w-fit"
                                :class="getPriorityClass(ticket.priority)"
                                >Priortas: {{ ticket.priority }}</span
                            >
                        </div>
                        <span
                            class="inline-flex items-center border text-xs font-medium px-1.5 py-0.5 rounded-sm"
                            :class="getStatusClass(ticket.status)"
                        >
                            <span
                                class="w-2 h-2 me-1 rounded-full"
                                :class="getDotClass(ticket.status)"
                            ></span>
                            {{ ticket.status }}
                        </span>
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
const randomSeed = ref(Math.random().toString(36).substring(2, 10));
const avatarUrl = ref(
    `https://api.dicebear.com/10.x/loops/svg?tags=animation&seed=${randomSeed.value}`,
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
        case "in_progress":
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
        case "in_progress":
            return "bg-amber-500";
        case "cancelled":
            return "bg-red-500";
        default:
            return "bg-gray-400";
    }
};
</script>
