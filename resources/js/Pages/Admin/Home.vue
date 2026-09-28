<template>
    <AdminLayout>
        <main class="py-2">
            <div class="flex items-center gap-2.5">
                <img
                    :src="avatarUrl"
                    alt="avatar"
                    id="avatar"
                    class="w-12 h-12 rounded-full cursor-pointer ring-2 p-1 ring-green-500"
                />
                <!-- Dropdown menu -->
                <div
                    id="userDropdown"
                    class="z-10 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-44"
                >
                    <div
                        class="px-4 py-3 border-b border-default-medium text-sm text-heading"
                    >
                        <div class="font-medium">Bonnie Green</div>
                        <div class="truncate">name@flowbite.com</div>
                    </div>
                    <ul
                        class="p-2 text-sm text-body font-medium"
                        aria-labelledby="avatarButton"
                    >
                        <li>
                            <a
                                href="#"
                                class="block w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded-md"
                                >Dashboard</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="block w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded-md"
                                >Settings</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="block w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded-md"
                                >Earnings</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="block w-full p-2 hover:bg-neutral-tertiary-medium text-fg-danger rounded-md"
                                >Sign out</a
                            >
                        </li>
                    </ul>
                </div>
                <div class="flex flex-col">
                    <div
                        class="font-semibold text-lg text-heading leading-tight"
                    >
                        <div>Hallo, {{ user.name }}</div>
                        <div
                            class="text-sm font-normal text-body leading-tight"
                        >
                            {{
                                user.role === "department"
                                    ? user.department?.department_name
                                    : "Departemen IT Support"
                            }}
                        </div>
                        <span
                            class="inline-flex items-center gap-1 bg-brand-softer text-fg-brand-strong text-xs font-medium px-1.5 py-0.5 rounded"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="10"
                                height="10"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-star preview-icon shrink-0"
                            >
                                <path
                                    d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"
                                />
                            </svg>
                            17 Stars
                        </span>
                    </div>
                </div>
            </div>

            <div
                class="pt-6 pb-4 font-semibold text-lg text-heading leading-tight"
            >
                Riwayat Perbaikan
            </div>

            <ul role="list" class="max-w-md divide-y divide-default">
                <li class="">
                    <div
                        class="flex items-center space-x-3 rtl:space-x-reverse"
                    >
                        <div class="flex-1 min-w-0">
                            <p
                                class="text-sm font-semibold text-heading truncate"
                            >
                                Departemen PPIC
                            </p>
                            <p class="text-sm text-body truncate">
                                Ruangan P-1
                            </p>
                            <span
                                class="bg-warning-soft text-fg-danger text-xs font-medium px-1.5 py-0.5 rounded"
                                >Critical</span
                            >
                        </div>
                        <span
                            class="inline-flex items-center bg-success-soft border border-success-subtle text-fg-success-strong text-xs font-medium px-1.5 py-0.5 rounded-sm"
                        >
                            <span
                                class="w-2 h-2 me-1 bg-success rounded-full"
                            ></span>
                            Sudah Selesai
                        </span>
                    </div>
                </li>
                <li class="py-3 sm:py-4">
                    <div
                        class="flex items-center space-x-3 rtl:space-x-reverse"
                    >
                        <div class="flex-1 min-w-0">
                            <p
                                class="text-sm font-semibold text-heading truncate"
                            >
                                Departemen Finance
                            </p>
                            <p class="text-sm text-body truncate">
                                Ruangan F-2
                            </p>
                            <span
                                class="bg-warning-soft text-fg-warning text-xs font-medium px-1.5 py-0.5 rounded"
                                >High</span
                            >
                        </div>
                        <span
                            class="inline-flex items-center bg-danger-soft border border-danger-subtle text-fg-danger-strong text-xs font-medium px-1.5 py-0.5 rounded-sm"
                        >
                            <span
                                class="w-2 h-2 me-1 bg-danger rounded-full"
                            ></span>
                            Belum Diproses
                        </span>
                    </div>
                </li>
            </ul>
        </main>
    </AdminLayout>
</template>

<script setup>
import { computed, ref } from "vue";
import AdminLayout from "../../Layouts/AdminLayout.vue";
import { usePage } from "@inertiajs/vue3";
const randomSeed = ref(Math.random().toString(36).substring(2, 10));
const avatarUrl = ref(
    `https://api.dicebear.com/10.x/loops/svg?tags=animation&seed=${randomSeed.value}`,
);
const page = usePage();
const user = computed(() => page.props.auth.user);
</script>
