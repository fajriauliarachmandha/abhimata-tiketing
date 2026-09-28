<template>
    <div class="max-w-md relative">
        <div
            class="fixed bottom-0 left-0 right-0 max-w-md mx-auto z-40 bg-neutral-primary-soft border-t border-default rounded-t-lg shadow-[0_-4px_6px_rgba(0,0,0,0.1)]"
        >
            <div class="w-full">
                <div
                    class="grid max-w-xs grid-cols-2 gap-1 p-1 mx-auto my-2 bg-neutral-tertiary rounded-base"
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
                </div>
            </div>

            <div class="grid h-full max-w-lg grid-cols-4 mx-auto">
                <Link
                    href="/admin/home"
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
                        class="lucide lucide-house preview-icon"
                        :class="
                            isActive('/admin/home')
                                ? 'text-primary-500'
                                : 'text-body group-hover:text-primary-600'
                        "
                    >
                        <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
                        <path
                            d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"
                        />
                    </svg>
                    <span class="sr-only">Home</span>
                </Link>

                <Link
                    href="/admin/ratings"
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
                        class="lucide lucide-star preview-icon"
                        :class="
                            isActive('/admin/ratings')
                                ? 'text-primary-500'
                                : 'text-body group-hover:text-primary-600'
                        "
                    >
                        <path
                            d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"
                        />
                    </svg>
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
                        class="lucide lucide-log-out preview-icon text-danger"
                    >
                        <path d="m16 17 5-5-5-5" />
                        <path d="M21 12H9" />
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                    </svg>

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

const page = usePage();

const isActive = (path) => page.url.startsWith(path);

const isModalOpen = ref(false);

const handleLogout = () => {
    router.post("/logout", {}, { onFinish: () => (isModalOpen.value = false) });
};
</script>
