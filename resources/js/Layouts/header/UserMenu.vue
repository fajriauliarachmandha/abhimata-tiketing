<template>
    <div class="relative" ref="dropdownRef">
        <button
            type="button"
            class="flex cursor-pointer items-center text-gray-700"
            @click.prevent="dropdownOpen = !dropdownOpen"
        >
            <span
                class="me-3 flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-brand-50 font-semibold text-brand-500"
            >
                {{ initial }}
            </span>
            <span class="me-1 block text-sm font-medium">{{ user?.name }}</span>
            <ChevronDownIcon
                class="size-5 text-gray-500 transition-transform duration-200"
                :class="{ 'rotate-180': dropdownOpen }"
            />
        </button>

        <div
            v-if="dropdownOpen"
            class="absolute end-0 z-50 mt-[17px] w-[260px] rounded-2xl border border-gray-200 bg-white p-3 shadow-lg"
        >
            <span class="block text-sm font-medium text-gray-700">
                {{ user?.name }}
            </span>
            <span
                v-if="user?.department"
                class="mt-0.5 block text-xs text-gray-500"
            >
                {{ user.department.department_name }}
            </span>

            <Link
                href="/logout"
                method="post"
                as="button"
                class="mt-3 flex w-full cursor-pointer items-center justify-center rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50"
            >
                Sign out
            </Link>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import { ChevronDownIcon } from "@/icons";

const page = usePage();
const user = computed(() => page.props.auth.user);
const initial = computed(() =>
    (user.value?.name ?? "?").charAt(0).toUpperCase(),
);

const dropdownOpen = ref(false);
const dropdownRef = ref(null);

const handleClickOutside = (event) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        dropdownOpen.value = false;
    }
};

onMounted(() => document.addEventListener("click", handleClickOutside));
onUnmounted(() => document.removeEventListener("click", handleClickOutside));
</script>
