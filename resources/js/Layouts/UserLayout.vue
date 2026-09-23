<template>
    <div
        class="min-h-screen bg-gray-100 flex justify-center font-sans text-gray-900"
    >
        <div
            class="w-full max-w-md bg-white min-h-screen shadow-2xl relative flex flex-col"
        >
            <main class="flex-1 p-4 pb-20">
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
</script>
