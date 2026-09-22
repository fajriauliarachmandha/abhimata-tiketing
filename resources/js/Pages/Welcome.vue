<template>
    <MobileLayout></MobileLayout>
    <main class="p-6">
        <div class="flex items-center gap-2.5 justify-between">
            <div class="font-medium text-heading">
                <div>Hallo, { user }</div>
                <div class="text-sm font-normal text-body">
                    Departemen IT Support
                </div>
            </div>

            <img
                id="avatarButton"
                type="button"
                data-dropdown-toggle="userDropdown"
                data-dropdown-placement="bottom-start"
                class="w-10 h-10 rounded-full cursor-pointer"
                src="https://flowbite.com/docs/images/people/profile-picture-5.jpg"
                alt="User dropdown"
            />

            <!-- Dropdown menu -->
            <div
                id="userDropdown"
                class="z-10 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-44"
            >
                <ul
                    class="p-2 text-sm text-body font-medium"
                    aria-labelledby="avatarButton"
                >
                    <li>
                        <a
                            href="#"
                            class="block w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded-md"
                            >Profile</a
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
        </div>
        <button
            type="button"
            class="inline-flex items-center text-white bg-warning box-border border border-transparent hover:bg-warning-strong focus:ring-4 focus:ring-warning-medium shadow-xs font-medium leading-5 rounded-full text-sm px-4 py-2.5 focus:outline-none w-full my-5"
        >
            Ajukan Perbaikan
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
                class="lucide lucide-wrench preview-icon"
            >
                <path
                    d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.106-3.105c.32-.322.863-.22.983.218a6 6 0 0 1-8.259 7.057l-7.91 7.91a1 1 0 0 1-2.999-3l7.91-7.91a6 6 0 0 1 7.057-8.259c.438.12.54.662.219.984z"
                />
            </svg>
        </button>

        <ul role="list" class="max-w-md divide-y divide-default">
            <li class="py-3 sm:py-4">
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-heading truncate">
                            Departemen PPIC
                        </p>
                        <p class="text-sm text-body truncate">Ruangan P-1</p>
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
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-heading truncate">
                            Departemen Finance
                        </p>
                        <p class="text-sm text-body truncate">Ruangan F-2</p>
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
</template>

<script setup>
import MobileLayout from "@/Layouts/MobileLayout.vue";
</script>
