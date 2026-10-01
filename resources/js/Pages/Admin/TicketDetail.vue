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
                <span class="text-xs font-medium px-1.5 py-0.5"
                    >Teknisi :
                    {{
                        ticket.technician
                            ? ticket.technician.name
                            : "Belum ada teknisi yang ditugaskan"
                    }}</span
                >

                <button
                    v-if="
                        ticket.status !== 'cancelled' &&
                        ticket.status !== 'closed' &&
                        ticket.status !== 'in progress'
                    "
                    type="button"
                    @click="isDrawerOpen = true"
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

                <Transition
                    enter-active-class="transition ease-out duration-200"
                    enter-from-class="opacity-0 scale-95"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-150"
                    leave-from-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-95"
                >
                    <div
                        v-if="isDrawerOpen"
                        id="drawer-bottom-example"
                        class="fixed bottom-0 inset-x-0 mx-auto z-[100] w-full max-w-md px-4 pb-4 pt-3 overflow-y-auto transition-transform bg-neutral-primary-soft transform-none rounded-t-2xl shadow-[0_-10px_40px_rgba(0,0,0,0.1)] border-t border-default"
                        tabindex="-1"
                        aria-labelledby="drawer-bottom-label"
                    >
                        <div class="flex justify-between pb-2 items-center">
                            <h3 class="text-lg font-medium text-heading">
                                Pilih Teknisi untuk memproses tiket ini
                            </h3>
                        </div>

                        <div class="grid gap-4 py-2">
                            <label
                                v-for="teknisi in technician"
                                :key="teknisi.id"
                                class="cursor-pointer"
                            >
                                <input
                                    type="radio"
                                    name="teknisi"
                                    :value="teknisi.id"
                                    v-model="form.assigned_to"
                                    class="sr-only peer"
                                    @change="errorMessage = ''"
                                />
                                <div
                                    class="px-4 py-4 bg-gray-100 text-gray-700 text-sm rounded-full border border-gray-200 transition-all duration-200 ease-in-out active:scale-95 peer-checked:bg-dark peer-checked:text-white peer-checked:border-dark peer-checked:shadow-md peer-checked:scale-[1.02]"
                                >
                                    {{ teknisi.name }}
                                </div>
                            </label>
                        </div>
                        <div
                            v-if="errorMessage"
                            class="text-red-500 text-sm mb-2"
                        >
                            {{ errorMessage }}
                        </div>
                        <div
                            class="flex justify-end items-center space-x-3 pt-4 mt-2"
                        >
                            <button
                                type="button"
                                @click="processTicket"
                                :disabled="form.processing"
                                class="inline-flex items-center text-white bg-green-600 hover:bg-green-700 font-medium rounded-full text-sm px-5 py-2.5 transition-all duration-200 cursor-pointer"
                            >
                                {{
                                    form.processing
                                        ? "Memproses..."
                                        : "Ya, Proses"
                                }}
                            </button>
                            <button
                                type="button"
                                @click="isDrawerOpen = false"
                                class="text-gray-700 bg-gray-100 hover:bg-gray-200 font-medium rounded-full text-sm px-5 py-2.5 transition-all duration-200 cursor-pointer"
                            >
                                Tidak
                            </button>
                        </div>
                    </div>
                </Transition>
            </form>
        </main></AdminLayout
    >
</template>

<script setup>
import { ref, computed } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import AdminLayout from "../../Layouts/AdminLayout.vue";

const form = useForm({
    status: "in progress",
    assigned_to: "",
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
    technician: Array,
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
const errorMessage = ref("");

const processTicket = () => {
    if (!form.assigned_to) {
        errorMessage.value("Silakan pilih teknisi sebelum memproses tiket.");
        return;
    }
    form.put(`/admin/ticket/${props.ticket.id}`, {
        onSuccess: () => {
            isDrawerOpen.value = false;
            router.visit("/admin/home");
        },
    });
};
</script>
