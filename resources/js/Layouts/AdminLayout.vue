<template>
    <div
        class="min-h-screen bg-gray-100 flex justify-center font-sans text-gray-900"
    >
        <div
            class="w-full max-w-md lg:max-w-7xl bg-white min-h-screen shadow-2xl relative flex flex-col"
        >
            <TopNav />

            <main class="flex-1 px-4 pt-20 pb-32">
                <div class="overflow-hidden">
                    <Transition
                        mode="out-in"
                        enter-active-class="transition duration-200 ease-out"
                        enter-from-class="opacity-0 translate-x-2"
                        enter-to-class="opacity-100 translate-x-0"
                        leave-active-class="transition duration-150 ease-in"
                        leave-from-class="opacity-100"
                        leave-to-class="opacity-0"
                    >
                        <div :key="$page.url">
                            <slot />
                        </div>
                    </Transition>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import TopNav from "./TopNav.vue";
</script>
