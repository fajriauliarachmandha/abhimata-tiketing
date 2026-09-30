<template>
    <AdminLayout>
        <main class="py-2 pb-32">
            <h1
                class="text-base font-bold tracking-wide text-gray-900 uppercase"
            >
                DETAIL TIKET
            </h1>

            <form @submit.prevent="submit" class="max-w-md mx-auto my-7 px-4">
                <div class="mb-3">
                    <label
                        for="departement"
                        class="block mb-2.5 text-sm font-medium text-heading"
                        >Departement</label
                    >
                    <span class="font-medium px-1.5 py-0.5">{{
                        user.department.department_name
                    }}</span>
                </div>
                <div class="mb-3">
                    <label
                        for="departement"
                        class="block mb-2.5 text-sm font-medium text-heading"
                        >Kategori Kendala</label
                    >
                    <span class="font-medium px-1.5 py-0.5">{{
                        ticket.category
                    }}</span>
                </div>
                <div class="mb-3">
                    <label
                        for="departement"
                        class="block mb-2.5 text-sm font-medium text-heading"
                        >Tingkat Prioritas</label
                    >
                    <span
                        class="font-medium px-1.5 py-0.5 w-fit"
                        :class="getPriorityClass(ticket.priority)"
                        >{{ ticket.priority }}</span
                    >
                </div>

                <div class="mb-3">
                    <!-- Label -->
                    <label
                        class="block mb-2.5 text-sm font-medium text-heading"
                    >
                        Foto
                    </label>

                    <div
                        class="relative w-full h-32 bg-gray-200 border-2 border-dashed border-gray-400 rounded-lg flex flex-col items-center justify-center cursor-pointer hover:bg-gray-300 transition-colors overflow-hidden"
                    >
                        <img
                            v-if="ticket.photo"
                            :src="`/storage/${ticket.photo}`"
                            class="w-full h-full object-contain"
                            alt="Preview Foto"
                        />
                    </div>
                </div>

                <div class="mb-3">
                    <label
                        for="departement"
                        class="block mb-2.5 text-sm font-medium text-heading"
                        >Deskripsi</label
                    >
                    <span class="font-medium px-1.5 py-0.5">{{
                        ticket.description
                    }}</span>
                </div>

                <button
                    v-if="
                        ticket.status !== 'cancelled' &&
                        ticket.status !== 'closed' &&
                        ticket.status !== 'in progress'
                    "
                    type="button"
                    @click="processTicket"
                    class="text-white bg-green-600 box-border border border-transparent focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-full text-sm px-4 py-4 focus:outline-none my-4 w-full transition-all duration-200 ease-in-out active:scale-95 cursor-pointer"
                >
                    Proses Tiket
                </button>

                <div
                    v-else-if="ticket.status === 'in progress'"
                    class="text-center py-3 text-sm font-medium text-green-600 bg-green-50 border border-green-200 rounded-lg my-4"
                >
                    Tiket ini sedang diproses.
                </div>
            </form>
        </main></AdminLayout
    >
</template>

<script setup>
import { ref, computed } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import AdminLayout from "../../Layouts/AdminLayout.vue";

const form = useForm({
    category: "",
    priority: "Low",
    photo: null,
    description: "",
});

const submit = () => {
    form.post("/admin/ticket", {
        forceFormData: true,
        onSuccess: () => {
            form.reset();
            previewUrl.value = null;
        },
    });
};
const props = defineProps({
    ticket: Object,
});
const page = usePage();
const user = computed(() => page.props.auth.user);
const getPriorityClass = (priority) => {
    switch (priority) {
        case "Low":
            return "bg-yellow-100 text-yellow-800";
        case "Medium":
            return "bg-amber-100 text-amber-800";
        case "High":
            return "bg-orange-100 text-orange-800";
        case "Critical":
            return "bg-red-100 text-red-800";
        default:
            return "bg-gray-100 text-gray-800";
    }
};

const isDrawerOpen = ref(false);

const processTicket = () => {
    form.put(`/admin/ticket/${props.ticket.id}`, {
        onSuccess: () => {
            isDrawerOpen.value = false;
            window.location.href = "/admin/home";
        },
    });
};
</script>
