<template>
    <AdminLayout>
        <main>
            <!-- ==================== HEADER: profil + filter status ==================== -->
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
                    </div>
                </div>

                <!-- Tombol filter status -->
                <div
                    class="sticky top-[72px] z-10 flex gap-2 py-2 -mx-4 px-4 overflow-x-auto lg:static lg:mx-0 lg:px-0 lg:py-0 lg:overflow-visible"
                >
                    <button
                        v-for="status in statusOptions"
                        :key="status.value"
                        type="button"
                        @click="activeStatus = status.value"
                        class="px-3 py-2 text-xs lg:text-sm rounded-full whitespace-nowrap transition active:scale-95"
                        :class="
                            activeStatus === status.value
                                ? 'bg-dark text-white'
                                : 'bg-white text-gray-700 hover:bg-gray-200'
                        "
                    >
                        {{ status.label }}
                    </button>
                </div>
            </div>

            <!-- ==================== CHART ==================== -->
            <div class="mt-6 grid grid-cols-1 gap-4 xl:grid-cols-2">
                <!-- Chart 1: tiket per bulan -->
                <div
                    class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 dark:border-gray-800 dark:bg-white/[0.03]"
                >
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <div>
                            <h3
                                class="text-lg font-semibold text-gray-800 dark:text-white/90"
                            >
                                Tiket per Bulan
                            </h3>
                            <p
                                class="text-theme-sm text-gray-500 dark:text-gray-400"
                            >
                                Jumlah pengajuan tiket tahun {{ chart.year }}
                            </p>
                        </div>

                        <!-- Dropdown tahun: .number supaya nilainya angka, bukan teks -->
                        <select
                            v-model.number="selectedYear"
                            @change="changeYear"
                            class="h-10 rounded-lg border border-gray-300 bg-transparent px-3 text-sm text-gray-800 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                        >
                            <option
                                v-for="year in chart.years"
                                :key="year"
                                :value="year"
                            >
                                {{ year }}
                            </option>
                        </select>
                    </div>

                    <!-- :key membuat chart digambar ulang saat tahun berganti -->
                    <VueApexCharts
                        :key="`monthly-${chart.year}`"
                        type="bar"
                        height="280"
                        :options="monthlyOptions"
                        :series="monthlySeries"
                    />
                </div>

                <!-- Chart 2: departemen terbanyak -->
                <div
                    class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 dark:border-gray-800 dark:bg-white/[0.03]"
                >
                    <div class="mb-4">
                        <h3
                            class="text-lg font-semibold text-gray-800 dark:text-white/90"
                        >
                            Departemen Terbanyak Mengajukan Tiket
                        </h3>
                        <p
                            class="text-theme-sm text-gray-500 dark:text-gray-400"
                        >
                            5 teratas tahun {{ chart.year }}
                        </p>
                    </div>

                    <VueApexCharts
                        v-if="chart.departments.length > 0"
                        :key="`dept-${chart.year}`"
                        type="bar"
                        height="280"
                        :options="departmentOptions"
                        :series="departmentSeries"
                    />
                    <p
                        v-else
                        class="flex h-[280px] items-center justify-center text-sm text-gray-500 dark:text-gray-400"
                    >
                        Belum ada tiket di tahun {{ chart.year }}.
                    </p>
                </div>
            </div>

            <!-- ==================== DAFTAR ORDER ==================== -->
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
                            <!-- Bagian atas kartu -->
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

                                <span class="mt-1 w-fit" :class="pillClass">
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
                                            ticket.technician?.name ??
                                            "Belum ada teknisi"
                                        }}
                                    </p>
                                </div>
                            </div>

                            <!-- Bagian bawah kartu -->
                            <div
                                class="ticket-bottom relative rounded-b-2xl bg-white dark:bg-gray-800 p-5 pt-6"
                            >
                                <!-- garis putus-putus, tepat di batas kedua bagian -->
                                <div
                                    class="absolute inset-x-5 top-0 border-t-2 border-dashed border-gray-200 dark:border-gray-700"
                                ></div>

                                <div class="flex flex-wrap items-center gap-2">
                                    <span :class="pillClass">
                                        <CalendarIcon size="15" />
                                        {{ formatDate(ticket.created_at) }}
                                    </span>

                                    <!-- Muncul hanya kalau tiket terlambat -->
                                    <span
                                        v-if="getLateText(ticket)"
                                        class="inline-flex items-center gap-1.5 rounded-full bg-red-100 dark:bg-red-900/40 px-3 py-1.5 text-xs font-medium text-red-700 dark:text-red-400"
                                    >
                                        <ClockCircleIcon size="15" />
                                        {{ getLateText(ticket) }}
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
import { Link, router, usePage } from "@inertiajs/vue3";
import VueApexCharts from "vue3-apexcharts";
import { ProgrammingIcon } from "@solar-icons/vue/bold/programming";
import { CalendarIcon } from "@solar-icons/vue/bold/calendar";
import { ClockCircleIcon } from "@solar-icons/vue/bold/clock-circle";
import { DangerTriangleIcon } from "@solar-icons/vue/bold/danger-triangle";
import AdminLayout from "../../Layouts/AdminLayout.vue";

// ==================== DATA DARI LARAVEL ====================
// `tickets` dan `chart` dikirim dari HomeController lewat Inertia::render(...)
const props = defineProps({
    tickets: Array,
    chart: Object,
});

// Data user yang sedang login (dibagikan Inertia ke semua halaman)
const page = usePage();
const user = computed(() => page.props.auth.user);

// Avatar acak, dibuat sekali saat halaman dibuka
const avatarSeed = Math.random().toString(36).substring(2, 10);
const avatarUrl = `https://api.dicebear.com/10.x/sprouts/svg?tags=animation&seed=${avatarSeed}`;

// ==================== FILTER STATUS ====================
const statusOptions = [
    { label: "Semua", value: "all" },
    { label: "Open", value: "open" },
    { label: "Di Proses", value: "in progress" },
    { label: "Selesai", value: "done" },
];
const activeStatus = ref("all");

const filteredTickets = computed(() => {
    if (activeStatus.value === "all") {
        return props.tickets;
    }
    return props.tickets.filter(
        (ticket) => ticket.status === activeStatus.value,
    );
});

// ==================== WARNA & TAMPILAN KARTU ====================
// Class "pil" abu-abu yang dipakai berulang (kategori & tanggal)
const pillClass =
    "inline-flex items-center gap-1.5 rounded-full bg-gray-100 dark:bg-gray-700 px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300";

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

// Teks keterlambatan, misal "Lebih lambat 3 jam". Mengembalikan null kalau tidak terlambat.
const getLateText = (ticket) => {
    const isFinished = ["resolved", "cancelled", "done"].includes(
        ticket.status,
    );
    if (!ticket.eta || isFinished) return null;

    const hoursLate = Math.floor(
        (new Date() - new Date(ticket.eta)) / (1000 * 60 * 60),
    );

    if (hoursLate < 1) return null; // belum lewat ETA
    if (hoursLate >= 24)
        return `Lebih lambat ${Math.floor(hoursLate / 24)} hari`;
    return `Lebih lambat ${hoursLate} jam`;
};

// ==================== CHART ====================
const selectedYear = ref(props.chart.year);

// Saat tahun diganti, minta ulang HANYA data `chart` ke Laravel
// (daftar tiket tidak ikut dimuat ulang)
const changeYear = () => {
    router.get(
        "/admin/home",
        { year: selectedYear.value },
        {
            only: ["chart"],
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
};

// Pengaturan dasar yang dipakai kedua chart
const baseChart = {
    fontFamily: "Outfit, sans-serif",
    toolbar: { show: false },
};

const monthNames = [
    "Jan",
    "Feb",
    "Mar",
    "Apr",
    "Mei",
    "Jun",
    "Jul",
    "Agu",
    "Sep",
    "Okt",
    "Nov",
    "Des",
];

// ----- Chart 1: tiket per bulan -----
const monthlySeries = computed(() => [
    { name: "Tiket", data: props.chart.monthly },
]);

const monthlyOptions = computed(() => {
    const maxValue = Math.max(...props.chart.monthly, 0);

    // Kalau datanya kecil (maks 5), paksa sumbu Y berupa bilangan bulat (0, 1, 2, ...)
    const smallDataAxis =
        maxValue <= 5
            ? { max: Math.max(maxValue, 1), tickAmount: Math.max(maxValue, 1) }
            : {};

    return {
        chart: { ...baseChart, type: "bar" },
        colors: ["#465fff"],
        plotOptions: {
            bar: {
                columnWidth: "45%",
                borderRadius: 5,
                borderRadiusApplication: "end",
            },
        },
        dataLabels: { enabled: false },
        xaxis: {
            categories: monthNames,
            axisBorder: { show: false },
            axisTicks: { show: false },
        },
        yaxis: {
            min: 0,
            ...smallDataAxis,
            labels: { formatter: (value) => Math.round(value) },
        },
        grid: { yaxis: { lines: { show: true } } },
        tooltip: { y: { formatter: (value) => `${value} tiket` } },
    };
});

// ----- Chart 2: departemen terbanyak -----
const departmentSeries = computed(() => [
    {
        name: "Tiket",
        data: props.chart.departments.map((department) => department.total),
    },
]);

const departmentOptions = computed(() => ({
    chart: { ...baseChart, type: "bar" },
    colors: ["#465fff"],
    plotOptions: {
        bar: {
            horizontal: true,
            barHeight: "55%",
            borderRadius: 5,
            borderRadiusApplication: "end",
        },
    },
    dataLabels: { enabled: true },
    xaxis: {
        categories: props.chart.departments.map(
            (department) => department.name,
        ),
        labels: { formatter: (value) => Math.round(value) },
        axisBorder: { show: false },
        axisTicks: { show: false },
    },
    grid: {
        xaxis: { lines: { show: true } },
        yaxis: { lines: { show: false } },
    },
    tooltip: { y: { formatter: (value) => `${value} tiket` } },
}));
</script>

<style scoped>
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
