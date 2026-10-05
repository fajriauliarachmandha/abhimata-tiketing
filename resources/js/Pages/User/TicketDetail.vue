<template>
    <NavUser />
    <main class="min-h-screen bg-gray-100 px-4 pt-20 pb-6 lg:ms-64 lg:pt-24">
        <h1
            class="text-center text-base font-bold tracking-wide text-gray-900 uppercase"
        >
            Detail Tiket
        </h1>
        <div
            class="mx-auto mt-7 grid max-w-md grid-cols-1 items-start gap-6 lg:max-w-5xl lg:grid-cols-2"
        >
            <!-- Tiket -->
            <div class="drop-shadow-lg">
                <!-- Bagian atas -->
                <div class="ticket-top rounded-t-2xl bg-white p-5 pb-6">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0 leading-tight">
                            <p class="text-[11px] text-gray-400">Departemen</p>
                            <p
                                class="text-sm font-semibold text-gray-900 truncate"
                            >
                                {{
                                    ticket.department?.department_name ??
                                    user.department?.department_name ??
                                    "-"
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
                        class="mt-4 text-sm text-gray-900 break-words whitespace-pre-line"
                    >
                        {{ ticket.description }}
                    </p>

                    <img
                        v-if="ticket.photo"
                        :src="`/storage/${ticket.photo}`"
                        alt="Foto kendala"
                        class="mt-4 mx-auto block h-auto max-h-96 max-w-full rounded-lg"
                    />

                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0 leading-tight">
                            <p class="text-[11px] text-gray-400">Teknisi</p>
                            <p
                                class="text-sm font-semibold text-gray-900 truncate"
                            >
                                {{
                                    ticket.technician
                                        ? ticket.technician.name
                                        : "Belum ada teknisi"
                                }}
                            </p>
                        </div>
                        <div class="min-w-0 leading-tight">
                            <p class="text-[11px] text-gray-400">Kategori</p>
                            <p
                                class="text-sm font-semibold text-gray-900 truncate"
                            >
                                {{ ticket.category }}
                            </p>
                        </div>
                        <div class="min-w-0 leading-tight">
                            <p class="text-[11px] text-gray-400">Prioritas</p>
                            <span
                                class="mt-0.5 inline-block text-xs font-medium px-2 py-0.5 rounded-full"
                                :class="getPriorityClass(ticket.priority)"
                            >
                                {{ ticket.priority }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Bagian bawah -->
                <div
                    class="ticket-bottom relative rounded-b-2xl bg-white p-5 pt-6"
                >
                    <!-- garis sobekan -->
                    <div
                        class="absolute inset-x-5 top-0 border-t-2 border-dashed border-gray-200"
                    ></div>

                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0 leading-tight">
                            <p class="text-[11px] text-gray-400">Dibuat</p>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ formatDate(ticket.created_at) }}
                            </p>
                        </div>

                        <span
                            v-if="getEtaStatus(ticket)"
                            class="mt-3 inline-block text-xs font-medium text-red-600 bg-red-50 px-2 py-0.5 rounded-full"
                        >
                            {{ getEtaStatus(ticket) }}
                        </span>
                    </div>

                    <p
                        class="mt-4 text-center font-mono text-xs tracking-wider text-gray-500"
                    >
                        {{ ticket.ticket_number }}
                    </p>
                    <!-- Tombol batalkan -->
                    <button
                        v-if="canCancel"
                        type="button"
                        @click="isCancelOpen = true"
                        class="w-full my-4 px-4 py-3 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-full focus:outline-none focus:ring-4 focus:ring-red-200 transition active:scale-95"
                    >
                        Batalkan Tiket
                    </button>

                    <p
                        v-else-if="statusMessage"
                        class="my-4 rounded-lg border py-3 text-center text-sm font-medium"
                        :class="
                            ticket.status === 'cancelled'
                                ? 'border-red-200 bg-red-50 text-red-600'
                                : 'border-gray-200 bg-gray-50 text-gray-500'
                        "
                    >
                        {{ statusMessage }}
                    </p>
                </div>
            </div>

            <!-- Timeline -->
            <ol class="relative border-s border-default">
                <li class="mb-7 ms-6">
                    <span
                        class="absolute flex items-center justify-center w-5 h-5 bg-amber-200 rounded-full -start-3"
                    >
                    </span>
                    <time
                        class="bg-neutral-secondary-medium border border-default-medium text-heading text-xs font-medium px-1.5 py-0.5 rounded"
                        >March 13th, 2025</time
                    >
                    <p class="mb-4 text-body text-sm pt-1">
                        Get access to over 20+ pages including a dashboard
                        layout, charts, kanban board, calendar, and pre-order
                        E-commerce & Marketing pages.
                    </p>
                </li>
                <button
                    type="button"
                    @click="openAddModal"
                    class="inline-flex items-center justify-center gap-1.5 h-10 px-4 text-sm font-semibold text-white bg-primary-500 hover:bg-primary-600 border border-transparent rounded-sm shadow-xs focus:outline-none focus:ring-4 focus:ring-brand-medium active:scale-95 transition duration-200"
                >
                    <AddIcon size="24" />
                    <span>Pelacakan</span>
                </button>
            </ol>
        </div>

        <!-- Modal konfirmasi batalkan -->
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="isCancelOpen"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50"
                role="alertdialog"
                aria-modal="true"
                aria-labelledby="cancel-title"
                @click.self="closeCancel"
            >
                <div
                    class="w-full max-w-sm bg-white rounded-2xl shadow-lg p-5 text-center"
                >
                    <h3
                        id="cancel-title"
                        class="text-lg font-semibold text-heading"
                    >
                        Batalkan tiket ini?
                    </h3>
                    <p class="mt-2 text-sm text-body">
                        Tiket
                        <span class="font-semibold text-heading">
                            {{ ticket.ticket_number }}
                        </span>
                        akan dibatalkan dan tidak bisa dikembalikan.
                    </p>

                    <div class="mt-6 flex gap-3">
                        <button
                            type="button"
                            @click="closeCancel"
                            :disabled="cancelForm.processing"
                            class="flex-1 h-10 px-4 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-full transition active:scale-95 disabled:opacity-60"
                        >
                            Tidak
                        </button>
                        <button
                            type="button"
                            @click="cancelTicket"
                            :disabled="cancelForm.processing"
                            class="flex-1 h-10 px-4 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-full transition active:scale-95 disabled:opacity-60"
                        >
                            {{
                                cancelForm.processing
                                    ? "Memproses..."
                                    : "Ya, Batalkan"
                            }}
                        </button>
                    </div>
                </div>
            </div>
        </Transition>

        <!-- Modal Tambah/Edit tracking -->
        <!-- Modal tambah/edit -->
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="isModalOpen"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50"
                role="dialog"
                aria-modal="true"
                aria-labelledby="modal-title"
                @click.self="closeModal"
            >
                <div
                    class="relative w-full max-w-md bg-neutral-primary-soft rounded-sm border border-default shadow-lg p-5"
                >
                    <div class="flex justify-between items-center pb-2">
                        <h3
                            id="modal-title"
                            class="text-lg font-medium text-heading"
                        >
                            {{
                                editingId
                                    ? "Edit Departemen"
                                    : "Tambah Departemen"
                            }}
                        </h3>
                        <button
                            type="button"
                            @click="closeModal"
                            class="text-body bg-transparent hover:text-heading hover:bg-neutral-tertiary rounded-sm w-9 h-9 flex items-center justify-center ms-auto transition-all duration-200 ease-in-out active:scale-95"
                        >
                            <CloseIcon size="24" />
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>

                    <form @submit.prevent="handleTrackingSubmit">
                        <div class="grid gap-4 grid-cols-2 py-2">
                            <div class="col-span-2">
                                <label
                                    for="kode"
                                    class="block mb-2.5 text-sm font-medium text-heading"
                                >
                                    Status
                                </label>
                                <input
                                    type="text"
                                    id="kode"
                                    v-model="form.department_code"
                                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-sm focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                                    required
                                />
                                <span
                                    v-if="form.errors.department_code"
                                    class="text-xs text-red-500 mt-1 block"
                                >
                                    {{ form.errors.department_code }}
                                </span>
                            </div>
                            <div class="col-span-2">
                                <label
                                    for="nama"
                                    class="block mb-2.5 text-sm font-medium text-heading"
                                >
                                    Keterangan
                                </label>
                                <input
                                    type="text"
                                    id="nama"
                                    v-model="form.department_name"
                                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-sm focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                                    required
                                />
                                <span
                                    v-if="form.errors.department_name"
                                    class="text-xs text-red-500 mt-1 block"
                                >
                                    {{ form.errors.department_name }}
                                </span>
                            </div>
                        </div>

                        <div
                            class="flex justify-end items-center gap-3 border-default pt-4 mt-2"
                        >
                            <button
                                type="button"
                                @click="closeModal"
                                class="text-body bg-neutral-secondary-medium border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading shadow-xs font-medium leading-5 rounded-full text-sm px-4 py-3 focus:outline-none transition-all duration-200 ease-in-out active:scale-95"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="inline-flex items-center text-white bg-primary-500 hover:bg-primary-600 border border-transparent shadow-xs font-medium leading-5 rounded-full text-sm px-4 py-3 focus:outline-none transition-all duration-200 ease-in-out active:scale-95 disabled:opacity-60"
                            >
                                {{
                                    form.processing
                                        ? editingId
                                            ? "Updating..."
                                            : "Saving..."
                                        : editingId
                                          ? "Update"
                                          : "Simpan"
                                }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Transition>
    </main>
</template>

<script setup>
import { ref, computed } from "vue";
import { useForm, usePage, router } from "@inertiajs/vue3";
import NavUser from "../../Layouts/NavUser.vue";

const props = defineProps({
    ticket: Object,
});

const page = usePage();
const user = computed(() => page.props.auth.user);

// Batalkan tiket
const isCancelOpen = ref(false);
const cancelForm = useForm({});

const canCancel = computed(() => ["open"].includes(props.ticket.status));
const statusMessage = computed(() => {
    switch (props.ticket.status) {
        case "cancelled":
            return "Tiket ini sudah dibatalkan.";
        case "accepted":
            return "Tiket sudah diterima teknisi dan tidak bisa dibatalkan.";
        case "in progress":
            return "Tiket sedang dikerjakan teknisi dan tidak bisa dibatalkan.";
        case "done":
            return "Tiket ini sudah selesai.";
        default:
            return "";
    }
});
const closeCancel = () => {
    if (!cancelForm.processing) isCancelOpen.value = false;
};

const cancelTicket = () => {
    cancelForm.put(`/user/ticket/${props.ticket.id}`, {
        onSuccess: () => {
            isCancelOpen.value = false;
            router.visit("/user/home");
        },
    });
};

// Warna badge
const priorityClasses = {
    low: "bg-sky-100 text-sky-800 border border-sky-300",
    medium: "bg-amber-200 text-amber-900 border border-amber-400",
    high: "bg-orange-200 text-orange-900 border border-orange-400",
    critical: "bg-red-200 text-red-900 border border-red-400",
};

const getPriorityClass = (priority) => {
    const key = String(priority ?? "")
        .trim()
        .toLowerCase();
    return (
        priorityClasses[key] ??
        "bg-gray-100 text-gray-800 border border-gray-300"
    );
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
