<template>
    <UserLayout>
        <main>
            <h1
                class="text-center text-base font-bold tracking-wide text-gray-900 dark:text-white uppercase"
            >
                Detail Tiket
            </h1>

            <!-- Laptop: 2 kolom (kiri = tiket, kanan = riwayat pelacakan). HP: menumpuk -->
            <div
                class="mx-auto mt-7 grid max-w-md grid-cols-1 items-start gap-6 lg:max-w-5xl lg:grid-cols-2"
            >
                <!-- ==================== KOLOM KIRI: TIKET ==================== -->
                <div class="drop-shadow-lg dark:drop-shadow-none">
                    <!-- Bagian atas tiket -->
                    <div
                        class="ticket-top rounded-t-2xl bg-white dark:bg-gray-800 p-5 pb-6"
                    >
                        <!-- Departemen + status -->
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0 leading-tight">
                                <p :class="infoLabelClass">Departemen</p>
                                <p :class="infoValueClass">
                                    {{ departmentName }}
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
                                <p :class="infoLabelClass">Teknisi</p>
                                <p :class="infoValueClass">
                                    {{
                                        ticket.technician?.name ??
                                        "Belum ada teknisi"
                                    }}
                                </p>
                            </div>
                            <div class="min-w-0 leading-tight">
                                <p :class="infoLabelClass">Kategori</p>
                                <p :class="infoValueClass">
                                    {{ ticket.category }}
                                </p>
                            </div>
                            <div class="min-w-0 leading-tight">
                                <p :class="infoLabelClass">Prioritas</p>
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
                                <p :class="infoLabelClass">Dibuat</p>
                                <p :class="infoValueClass">
                                    {{ formatDate(ticket.created_at) }}
                                </p>
                            </div>

                            <span
                                v-if="lateText"
                                class="inline-block text-xs font-medium text-red-600 bg-red-50 dark:bg-red-900/40 dark:text-red-400 px-2 py-0.5 rounded-full"
                            >
                                {{ lateText }}
                            </span>
                        </div>

                        <!-- Nomor tiket -->
                        <p
                            class="mt-4 text-center font-mono text-xs tracking-wider text-gray-500 dark:text-gray-400"
                        >
                            {{ ticket.ticket_number }}
                        </p>

                        <!-- Tombol batalkan (hanya untuk tiket open) -->
                        <button
                            v-if="canCancel"
                            type="button"
                            @click="isCancelModalOpen = true"
                            class="w-full my-4 px-4 py-3 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-full focus:outline-none focus:ring-4 focus:ring-red-200 dark:focus:ring-red-900 transition active:scale-95"
                        >
                            Batalkan Tiket
                        </button>

                        <!-- Keterangan kalau tiket tidak bisa dibatalkan -->
                        <p
                            v-else-if="statusMessage"
                            class="my-4 rounded-lg border py-3 text-center text-sm font-medium transition-colors"
                            :class="
                                ticket.status === 'cancelled'
                                    ? 'border-red-200 bg-red-50 text-red-600 dark:border-red-900 dark:bg-red-900/30 dark:text-red-400'
                                    : 'border-gray-200 bg-gray-50 text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400'
                            "
                        >
                            {{ statusMessage }}
                        </p>
                    </div>
                </div>

                <!-- ==================== KOLOM KANAN: PELACAKAN & RATING ==================== -->
                <div>
                    <!-- Riwayat pelacakan -->
                    <div v-if="trackingLogs.length > 0" class="mb-6 ms-2 mt-2">
                        <div
                            v-for="(log, index) in trackingLogs"
                            :key="log.id"
                            class="flex gap-3 items-stretch"
                        >
                            <!-- Kiri: tanggal & waktu -->
                            <div class="w-12 shrink-0 text-right pt-0.5">
                                <p
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                >
                                    {{ getRelativeDay(log.created_at) }}
                                </p>
                                <p
                                    class="text-[11px] text-gray-500 dark:text-gray-500"
                                >
                                    {{ formatTime(log.created_at) }}
                                </p>
                            </div>

                            <!-- Tengah: titik & garis -->
                            <div class="flex flex-col items-center">
                                <div
                                    class="w-2 h-2 bg-emerald-700 dark:bg-emerald-500 rounded-full mt-1.5 shrink-0"
                                ></div>
                                <!-- Garis penghubung (tidak ada di data paling terakhir) -->
                                <div
                                    v-if="index !== trackingLogs.length - 1"
                                    class="w-px flex-1 bg-gray-300 dark:bg-gray-700 mt-1"
                                ></div>
                            </div>

                            <!-- Kanan: catatan pelacakan -->
                            <div class="flex-1 pb-6 pt-0.5">
                                <p
                                    class="text-sm font-medium text-emerald-700 dark:text-emerald-400"
                                >
                                    {{ log.note }}
                                </p>
                                <p
                                    class="text-[10px] text-gray-400 dark:text-gray-500 mt-1"
                                >
                                    Oleh: {{ log.user?.name ?? "Sistem" }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        v-else
                        class="text-sm text-gray-500 dark:text-gray-400 italic mb-6 ms-2"
                    >
                        Belum ada riwayat pelacakan.
                    </div>

                    <!-- Penilaian: hanya muncul kalau tiket sudah selesai -->
                    <div v-if="ticket.status === 'done'" class="mb-5 ms-2">
                        <p
                            class="mb-2.5 text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            Penilaian Tiket
                        </p>

                        <form @submit.prevent="submitReview" class="space-y-4">
                            <!-- Bintang: bisa diklik kalau belum dinilai, hanya tampilan kalau sudah -->
                            <div>
                                <label
                                    v-if="!isReviewed"
                                    :class="reviewLabelClass"
                                >
                                    Beri Rating:
                                </label>

                                <div
                                    class="flex items-center space-x-1"
                                    @mouseleave="hoverRating = 0"
                                >
                                    <StarIcon
                                        v-for="star in 5"
                                        :key="star"
                                        size="24"
                                        @click="selectStar(star)"
                                        @mouseenter="hoverStar(star)"
                                        :class="[
                                            'w-8 h-8 transition-colors duration-200',
                                            isReviewed ? '' : 'cursor-pointer',
                                            star <= displayRating
                                                ? 'text-yellow-400'
                                                : 'text-gray-300 dark:text-gray-600',
                                        ]"
                                    />
                                </div>

                                <span
                                    v-if="reviewForm.errors.rating"
                                    class="mt-1 block text-sm text-red-500"
                                >
                                    {{ reviewForm.errors.rating }}
                                </span>
                            </div>

                            <!-- Tag: bisa dipilih kalau belum dinilai, hanya tag terpilih yang tampil kalau sudah -->
                            <div
                                v-if="!isReviewed || ticket.review.tags?.length"
                            >
                                <label
                                    v-if="!isReviewed"
                                    :class="reviewLabelClass"
                                >
                                    Tag (opsional):
                                </label>
                                <div class="flex flex-wrap gap-2">
                                    <template v-if="isReviewed">
                                        <span
                                            v-for="tag in ticket.review.tags"
                                            :key="tag"
                                            class="rounded-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 px-3 py-1 text-xs text-gray-700 dark:text-gray-300"
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
                                                reviewForm.tags.includes(tag)
                                                    ? 'border-amber-600 bg-amber-600 text-white dark:border-amber-500 dark:bg-amber-500'
                                                    : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700',
                                            ]"
                                        >
                                            {{ tag }}
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <button
                                v-if="!isReviewed"
                                type="submit"
                                :disabled="reviewForm.processing"
                                class="w-full my-4 px-4 py-3 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-full focus:outline-none focus:ring-4 focus:ring-amber-200 dark:focus:ring-amber-900 transition active:scale-95 disabled:opacity-50"
                            >
                                {{
                                    reviewForm.processing
                                        ? "Menyimpan..."
                                        : "Kirim Rating"
                                }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ==================== MODAL KONFIRMASI BATALKAN TIKET ==================== -->
            <Transition name="fade">
                <div
                    v-if="isCancelModalOpen"
                    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50"
                    role="alertdialog"
                    aria-modal="true"
                    aria-labelledby="cancel-title"
                    @click.self="closeCancelModal"
                >
                    <div
                        class="w-full max-w-sm bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-5 text-center"
                    >
                        <h3
                            id="cancel-title"
                            class="text-lg font-semibold text-heading dark:text-white"
                        >
                            Batalkan tiket ini?
                        </h3>
                        <p class="mt-2 text-sm text-body dark:text-gray-300">
                            Tiket
                            <span
                                class="font-semibold text-heading dark:text-white"
                            >
                                {{ ticket.ticket_number }}
                            </span>
                            akan dibatalkan dan tidak bisa dikembalikan.
                        </p>

                        <div class="mt-6 flex gap-3">
                            <button
                                type="button"
                                @click="closeCancelModal"
                                :disabled="cancelForm.processing"
                                class="flex-1 h-10 px-4 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 rounded-full transition active:scale-95 disabled:opacity-60"
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
    </UserLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { useForm, usePage, router } from "@inertiajs/vue3";
import { StarIcon } from "@solar-icons/vue/bold/star";
import UserLayout from "../../Layouts/UserLayout.vue";

// ==================== DATA DARI LARAVEL ====================
// `ticket` dikirim dari User\TicketController@show (sudah termasuk relasinya)
const props = defineProps({
    ticket: Object,
});

// Data user yang sedang login (dibagikan Inertia ke semua halaman)
const page = usePage();
const user = computed(() => page.props.auth.user);

// ==================== CLASS YANG DIPAKAI BERULANG ====================
const infoLabelClass = "text-[11px] text-gray-400 dark:text-gray-500";
const infoValueClass =
    "text-sm font-semibold text-gray-900 dark:text-white truncate";
const reviewLabelClass =
    "block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300";

// ==================== DATA TIKET UNTUK TAMPILAN ====================
// Departemen tiket; kalau kosong pakai departemen user yang login
const departmentName = computed(
    () =>
        props.ticket.department?.department_name ??
        user.value.department?.department_name ??
        "-",
);

// Kalau tiket belum punya riwayat, pakai array kosong supaya tidak error
const trackingLogs = computed(() => props.ticket.tracking ?? []);

// Warna badge prioritas
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

// Warna badge status
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

// ==================== TANGGAL & KETERLAMBATAN ====================
const formatDate = (date) => {
    if (!date) return "-";

    return new Date(date).toLocaleDateString("id-ID", {
        day: "numeric",
        month: "short",
        year: "numeric",
    });
};

// Teks keterlambatan, misal "Lebih lambat 3 jam". Berisi null kalau tidak terlambat.
const lateText = computed(() => {
    const { eta, status } = props.ticket;
    const isFinished = ["resolved", "cancelled", "done"].includes(status);
    if (!eta || isFinished) return null;

    const hoursLate = Math.floor(
        (new Date() - new Date(eta)) / (1000 * 60 * 60),
    );

    if (hoursLate < 1) return null; // belum lewat ETA
    if (hoursLate >= 24)
        return `Lebih lambat ${Math.floor(hoursLate / 24)} hari`;
    return `Lebih lambat ${hoursLate} jam`;
});

// "Hari ini", "Kemarin", atau tanggal singkat (misal 13 Okt)
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

// Jam dengan format 14:30
const formatTime = (dateString) => {
    if (!dateString) return "-";

    return new Date(dateString)
        .toLocaleTimeString("id-ID", { hour: "2-digit", minute: "2-digit" })
        .replace(".", ":");
};

// ==================== BATALKAN TIKET ====================
const isCancelModalOpen = ref(false);
const cancelForm = useForm({}); // form kosong, hanya untuk memakai status "processing"

// Tiket hanya bisa dibatalkan selama statusnya masih open
const canCancel = computed(() => props.ticket.status === "open");

// Keterangan yang tampil kalau tiket tidak bisa dibatalkan
const statusMessages = {
    cancelled: "Tiket ini sudah dibatalkan.",
    accepted: "Tiket sudah diterima teknisi dan tidak bisa dibatalkan.",
    "in progress": "Tiket sedang dikerjakan teknisi dan tidak bisa dibatalkan.",
    done: "Tiket ini sudah selesai.",
};
const statusMessage = computed(() => statusMessages[props.ticket.status] ?? "");

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

// ==================== RATING ====================
const reviewTags = [
    "Cepat",
    "Ramah",
    "Solusi tepat",
    "Komunikatif",
    "Lambat",
    "Kurang jelas",
];

// useForm dari Inertia: menyimpan isi form, error validasi, dan status "sedang mengirim"
const reviewForm = useForm({
    ticket_id: props.ticket.id,
    rating: 0,
    tags: [],
});

const hoverRating = ref(0); // bintang yang sedang di-hover
const isReviewed = computed(() => !!props.ticket.review); // tiket sudah pernah dinilai?

// Bintang yang menyala: rating tersimpan kalau sudah dinilai, kalau belum ikut hover/pilihan
const displayRating = computed(() =>
    isReviewed.value
        ? props.ticket.review.rating
        : hoverRating.value || reviewForm.rating,
);

// Bintang hanya bisa diklik/di-hover kalau tiket belum dinilai
const selectStar = (star) => {
    if (!isReviewed.value) reviewForm.rating = star;
};

const hoverStar = (star) => {
    if (!isReviewed.value) hoverRating.value = star;
};

// Pilih tag kalau belum ada, hapus kalau sudah dipilih
const toggleTag = (tag) => {
    const index = reviewForm.tags.indexOf(tag);

    if (index === -1) {
        reviewForm.tags.push(tag);
    } else {
        reviewForm.tags.splice(index, 1);
    }
};

const submitReview = () => {
    // Setelah sukses, Inertia memuat ulang props sehingga ticket.review terisi
    // dan tampilan otomatis berubah jadi read-only
    reviewForm.post("/user/reviews", { preserveScroll: true });
};
</script>

<style scoped>
/* Animasi muncul/hilang untuk modal (dipakai lewat <Transition name="fade">) */
.fade-enter-active {
    transition: opacity 200ms ease-out;
}
.fade-leave-active {
    transition: opacity 150ms ease-in;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Lekukan tiket (efek sobekan karcis): atas = sudut kiri-bawah & kanan-bawah */
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

/* Bagian bawah: lekukan di sudut kiri-atas & kanan-atas */
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
