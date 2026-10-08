<template>
    <AdminLayout>
        <main>
            <h1
                class="text-center text-base font-bold tracking-wide text-gray-900 uppercase"
            >
                Detail Tiket
            </h1>

            <!-- Laptop: 2 kolom (kiri = tiket, kanan = pelacakan). HP: menumpuk -->
            <div
                class="mx-auto mt-7 grid max-w-md grid-cols-1 items-start gap-6 lg:max-w-5xl lg:grid-cols-2"
            >
                <!-- ==================== KOLOM KIRI: TIKET ==================== -->
                <div class="drop-shadow-lg">
                    <!-- Bagian atas tiket -->
                    <div
                        class="ticket-top rounded-t-2xl bg-white dark:bg-gray-800 p-5 pb-6"
                    >
                        <!-- Departemen + status -->
                        <div class="flex items-start justify-between gap-3">
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

                        <!-- Deskripsi + foto -->
                        <p
                            class="mt-4 text-sm text-gray-900 dark:text-gray-200 break-words whitespace-pre-line"
                        >
                            {{ ticket.description }}
                        </p>

                        <img
                            v-if="ticket.photo"
                            :src="`/storage/${ticket.photo}`"
                            alt="Foto kendala"
                            class="mt-4 mx-auto block h-auto max-h-96 max-w-full rounded-lg"
                        />

                        <!-- Teknisi, kategori, prioritas -->
                        <div
                            class="mt-4 flex items-start justify-between gap-3"
                        >
                            <div class="min-w-0 leading-tight">
                                <p
                                    class="text-[11px] text-gray-400 dark:text-gray-500"
                                >
                                    Teknisi
                                </p>
                                <p
                                    class="text-sm font-semibold text-gray-900 dark:text-white truncate"
                                >
                                    {{
                                        ticket.technician
                                            ? ticket.technician.name
                                            : "Belum ada teknisi"
                                    }}
                                </p>
                            </div>
                            <div class="min-w-0 leading-tight">
                                <p
                                    class="text-[11px] text-gray-400 dark:text-gray-500"
                                >
                                    Kategori
                                </p>
                                <p
                                    class="text-sm font-semibold text-gray-900 dark:text-white truncate"
                                >
                                    {{ ticket.category }}
                                </p>
                            </div>
                            <div class="min-w-0 leading-tight">
                                <p
                                    class="text-[11px] text-gray-400 dark:text-gray-500"
                                >
                                    Prioritas
                                </p>
                                <span
                                    class="mt-0.5 inline-block text-xs font-medium px-2 py-0.5 rounded-full"
                                    :class="getPriorityClass(ticket.priority)"
                                >
                                    {{ ticket.priority }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian bawah tiket -->
                    <div
                        class="ticket-bottom relative rounded-b-2xl bg-white dark:bg-gray-800 p-5 pt-6"
                    >
                        <!-- Garis sobekan -->
                        <div
                            class="absolute inset-x-5 top-0 border-t-2 border-dashed border-gray-200 dark:border-gray-700"
                        ></div>

                        <!-- Tanggal dibuat + keterlambatan -->
                        <div class="flex items-center justify-between gap-3">
                            <div class="min-w-0 leading-tight">
                                <p
                                    class="text-[11px] text-gray-400 dark:text-gray-500"
                                >
                                    Dibuat
                                </p>
                                <p
                                    class="text-sm font-semibold text-gray-900 dark:text-white"
                                >
                                    {{ formatDate(ticket.created_at) }}
                                </p>
                            </div>

                            <span
                                v-if="getEtaStatus(ticket)"
                                class="inline-block text-xs font-medium text-red-600 bg-red-50 dark:bg-red-900/40 dark:text-red-400 px-2 py-0.5 rounded-full"
                            >
                                {{ getEtaStatus(ticket) }}
                            </span>
                        </div>

                        <!-- Nomor tiket -->
                        <!-- Nomor tiket -->
                        <p
                            class="mt-4 text-center font-mono text-xs tracking-wider text-gray-500 dark:text-gray-400"
                        >
                            {{ ticket.ticket_number }}
                        </p>

                        <!-- Tombol proses tiket -->
                        <button
                            v-if="canProcess"
                            type="button"
                            @click="isTechnicianModalOpen = true"
                            class="w-full my-4 px-4 py-3 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-full focus:outline-none focus:ring-4 focus:ring-green-200 transition active:scale-95"
                        >
                            Proses Tiket
                        </button>

                        <div
                            v-else-if="ticket.status === 'in progress'"
                            class="text-center py-3 text-sm font-medium text-green-600 bg-green-50 border border-green-200 rounded-lg my-4"
                        >
                            Tiket ini sedang diproses.
                        </div>
                        <div
                            v-else-if="ticket.status === 'done'"
                            class="text-center py-3 text-sm font-medium text-gray-500 bg-gray-50 border border-gray-200 rounded-lg my-4"
                        >
                            Tiket ini sudah selesai.
                        </div>
                    </div>
                </div>

                <!-- ==================== KOLOM KANAN: PELACAKAN ==================== -->
                <div>
                    <!-- Daftar riwayat pelacakan -->
                    <div v-if="trackings.length > 0" class="mb-6 ms-2 mt-2">
                        <div
                            v-for="(track, index) in trackings"
                            :key="track.id"
                            class="flex items-stretch gap-3"
                        >
                            <!-- Kiri: hari dan jam -->
                            <div class="w-16 shrink-0 pt-0.5 text-right">
                                <p class="text-sm font-medium text-gray-700">
                                    {{ getRelativeDay(track.created_at) }}
                                </p>
                                <p class="text-[11px] text-gray-500">
                                    {{ formatTime(track.created_at) }}
                                </p>
                            </div>

                            <!-- Tengah: titik dan garis penghubung -->
                            <div class="flex flex-col items-center">
                                <div
                                    class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-emerald-700"
                                ></div>
                                <!-- Garis tidak ditampilkan di catatan paling akhir -->
                                <div
                                    v-if="index < trackings.length - 1"
                                    class="mt-1 w-px flex-1 bg-gray-300"
                                ></div>
                            </div>

                            <!-- Kanan: catatan + penulisnya -->
                            <div class="flex-1 pb-6 pt-0.5">
                                <p class="text-sm font-medium text-emerald-700">
                                    {{ track.note }}
                                </p>
                                <p class="mt-1 text-[10px] text-gray-400">
                                    Oleh: {{ track.user?.name ?? "Sistem" }}
                                </p>
                            </div>

                            <!-- Tombol edit dan hapus -->
                            <div class="flex shrink-0 items-start gap-2">
                                <button
                                    type="button"
                                    v-if="canTracking"
                                    @click="openEditTrackingModal(track)"
                                    class="font-medium text-fg-brand transition-all duration-200 ease-in-out active:scale-95 bg-blue-50 hover:bg-blue-100 rounded-full p-2"
                                >
                                    <PenIcon size="24" />
                                </button>
                                <button
                                    type="button"
                                    v-if="canTracking"
                                    @click="openDeleteModal(track)"
                                    class="font-medium text-fg-danger transition-all duration-200 ease-in-out active:scale-95 bg-red-50 hover:bg-red-100 rounded-full p-2"
                                >
                                    <TrashBinTrashIcon size="24" />
                                </button>
                            </div>
                        </div>
                    </div>

                    <p v-else class="mb-6 ms-2 text-sm italic text-gray-500">
                        Belum ada riwayat pelacakan.
                    </p>

                    <!-- Tombol tambah pelacakan -->
                    <button
                        v-if="canTracking"
                        type="button"
                        @click="openAddTrackingModal"
                        class="ms-4 inline-flex w-full items-center justify-center gap-1.5 h-10 px-4 text-sm font-semibold text-white bg-primary-500 hover:bg-primary-600 border border-transparent rounded-sm shadow-xs focus:outline-none focus:ring-4 focus:ring-brand-medium active:scale-95 transition duration-200"
                    >
                        <AddIcon size="24" />
                        <span>Pelacakan</span>
                    </button>

                    <div
                        v-else-if="
                            ticket.status !== 'done' &&
                            ticket.status !== 'cancelled'
                        "
                        class="text-center py-3 text-sm font-medium text-green-600 bg-green-50 border border-green-200 rounded-lg my-4"
                    >
                        {{
                            ticket.assigned_to
                                ? "Hanya teknisi yang ditugaskan yang bisa menambah pelacakan."
                                : "Pilih teknisi terlebih dahulu agar bisa menambah pelacakan."
                        }}
                    </div>

                    <!-- Tombol menyelesaikan ticket -->
                    <button
                        v-if="canDone"
                        type="button"
                        @click="isDoneModalOpen = true"
                        class="ms-4 inline-flex w-full items-center justify-center gap-1.5 h-10 px-4 text-sm font-semibold text-white bg-red-500 hover:bg-red-600 border border-transparent rounded-sm shadow-xs focus:outline-none focus:ring-4 focus:ring-brand-medium active:scale-95 transition duration-200 mt-3"
                    >
                        <CloudCheckIcon size="24" />
                        <span>Tandain Telah Selesai</span>
                    </button>

                    <!-- Penilaian dari pengaju (hanya tampil untuk tiket selesai) -->
                    <div v-if="ticket.status === 'done'" class="mb-5 ms-2 mt-4">
                        <p class="mb-2.5 text-sm font-medium text-gray-700">
                            Penilaian Tiket
                        </p>

                        <div v-if="ticket.review">
                            <div class="flex items-center space-x-1">
                                <StarIcon
                                    v-for="star in 5"
                                    :key="star"
                                    size="24"
                                    :class="
                                        star <= ticket.review.rating
                                            ? 'text-yellow-400'
                                            : 'text-gray-300'
                                    "
                                />
                            </div>

                            <div
                                v-if="ticket.review.tags?.length"
                                class="mt-3 flex flex-wrap gap-2"
                            >
                                <span
                                    v-for="tag in ticket.review.tags"
                                    :key="tag"
                                    class="rounded-full border border-gray-300 bg-gray-50 px-3 py-1 text-xs text-gray-700"
                                >
                                    {{ tag }}
                                </span>
                            </div>
                        </div>

                        <p v-else class="text-sm italic text-gray-500">
                            Belum ada penilaian dari pengaju.
                        </p>
                    </div>
                </div>
            </div>

            <!-- ==================== MODAL ==================== -->

            <!-- Modal 1: pilih teknisi untuk memproses tiket -->
            <Transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="isTechnicianModalOpen"
                    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="technician-title"
                    @click.self="closeTechnicianModal"
                >
                    <div
                        class="w-full max-w-sm max-h-[90vh] flex flex-col bg-white rounded-2xl shadow-lg p-5"
                    >
                        <h3
                            id="technician-title"
                            class="text-lg font-semibold text-heading"
                        >
                            Pilih teknisi
                        </h3>
                        <p class="mt-1 text-sm text-body">
                            Pilih teknisi yang akan memproses tiket ini.
                        </p>

                        <!-- Daftar teknisi: bisa di-scroll kalau banyak -->
                        <div class="mt-4 grid gap-2 overflow-y-auto">
                            <label
                                v-for="tech in technician"
                                :key="tech.id"
                                class="cursor-pointer"
                            >
                                <input
                                    type="radio"
                                    name="technician"
                                    :value="tech.id"
                                    v-model="processForm.assigned_to"
                                    class="sr-only peer"
                                    @change="technicianError = ''"
                                />
                                <div
                                    class="px-4 py-3 bg-gray-100 text-gray-700 text-sm rounded-full border border-gray-200 transition-all duration-200 ease-in-out active:scale-95 peer-checked:bg-dark peer-checked:text-white peer-checked:border-dark peer-checked:shadow-md peer-focus-visible:ring-2 peer-focus-visible:ring-brand"
                                >
                                    {{ tech.name }}
                                </div>
                            </label>
                        </div>

                        <p
                            v-if="technicianError"
                            class="mt-3 text-sm text-red-500"
                        >
                            {{ technicianError }}
                        </p>

                        <div class="mt-6 flex gap-3">
                            <button
                                type="button"
                                @click="closeTechnicianModal"
                                :disabled="processForm.processing"
                                class="flex-1 h-10 px-4 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-full transition active:scale-95 disabled:opacity-60"
                            >
                                Tidak
                            </button>
                            <button
                                type="button"
                                @click="processTicket"
                                :disabled="processForm.processing"
                                class="flex-1 h-10 px-4 text-sm font-semibold text-white bg-green-600 hover:bg-green-700 rounded-full transition active:scale-95 disabled:opacity-60"
                            >
                                {{
                                    processForm.processing
                                        ? "Memproses..."
                                        : "Ya, Proses"
                                }}
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>

            <!-- Modal 2: tambah / edit pelacakan (satu modal untuk dua mode) -->
            <Transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="isTrackingModalOpen"
                    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="tracking-title"
                    @click.self="closeTrackingModal"
                >
                    <div
                        class="relative w-full max-w-md bg-neutral-primary-soft rounded-sm border border-default shadow-lg p-5"
                    >
                        <!-- Judul + tombol tutup -->
                        <div class="flex justify-between items-center pb-2">
                            <h3
                                id="tracking-title"
                                class="text-lg font-medium text-heading"
                            >
                                {{
                                    editingTrackingId
                                        ? "Edit Pelacakan"
                                        : "Tambah Pelacakan"
                                }}
                            </h3>
                            <button
                                type="button"
                                @click="closeTrackingModal"
                                class="text-body bg-transparent hover:text-heading hover:bg-neutral-tertiary rounded-sm w-9 h-9 flex items-center justify-center ms-auto transition-all duration-200 ease-in-out active:scale-95"
                            >
                                <CloseIcon size="24" />
                                <span class="sr-only">Tutup</span>
                            </button>
                        </div>

                        <!-- Form -->
                        <form @submit.prevent="submitTracking">
                            <div class="py-2">
                                <label
                                    for="tracking-note"
                                    class="block mb-2.5 text-sm font-medium text-heading"
                                >
                                    Keterangan
                                </label>
                                <input
                                    type="text"
                                    id="tracking-note"
                                    v-model="trackingForm.note"
                                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-sm focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                                    required
                                />
                                <span
                                    v-if="trackingForm.errors.note"
                                    class="text-xs text-red-500 mt-1 block"
                                >
                                    {{ trackingForm.errors.note }}
                                </span>
                            </div>

                            <!-- Tombol -->
                            <div
                                class="flex justify-end items-center gap-3 pt-4 mt-2"
                            >
                                <button
                                    type="button"
                                    @click="closeTrackingModal"
                                    class="text-body bg-neutral-secondary-medium border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading shadow-xs font-medium leading-5 rounded-full text-sm px-4 py-3 focus:outline-none transition-all duration-200 ease-in-out active:scale-95"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    :disabled="trackingForm.processing"
                                    class="inline-flex items-center text-white bg-primary-500 hover:bg-primary-600 border border-transparent shadow-xs font-medium leading-5 rounded-full text-sm px-4 py-3 focus:outline-none transition-all duration-200 ease-in-out active:scale-95 disabled:opacity-60"
                                >
                                    {{ trackingSubmitText }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Transition>

            <!-- Modal 3: konfirmasi hapus pelacakan -->
            <Transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="isDeleteModalOpen"
                    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50"
                    role="alertdialog"
                    aria-modal="true"
                    aria-labelledby="delete-title"
                    @click.self="closeDeleteModal"
                >
                    <div
                        class="relative w-full max-w-sm bg-neutral-primary-soft rounded-sm border border-default shadow-lg p-5 text-center"
                    >
                        <div
                            class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-red-50 text-red-600"
                        >
                            <TrashBinTrashIcon size="28" />
                        </div>

                        <h3
                            id="delete-title"
                            class="text-lg font-semibold text-heading"
                        >
                            Hapus Catatan Pelacakan ini?
                        </h3>

                        <div class="mt-6 flex gap-3">
                            <button
                                type="button"
                                @click="closeDeleteModal"
                                :disabled="isDeleting"
                                class="flex-1 inline-flex items-center justify-center h-10 px-4 text-sm font-semibold text-body bg-neutral-secondary-medium border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading rounded-sm shadow-xs focus:outline-none transition duration-200 active:scale-95 disabled:opacity-60"
                            >
                                Batal
                            </button>
                            <button
                                type="button"
                                @click="deleteTracking"
                                :disabled="isDeleting"
                                class="flex-1 inline-flex items-center justify-center h-10 px-4 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 border border-transparent rounded-sm shadow-xs focus:outline-none focus:ring-4 focus:ring-red-200 transition duration-200 active:scale-95 disabled:opacity-60"
                            >
                                {{ isDeleting ? "Menghapus..." : "Ya, Hapus" }}
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>

            <!-- Modal 4: konfirmasi selesai ticket -->
            <Transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="isDoneModalOpen"
                    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="done-title"
                    @click.self="closeDoneModal"
                >
                    <div
                        class="w-full max-w-sm max-h-[90vh] flex flex-col bg-white rounded-2xl shadow-lg p-5"
                    >
                        <h3
                            id="done-title"
                            class="text-lg font-semibold text-heading"
                        >
                            Selesaikan tiket?
                        </h3>
                        <p class="mt-1 text-sm text-body">
                            Yakin ingin menyelesaikan tiket ini?
                        </p>

                        <p v-if="doneError" class="mt-3 text-sm text-red-500">
                            {{ doneError }}
                        </p>

                        <div class="mt-6 flex gap-3">
                            <button
                                type="button"
                                @click="closeDoneModal"
                                :disabled="doneForm.processing"
                                class="flex-1 h-10 px-4 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-full transition active:scale-95 disabled:opacity-60"
                            >
                                Tidak
                            </button>
                            <button
                                type="button"
                                @click="processDone"
                                :disabled="doneForm.processing"
                                class="flex-1 h-10 px-4 text-sm font-semibold text-white bg-green-600 hover:bg-green-700 rounded-full transition active:scale-95 disabled:opacity-60"
                            >
                                {{
                                    doneForm.processing
                                        ? "Memproses..."
                                        : "Ya, Selesaikan"
                                }}
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </main>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import { AddIcon } from "@solar-icons/vue/bold/add";
import { CloseIcon } from "@solar-icons/vue/bold/close";
import { PenIcon } from "@solar-icons/vue/bold/pen";
import { TrashBinTrashIcon } from "@solar-icons/vue/bold/trash-bin-trash";
import { CloudCheckIcon } from "@solar-icons/vue/bold/cloud-check";
import AdminLayout from "../../Layouts/AdminLayout.vue";
import { StarIcon } from "@solar-icons/vue/bold/star";

// ==================== DATA DARI CONTROLLER ====================
const props = defineProps({
    ticket: Object,
    technician: Array, // daftar teknisi yang bisa dipilih
});

// ==================== USER YANG SEDANG LOGIN ====================
const page = usePage();
const user = computed(() => page.props.auth.user);

// ==================== TAMPILAN: WARNA BADGE ====================
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

// ==================== TAMPILAN: TANGGAL & KETERLAMBATAN ====================
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

// Menampilkan "Hari ini", "Kemarin", atau tanggal singkat (misal 13 Okt)
const getRelativeDay = (dateString) => {
    if (!dateString) return "-";

    const date = new Date(dateString);
    const today = new Date();
    const yesterday = new Date(today);
    yesterday.setDate(yesterday.getDate() - 1);

    if (date.toDateString() === today.toDateString()) return "Hari ini";
    if (date.toDateString() === yesterday.toDateString()) return "Kemarin";

    return date.toLocaleDateString("id-ID", { day: "numeric", month: "short" });
};

// Menampilkan jam dengan format 14:30
const formatTime = (dateString) => {
    if (!dateString) return "-";
    return new Date(dateString)
        .toLocaleTimeString("id-ID", { hour: "2-digit", minute: "2-digit" })
        .replace(".", ":");
};

// ==================== PROSES TIKET (PILIH TEKNISI) ====================
const isTechnicianModalOpen = ref(false);
const technicianError = ref("");

const processForm = useForm({
    status: "in progress",
    assigned_to: "",
});

// Tombol "Proses Tiket" tampil selama tiket belum dibatalkan/ditutup/diproses
const canProcess = computed(() => props.ticket.status === "open");

const closeTechnicianModal = () => {
    if (!processForm.processing) isTechnicianModalOpen.value = false;
};

const processTicket = () => {
    if (!processForm.assigned_to) {
        technicianError.value =
            "Silakan pilih teknisi sebelum memproses tiket.";
        return;
    }

    processForm.put(`/admin/ticket/${props.ticket.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            isTechnicianModalOpen.value = false;
        },
        onError: (errors) => {
            technicianError.value =
                Object.values(errors)[0] ?? "Gagal memproses tiket.";
        },
    });
};

// ==================== RIWAYAT PELACAKAN ====================
// Kalau tiket belum punya riwayat, pakai array kosong supaya tidak error
const trackings = computed(() => props.ticket.tracking ?? []);

// Tombol "Pelacakan" hanya tampil untuk teknisi yang ditugaskan di tiket ini
const canTracking = computed(
    () =>
        Number(props.ticket.assigned_to) === Number(user.value.id) &&
        props.ticket.status !== "done",
);

// ==================== TAMBAH & EDIT PELACAKAN ====================

const isTrackingModalOpen = ref(false);
const editingTrackingId = ref(null);

const trackingForm = useForm({
    note: "",
});

const trackingSubmitText = computed(() => {
    if (trackingForm.processing) {
        return editingTrackingId.value ? "Updating..." : "Menyimpan...";
    }
    return editingTrackingId.value ? "Update" : "Simpan";
});

const openAddTrackingModal = () => {
    editingTrackingId.value = null;
    trackingForm.reset();
    trackingForm.clearErrors();
    isTrackingModalOpen.value = true;
};

const openEditTrackingModal = (track) => {
    editingTrackingId.value = track.id;
    trackingForm.note = track.note;
    trackingForm.clearErrors();
    isTrackingModalOpen.value = true;
};

const closeTrackingModal = () => {
    if (!trackingForm.processing) isTrackingModalOpen.value = false;
};

const submitTracking = () => {
    const options = {
        preserveScroll: true,
        onSuccess: () => {
            isTrackingModalOpen.value = false;
            trackingForm.reset();
        },
    };

    // TODO: sesuaikan URL dengan route di routes/web.php
    if (editingTrackingId.value) {
        trackingForm.put(
            `/admin/ticket/${props.ticket.id}/tracking/${editingTrackingId.value}`,
            options,
        );
    } else {
        trackingForm.post(`/admin/ticket/${props.ticket.id}/tracking`, options);
    }
};

// ==================== HAPUS PELACAKAN ====================
const isDeleteModalOpen = ref(false);
const isDeleting = ref(false);
const trackingToDelete = ref(null);

const openDeleteModal = (track) => {
    trackingToDelete.value = track;
    isDeleteModalOpen.value = true;
};

const closeDeleteModal = () => {
    if (isDeleting.value) return;
    isDeleteModalOpen.value = false;
};

const deleteTracking = () => {
    if (!trackingToDelete.value) return;

    router.delete(
        `/admin/ticket/${props.ticket.id}/tracking/${trackingToDelete.value.id}`,
        {
            preserveScroll: true,
            onStart: () => (isDeleting.value = true),
            onSuccess: () => (isDeleteModalOpen.value = false),
            onFinish: () => (isDeleting.value = false),
        },
    );
};

// ==================== MENYELESAIKAN TIKET ====================
const isDoneModalOpen = ref(false);
const doneError = ref("");

const doneForm = useForm({});

// Tombol hanya tampil untuk teknisi yang ditugaskan, saat tiket sedang diproses
const canDone = computed(
    () => props.ticket.status === "in progress" && canTracking.value,
);

const closeDoneModal = () => {
    if (!doneForm.processing) isDoneModalOpen.value = false;
};

const processDone = () => {
    doneForm.put(`/admin/ticket/${props.ticket.id}/done`, {
        preserveScroll: true,
        onSuccess: () => {
            isDoneModalOpen.value = false;
        },
        onError: (errors) => {
            doneError.value =
                Object.values(errors)[0] ?? "Gagal menyelesaikan tiket.";
        },
    });
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
