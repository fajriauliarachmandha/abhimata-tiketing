<template>
    <div class="max-w-md relative">
        <div
            class="fixed bottom-0 left-0 right-0 max-w-md mx-auto z-40 bg-neutral-primary-soft border-t border-default rounded-t-lg shadow-[0_-4px_6px_rgba(0,0,0,0.1)]"
        >
            <div class="w-full">
                <div
                    class="grid max-w-xs grid-cols-3 gap-1 p-1 mx-auto my-2 bg-neutral-tertiary rounded-base"
                    role="group"
                >
                    <Link
                        href="/admin/users"
                        class="px-5 py-1.5 text-xs font-medium rounded text-center transition-all duration-200 ease-in-out"
                        :class="
                            isActive('/admin/users')
                                ? 'bg-dark-strong text-white'
                                : 'text-body hover:bg-dark-strong hover:text-white'
                        "
                    >
                        Users
                    </Link>
                    <Link
                        href="/admin/department"
                        class="px-5 py-1.5 text-xs font-medium rounded text-center transition-all duration-200 ease-in-out"
                        :class="
                            isActive('/admin/department')
                                ? 'bg-dark-strong text-white'
                                : 'text-body hover:bg-dark-strong hover:text-white'
                        "
                    >
                        Departemen
                    </Link>
                    <Link
                        href="/admin/history"
                        class="px-5 py-1.5 text-xs font-medium rounded text-center transition-all duration-200 ease-in-out"
                        :class="
                            isActive('/admin/ticket')
                                ? 'bg-dark-strong text-white'
                                : 'text-body hover:bg-dark-strong hover:text-white'
                        "
                    >
                        Ticket
                    </Link>
                </div>
            </div>

            <div class="grid h-full max-w-lg grid-cols-4 mx-auto">
                <Link
                    href="/admin/home"
                    class="inline-flex flex-col items-center justify-center p-4 hover:bg-neutral-secondary-medium group"
                >
                    <HomeSmileAngleIcon
                        size="24"
                        class=""
                        :class="
                            isActive('/admin/home')
                                ? 'text-primary-500'
                                : 'text-body group-hover:text-primary-600'
                        "
                    />

                    <span class="sr-only">Home</span>
                </Link>

                <Link
                    href="/admin/ratings"
                    class="inline-flex flex-col items-center justify-center p-4 hover:bg-neutral-secondary-medium group"
                >
                    <StarIcon
                        size="24"
                        :class="
                            isActive('/admin/ratings')
                                ? 'text-primary-500'
                                : 'text-body group-hover:text-primary-600'
                        "
                    />

                    <span class="sr-only">Rating</span>
                </Link>

                <Link
                    href="/admin/history"
                    class="inline-flex flex-col items-center justify-center p-4 hover:bg-neutral-secondary-medium group"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="lucide lucide-rotate-ccw-clock preview-icon"
                        :class="
                            isActive('/admin/history')
                                ? 'text-primary-500'
                                : 'text-body group-hover:text-primary-600'
                        "
                    >
                        <path
                            d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"
                        />
                        <path d="M3 3v5h5" />
                        <path d="M12 7v5l4 2" />
                    </svg>
                    <span class="sr-only">Riwayat</span>
                </Link>

                <button
                    type="button"
                    @click="isModalOpen = true"
                    class="inline-flex flex-col items-center justify-center p-4 hover:bg-neutral-secondary-medium group"
                >
                    <Logout2Icon size="24" class="text-danger" />

                    <span class="sr-only">Log-out</span>
                </button>
            </div>
        </div>

        <Teleport to="body">
            <div
                v-if="isModalOpen"
                id="popup-modal"
                tabindex="-1"
                class="fixed inset-0 z-[100] flex justify-center items-center w-full h-full bg-black/50"
            >
                <div class="relative p-4 w-full max-w-md max-h-full">
                    <!-- Kotak Putih Modal -->
                    <div
                        class="relative bg-neutral-primary-soft border border-default rounded-base shadow-sm p-4 md:p-6"
                    >
                        <!-- Tombol Silang (X) -->
                        <button
                            type="button"
                            class="absolute top-3 end-2.5 text-body bg-transparent hover:bg-neutral-tertiary hover:text-heading rounded-base text-sm w-9 h-9 ms-auto inline-flex justify-center items-center"
                            @click="isModalOpen = false"
                        >
                            <svg
                                class="w-5 h-5"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18 17.94 6M18 18 6.06 6"
                                />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>

                        <!-- Isi Konten Modal -->
                        <div class="p-4 md:p-5 text-center">
                            <svg
                                class="mx-auto mb-4 text-fg-disabled w-12 h-12"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                />
                            </svg>
                            <h3 class="mb-6 text-body">
                                Anda yakin ingin keluar dari akun ini?
                            </h3>

                            <!-- Tombol Aksi -->
                            <div
                                class="flex items-center space-x-4 justify-center"
                            >
                                <button
                                    @click="handleLogout"
                                    type="button"
                                    class="text-white bg-danger box-border border border-transparent hover:bg-danger-strong focus:ring-4 focus:ring-danger-medium shadow-xs font-medium leading-5 rounded-full text-sm px-4 py-4 focus:outline-none"
                                >
                                    Ya, Keluar
                                </button>
                                <button
                                    @click="isModalOpen = false"
                                    type="button"
                                    class="text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-full text-sm px-4 py-4 focus:outline-none"
                                >
                                    Tidak, Batal
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Teleport>
    </div>
</template>

<script setup>
import { Link, router, usePage } from "@inertiajs/vue3";
import { ref } from "vue";

import { HomeSmileAngleIcon } from "@solar-icons/vue/bold/home-smile-angle";
import { StarIcon } from "@solar-icons/vue/bold/star";
import { Logout2Icon } from "@solar-icons/vue/bold/logout-2";

const page = usePage();

const isActive = (path) => page.url.startsWith(path);

const isModalOpen = ref(false);

const handleLogout = () => {
    router.post("/logout", {}, { onFinish: () => (isModalOpen.value = false) });
};
</script>
