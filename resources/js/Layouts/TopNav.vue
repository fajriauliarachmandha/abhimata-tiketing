<template>
    <!-- Top navbar -->
    <nav
        class="bg-neutral-primary fixed w-full z-50 top-0 start-0 border-b border-default"
    >
        <div class="h-[72px] px-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <!-- Hamburger: hanya di HP, membuka sidebar -->
                <button
                    type="button"
                    class="lg:hidden inline-flex items-center justify-center w-10 h-10 text-body rounded-base hover:bg-neutral-secondary-soft hover:text-heading focus:outline-none focus:ring-2 focus:ring-neutral-tertiary"
                    aria-controls="default-sidebar"
                    :aria-expanded="sidebarOpen"
                    @click="sidebarOpen = !sidebarOpen"
                >
                    <span class="sr-only">Open sidebar</span>
                    <svg
                        class="w-6 h-6"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <path
                            v-if="!sidebarOpen"
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-width="2"
                            d="M5 7h14M5 12h14M5 17h14"
                        />
                        <path
                            v-else
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-width="2"
                            d="M6 6l12 12M18 6L6 18"
                        />
                    </svg>
                </button>

                <Link
                    href="/admin/dashboard"
                    class="text-xl text-heading font-semibold whitespace-nowrap"
                >
                    <img
                        src="/public/abhimata-logo.webp"
                        alt="logo"
                        class="w-10 h-10 inline-block me-2"
                    />
                    E-Tiket
                </Link>
            </div>
        </div>
    </nav>

    <!-- Latar gelap, hanya di HP saat sidebar terbuka -->
    <div
        v-if="sidebarOpen"
        class="fixed inset-0 z-30 bg-black/40 lg:hidden"
        @click="sidebarOpen = false"
    ></div>

    <!-- Sidebar: mulai di bawah navbar -->
    <aside
        id="default-sidebar"
        class="fixed top-[72px] bottom-0 start-0 z-40 w-64 transition-transform duration-200 lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        aria-label="Sidebar"
    >
        <div
            class="h-full px-3 py-4 overflow-y-auto bg-neutral-primary-soft border-e border-default"
        >
            <ul class="space-y-2 font-medium">
                <li v-for="item in menuItems" :key="item.href">
                    <Link
                        :href="item.href"
                        :method="item.method ?? 'get'"
                        :as="item.method ? 'button' : 'a'"
                        class="flex items-center w-full px-2 py-1.5 rounded-sm text-start"
                        :class="
                            isActive(item.href)
                                ? 'bg-neutral-tertiary text-fg-brand'
                                : item.danger
                                  ? 'text-fg-danger hover:bg-danger-soft'
                                  : 'text-body hover:bg-neutral-tertiary hover:text-fg-brand'
                        "
                    >
                        <component :is="item.icon" size="24" class="shrink-0" />
                        <span class="ms-3">{{ item.label }}</span>
                    </Link>
                </li>
            </ul>
        </div>
    </aside>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import { Link, usePage, router } from "@inertiajs/vue3";
import { HomeIcon } from "@solar-icons/vue/bold/home";
import { UsersGroupRoundedIcon } from "@solar-icons/vue/bold/users-group-rounded";
import { BuildingsIcon } from "@solar-icons/vue/bold/buildings";
import { Logout2Icon } from "@solar-icons/vue/bold/logout-2";
import { TicketIcon } from "@solar-icons/vue/bold/ticket";

const page = usePage();

// Menu sidebar. Tambah item baru di sini.
const menuItems = [
    { label: "Home", href: "/admin/home", icon: HomeIcon },
    {
        label: "Data Pengguna",
        href: "/admin/users",
        icon: UsersGroupRoundedIcon,
    },
    {
        label: "Data Departemen",
        href: "/admin/department",
        icon: BuildingsIcon,
    },
    {
        label: "Data Tiket",
        href: "/admin/history",
        icon: TicketIcon,
    },
    {
        label: "Sign Out",
        href: "/logout",
        method: "post",
        icon: Logout2Icon,
        danger: true,
    },
];

const isActive = (href) => page.url.startsWith(href);

const userMenuOpen = ref(false);
const sidebarOpen = ref(false);
const userMenuRef = ref(null);

const closeOnOutsideClick = (e) => {
    if (userMenuRef.value && !userMenuRef.value.contains(e.target)) {
        userMenuOpen.value = false;
    }
};

let removeNavigateListener;
onMounted(() => {
    document.addEventListener("click", closeOnOutsideClick);
    // tutup dropdown dan sidebar setiap pindah halaman
    removeNavigateListener = router.on("navigate", () => {
        userMenuOpen.value = false;
        sidebarOpen.value = false;
    });
});
onBeforeUnmount(() => {
    document.removeEventListener("click", closeOnOutsideClick);
    removeNavigateListener?.();
});
</script>
