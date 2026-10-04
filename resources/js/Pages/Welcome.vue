<template>
    <div
        class="relative w-full max-w-sm overflow-hidden rounded-2xl bg-white p-5 shadow-lg"
    >
        <!-- Header: maskapai + tipe -->
        <div class="flex items-start justify-between">
            <div class="flex items-center gap-3">
                <div class="leading-tight">
                    <p class="text-[11px] text-gray-400">Departemen</p>
                    <p class="text-sm font-semibold text-gray-900">
                        Human Resource General Affair
                    </p>
                </div>
            </div>
            <div class="text-end leading-tight">
                <p class="text-[11px] text-gray-400">Status</p>
                <p class="text-sm font-semibold text-yellow-500">Diproses</p>
            </div>
        </div>

        <div class="mt-4">
            <p
                ref="descRef"
                class="text-sm font-normal text-gray-900 break-words whitespace-pre-line"
                :class="{ 'line-clamp-3': !expanded }"
            >
                {{ description }}
            </p>

            <button
                v-if="isClamped || expanded"
                type="button"
                @click="expanded = !expanded"
                class="mt-1 text-xs font-medium text-primary-500 hover:underline"
            >
                {{ expanded ? "Tampilkan lebih sedikit" : "Selengkapnya" }}
            </button>
        </div>
        <span
            class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1.5 text-xs font-medium text-gray-700 mt-2"
        >
            <ProgrammingIcon size="15" />
            Jaringan
        </span>

        <!-- Garis sobekan + lekukan -->
        <div class="relative -mx-5 my-5">
            <span
                class="absolute left-0 top-1/2 h-6 w-6 -translate-x-1/2 -translate-y-1/2 rounded-full"
                :class="notchClass"
            ></span>
            <span
                class="absolute right-0 top-1/2 h-6 w-6 translate-x-1/2 -translate-y-1/2 rounded-full"
                :class="notchClass"
            ></span>
            <div class="mx-5 border-t-2 border-dashed border-gray-200"></div>
        </div>

        <!-- Footer: tanggal, durasi, harga -->
        <div class="flex items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <span
                    class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1.5 text-xs font-medium text-gray-700"
                >
                    <CalendarIcon size="15" />
                    Dec 24, 2024
                </span>
                <span
                    class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-3 py-1.5 text-xs font-medium text-gray-700"
                >
                    <ClockCircleIcon size="15" />
                    1 Hari lebih Lambat
                </span>
            </div>
        </div>
        <div class="flex flex-col items-end">
            <p class="text-sm font-bold text-gray-900">tx-1236574561-qwert</p>
        </div>
    </div>
</template>

<script setup>
import { ProgrammingIcon } from "@solar-icons/vue/bold/programming";
import { CalendarIcon } from "@solar-icons/vue/bold/calendar";
import { ClockCircleIcon } from "@solar-icons/vue/bold/clock-circle";

import { ref, onMounted, watch, nextTick } from "vue";

const props = defineProps({
    description: {
        type: String,
        default:
            "Lorem ipsum dolor sit amet consectetur adipisicing elit. Possimus, atque eveniet. Rerum placeat ipsum consequuntur veniam ullam blanditiis. Rerum quaerat et ducimus nemo labore suscipit eveniet enim maxime iure placeat? Atque quos perferendis ex, consectetur porro ratione ipsum aliquid, accusantium hic, praesentium necessitatibus sapiente in quo cupiditate? Rem vel eveniet minima fugit, blanditiis quia hic, amet vitae earum placeat iste repellat magni. Officia modi sapiente odit unde asperiores vero illo necessitatibus maiores quidem. At provident, deleniti quisquam iure eaque aliquam ea a quasi quia, optio pariatur? Non, sint magni adipisci repudiandae ex architecto, minus vitae, assumenda error repellat et quis!",
    },
    // props lain tetap
    notchClass: { type: String, default: "bg-gray-100" },
});

const descRef = ref(null);
const expanded = ref(false);
const isClamped = ref(false);

// teks terpotong kalau tinggi isi aslinya lebih besar dari tinggi yang terlihat
const checkClamp = async () => {
    await nextTick();
    const el = descRef.value;
    if (!el || expanded.value) return;
    isClamped.value = el.scrollHeight > el.clientHeight + 1;
};

onMounted(checkClamp);
watch(
    () => props.description,
    () => {
        expanded.value = false;
        checkClamp();
    },
);
</script>
