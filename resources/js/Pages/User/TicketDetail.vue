<template>
    <NavUser />

    <main class="min-h-screen bg-gray-100 px-4 pt-20 pb-6 lg:ms-64 lg:pt-24">
        <h1
            class="text-center text-base font-bold tracking-wide text-gray-900 uppercase"
        >
            Detail Tiket
        </h1>

        <!-- Laptop: 2 kolom (kiri = tiket, kanan = riwayat pelacakan). HP: menumpuk -->
        <div
            class="mx-auto mt-7 grid max-w-md grid-cols-1 items-start gap-6 lg:max-w-5xl lg:grid-cols-2"
        >
            <!-- ==================== KOLOM KIRI: TIKET ==================== -->
            <div class="drop-shadow-lg">
                <!-- Bagian atas tiket -->
                <div class="ticket-top rounded-t-2xl bg-white p-5 pb-6">
                    <!-- Departemen + status -->
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

                    <!-- Deskripsi + foto -->
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

                    <!-- Teknisi, kategori, prioritas -->
                    <div class="mt-4 flex items-start justify-between gap-3">
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

                <!-- Bagian bawah tiket -->
                <div
                    class="ticket-bottom relative rounded-b-2xl bg-white p-5 pt-6"
                >
                    <!-- Garis sobekan -->
                    <div
                        class="absolute inset-x-5 top-0 border-t-2 border-dashed border-gray-200"
                    ></div>

                    <!-- Tanggal dibuat + keterlambatan -->
                    <div class="flex items-center justify-between gap-3">
                        <div class="min-w-0 leading-tight">
                            <p class="text-[11px] text-gray-400">Dibuat</p>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ formatDate(ticket.created_at) }}
                            </p>
                        </div>

                        <span
                            v-if="getEtaStatus(ticket)"
                            class="inline-block text-xs font-medium text-red-600 bg-red-50 px-2 py-0.5 rounded-full"
                        >
                            {{ getEtaStatus(ticket) }}
                        </span>
                    </div>

                    <!-- Nomor tiket -->
                    <p
                        class="mt-4 text-center font-mono text-xs tracking-wider text-gray-500"
                    >
                        {{ ticket.ticket_number }}
                    </p>

                    <!-- Tombol batalkan (hanya untuk tiket open) -->
                    <button
                        v-if="canCancel"
                        type="button"
                        @click="isCancelModalOpen = true"
                        class="w-full my-4 px-4 py-3 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-full focus:outline-none focus:ring-4 focus:ring-red-200 transition active:scale-95"
                    >
                        Batalkan Tiket
                    </button>

                    <!-- Keterangan kalau tiket tidak bisa dibatalkan -->
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

            <!-- ==================== KOLOM KANAN: RIWAYAT PELACAKAN ==================== -->
            <div>
                <div
                    v-if="ticket.tracking && ticket.tracking.length > 0"
                    class="mb-6 ms-2 mt-2"
                >
                    <div
                        v-for="(log, index) in ticket.tracking"
                        :key="log.id"
                        class="flex gap-3 items-stretch"
                    >
                        <!-- Kiri: Tanggal & Waktu -->
                        <div class="w-12 shrink-0 text-right pt-0.5">
                            <p class="text-sm font-medium text-gray-700">
                                {{ getRelativeDay(log.created_at) }}
                            </p>
                            <p class="text-[11px] text-gray-500">
                                {{ formatTime(log.created_at) }}
                            </p>
                        </div>

                        <!-- Tengah: Titik & Garis -->
                        <div class="flex flex-col items-center">
                            <!-- Titik bulat hijau -->
                            <div
                                class="w-2 h-2 bg-emerald-700 rounded-full mt-1.5 shrink-0"
                            ></div>
                            <!-- Garis vertikal penghubung (hilang di data paling terakhir) -->
                            <div
                                v-if="index !== ticket.tracking.length - 1"
                                class="w-px flex-1 bg-gray-300 mt-1"
                            ></div>
                        </div>

                        <!-- Kanan: Catatan Pelacakan -->
                        <div class="flex-1 pb-6 pt-0.5">
                            <p class="text-sm font-medium text-emerald-700">
                                {{ log.note }}
                            </p>
                            <!-- Nama penulis (bisa admin atau teknisi) -->
                            <p class="text-[10px] text-gray-400 mt-1">
                                Oleh: {{ log.user ? log.user.name : "Sistem" }}
                            </p>
                        </div>
                    </div>
                </div>

                <div v-else class="text-sm text-gray-500 italic mb-6 ms-2">
                    Belum ada riwayat pelacakan.
                </div>
                <!-- Penilaian: hanya muncul kalau tiket sudah selesai -->
                <div v-if="ticket.status === 'done'" class="mb-5 ms-2">
                    <p class="mb-2.5 text-sm font-medium text-gray-700">
                        Penilaian Tiket
                    </p>

                    <form @submit.prevent="submitReview" class="space-y-4">
                        <div>
                            <label
                                v-if="!ticket.review"
                                class="block mb-2 text-sm font-medium text-gray-700"
                                >Beri Rating:</label
                            >

                            <!-- Satu baris bintang untuk dua mode: form (bisa diklik) dan read-only (sudah dinilai) -->
                            <div
                                class="flex items-center space-x-1"
                                @mouseleave="hoverRating = 0"
                            >
                                <StarIcon
                                    size="24"
                                    v-for="star in 5"
                                    :key="star"
                                    @click="
                                        !ticket.review && (form.rating = star)
                                    "
                                    @mouseenter="
                                        !ticket.review && (hoverRating = star)
                                    "
                                    :class="[
                                        'w-8 h-8 transition-colors duration-200',
                                        ticket.review ? '' : 'cursor-pointer',
                                        star <= displayRating
                                            ? 'text-yellow-400'
                                            : 'text-gray-300',
                                    ]"
                                />
                            </div>

                            <span
                                v-if="form.errors.rating"
                                class="mt-1 block text-sm text-red-500"
                            >
                                {{ form.errors.rating }}
                            </span>
                        </div>

                        <!-- Tag: bisa dipilih kalau belum dinilai, hanya tampil tag terpilih kalau sudah -->
                        <div
                            v-if="!ticket.review || ticket.review.tags?.length"
                        >
                            <label
                                v-if="!ticket.review"
                                class="block mb-2 text-sm font-medium text-gray-700"
                                >Tag (opsional):</label
                            >
                            <div class="flex flex-wrap gap-2">
                                <template v-if="ticket.review">
                                    <span
                                        v-for="tag in ticket.review.tags"
                                        :key="tag"
                                        class="rounded-full border border-gray-300 bg-gray-50 px-3 py-1 text-xs text-gray-700"
                                    >
                                        {{ tag }}
                                    </span>
                                </template>
                                <template v-else>
                                    <button
                                        v-for="tag in reviewTags"
                                        :key="tag"
                                        type="button"
                                        @click="toggleTag(tag)"
                                        :class="[
                                            'rounded-full border px-3 py-1 text-xs transition active:scale-95',
                                            form.tags.includes(tag)
                                                ? 'border-amber-600 bg-amber-600 text-white'
                                                : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50',
                                        ]"
                                    >
                                        {{ tag }}
                                    </button>
                                </template>
                            </div>
                        </div>

                        <button
                            v-if="!ticket.review"
                            type="submit"
                            :disabled="form.processing"
                            class="w-full my-4 px-4 py-3 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-full focus:outline-none focus:ring-4 focus:ring-red-200 transition active:scale-95"
                        >
                            {{
                                form.processing
                                    ? "Menyimpan..."
                                    : "Kirim Rating"
                            }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- ==================== MODAL ==================== -->

        <!-- Modal konfirmasi batalkan tiket -->
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="isCancelModalOpen"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50"
                role="alertdialog"
                aria-modal="true"
                aria-labelledby="cancel-title"
                @click.self="closeCancelModal"
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
                            @click="closeCancelModal"
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
    </main>
</template>

<script setup>
import { ref, computed } from "vue";
import { useForm, usePage, router } from "@inertiajs/vue3";
import { AddIcon } from "@solar-icons/vue/bold/add";
import NavUser from "../../Layouts/NavUser.vue";
import { StarIcon } from "@solar-icons/vue/bold/star";

// ==================== DATA DARI CONTROLLER ====================
const props = defineProps({
    ticket: Object,
});

// ==================== USER YANG SEDANG LOGIN ====================
const page = usePage();
const user = computed(() => page.props.auth.user);

// ==================== BATALKAN TIKET ====================
const isCancelModalOpen = ref(false);
const cancelForm = useForm({});

// Tiket hanya bisa dibatalkan selama statusnya masih open
const canCancel = computed(() => props.ticket.status === "open");

// Keterangan yang tampil kalau tiket tidak bisa dibatalkan
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

const closeCancelModal = () => {
    if (!cancelForm.processing) isCancelModalOpen.value = false;
};

const cancelTicket = () => {
    cancelForm.put(`/user/ticket/${props.ticket.id}`, {
        onSuccess: () => {
            isCancelModalOpen.value = false;
            router.visit("/user/home");
        },
    });
};

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

// ==================== RIWAYAT PELACAKAN ====================
// Kalau tiket belum punya riwayat, pakai array kosong supaya tidak error
const trackings = computed(() => props.ticket.tracking ?? []);

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

// ==================== RATING ====================
const reviewTags = [
    "Cepat",
    "Ramah",
    "Solusi tepat",
    "Komunikatif",
    "Lambat",
    "Kurang jelas",
];

const hoverRating = ref(0);

const form = useForm({
    ticket_id: props.ticket.id,
    rating: 0,
    tags: [],
});

// Bintang yang menyala: rating tersimpan kalau sudah dinilai, kalau belum ikut hover/pilihan
const displayRating = computed(() =>
    props.ticket.review
        ? props.ticket.review.rating
        : hoverRating.value || form.rating,
);

const toggleTag = (tag) => {
    const i = form.tags.indexOf(tag);
    i === -1 ? form.tags.push(tag) : form.tags.splice(i, 1);
};

const submitReview = () => {
    // Setelah sukses, Inertia memuat ulang props sehingga ticket.review terisi
    // dan tampilan otomatis berubah jadi read-only
    form.post("/user/reviews", { preserveScroll: true });
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
