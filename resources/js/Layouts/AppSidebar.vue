<template>
    <aside
        :class="[
            'fixed flex flex-col mt-0 top-0 px-5 start-0 bg-white dark:bg-gray-900 dark:border-gray-800 text-gray-900 h-screen transition-all duration-300 ease-in-out z-99999 border-e border-gray-200',
            {
                'xl:w-[290px]': isExpanded || isMobileOpen || isHovered,
                'xl:w-[90px]': !isExpanded && !isHovered,
                'translate-x-0 w-[290px]': isMobileOpen,
                'max-xl:-translate-x-full max-xl:rtl:translate-x-full':
                    !isMobileOpen,
                'xl:translate-x-0': true,
            },
        ]"
        @mouseenter="!isExpanded && (isHovered = true)"
        @mouseleave="isHovered = false"
    >
        <div
            :class="[
                'pt-8 pb-7 flex',
                !isExpanded && !isHovered
                    ? 'xl:justify-center'
                    : 'justify-start',
            ]"
        >
            <!-- PERBAIKAN 1: Tambahkan titik dua (:) pada href -->
            <Link :href="homePath" class="flex items-center gap-3">
                <img
                    src="/public/abhimata-logo.webp"
                    alt="Logo"
                    class="h-10 w-10 shrink-0"
                />
                <span
                    v-if="isExpanded || isHovered || isMobileOpen"
                    class="text-xl font-semibold text-gray-900"
                >
                    E-Tiket
                </span>
            </Link>
        </div>
        <div
            class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar"
        >
            <nav class="mb-6">
                <div class="flex flex-col gap-4">
                    <div
                        v-for="(menuGroup, groupIndex) in menuGroups"
                        :key="groupIndex"
                    >
                        <h2
                            :class="[
                                'mb-4 text-xs uppercase flex leading-5 text-gray-400',
                                !isExpanded && !isHovered
                                    ? 'xl:justify-center'
                                    : 'justify-start',
                            ]"
                        >
                            <template
                                v-if="isExpanded || isHovered || isMobileOpen"
                            >
                                {{ menuGroup.title }}
                            </template>
                            <HorizontalDots v-else />
                        </h2>
                        <ul class="flex flex-col gap-1">
                            <li
                                v-for="(item, index) in menuGroup.items"
                                :key="item.name"
                            >
                                <button
                                    v-if="item.subItems"
                                    @click="toggleSubmenu(groupIndex, index)"
                                    :class="[
                                        'menu-item group w-full',
                                        {
                                            'menu-item-active': isSubmenuOpen(
                                                groupIndex,
                                                index,
                                            ),
                                            'menu-item-inactive':
                                                !isSubmenuOpen(
                                                    groupIndex,
                                                    index,
                                                ),
                                        },
                                        !isExpanded && !isHovered
                                            ? 'xl:justify-center'
                                            : 'xl:justify-start',
                                    ]"
                                >
                                    <span
                                        :class="[
                                            isSubmenuOpen(groupIndex, index)
                                                ? 'menu-item-icon-active'
                                                : 'menu-item-icon-inactive',
                                        ]"
                                    >
                                        <component :is="item.icon" size="24" />
                                    </span>
                                    <span
                                        v-if="
                                            isExpanded ||
                                            isHovered ||
                                            isMobileOpen
                                        "
                                        class="menu-item-text truncate"
                                        >{{ item.name }}</span
                                    >
                                    <ChevronDownIcon
                                        v-if="
                                            isExpanded ||
                                            isHovered ||
                                            isMobileOpen
                                        "
                                        :class="[
                                            'ms-auto w-5 h-5 transition-transform duration-200',
                                            {
                                                'rotate-180 text-brand-500':
                                                    isSubmenuOpen(
                                                        groupIndex,
                                                        index,
                                                    ),
                                            },
                                        ]"
                                    />
                                </button>
                                <Link
                                    v-else-if="item.path"
                                    :href="item.path"
                                    :class="[
                                        'menu-item group',
                                        {
                                            'menu-item-active': isActive(
                                                item.path,
                                            ),
                                            'menu-item-inactive': !isActive(
                                                item.path,
                                            ),
                                        },
                                        !isExpanded && !isHovered
                                            ? 'xl:justify-center'
                                            : 'xl:justify-start',
                                    ]"
                                >
                                    <span
                                        :class="[
                                            isActive(item.path)
                                                ? 'menu-item-icon-active'
                                                : 'menu-item-icon-inactive',
                                        ]"
                                    >
                                        <component :is="item.icon" size="24" />
                                    </span>
                                    <span
                                        v-if="
                                            isExpanded ||
                                            isHovered ||
                                            isMobileOpen
                                        "
                                        class="menu-item-text"
                                        >{{ item.name }}</span
                                    >
                                </Link>
                                <transition
                                    @enter="startTransition"
                                    @after-enter="endTransition"
                                    @before-leave="startTransition"
                                    @after-leave="endTransition"
                                >
                                    <div
                                        v-show="
                                            isSubmenuOpen(groupIndex, index) &&
                                            (isExpanded ||
                                                isHovered ||
                                                isMobileOpen)
                                        "
                                    >
                                        <ul class="mt-2 space-y-1 ms-9">
                                            <li
                                                v-for="subItem in item.subItems"
                                                :key="subItem.name"
                                            >
                                                <Link
                                                    :href="subItem.path"
                                                    :class="[
                                                        'menu-dropdown-item',
                                                        {
                                                            'menu-dropdown-item-active':
                                                                isActive(
                                                                    subItem.path,
                                                                ),
                                                            'menu-dropdown-item-inactive':
                                                                !isActive(
                                                                    subItem.path,
                                                                ),
                                                        },
                                                    ]"
                                                >
                                                    {{ subItem.name }}
                                                </Link>
                                            </li>
                                        </ul>
                                    </div>
                                </transition>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Sign Out -->
            <Link
                href="/logout"
                method="post"
                as="button"
                :class="[
                    'menu-item group mb-6 text-error-600 hover:bg-error-50',
                    !isExpanded && !isHovered
                        ? 'xl:justify-center'
                        : 'xl:justify-start',
                ]"
            >
                <span class="text-error-500">
                    <Logout2Icon size="24" />
                </span>
                <span
                    v-if="isExpanded || isHovered || isMobileOpen"
                    class="menu-item-text"
                    >Sign Out</span
                >
            </Link>
        </div>
    </aside>
</template>

<script setup lang="ts">
import { watch, computed } from "vue"; // Pastikan computed di-import
import { Link, usePage } from "@inertiajs/vue3";

import { useSidebar } from "@/composables/useSidebar";
import { ChevronDownIcon, HorizontalDots } from "@/icons";
import { HomeIcon } from "@solar-icons/vue/bold/home";
import { UsersGroupRoundedIcon } from "@solar-icons/vue/bold/users-group-rounded";
import { BuildingsIcon } from "@solar-icons/vue/bold/buildings";
import { TicketIcon } from "@solar-icons/vue/bold/ticket";
import { Logout2Icon } from "@solar-icons/vue/bold/logout-2";

const page = usePage();
const { isExpanded, isMobileOpen, isHovered, openSubmenu } = useSidebar();

interface SubItem {
    name: string;
    path: string;
}

interface MenuItem {
    icon?: any;
    name: string;
    path?: string;
    subItems?: SubItem[];
}

interface MenuGroup {
    title: string;
    items: MenuItem[];
}

// --------------------------------------------------------
// DEKLARASI MENU BERDASARKAN ROLE
// --------------------------------------------------------

// Ambil role dari page props Inertia
const role = computed(() => (page.props as any).auth?.user?.role);

// 1. Menu untuk IT Support (admin)
const adminMenuGroups: MenuGroup[] = [
    {
        title: "Menu",
        items: [
            { icon: HomeIcon, name: "Home", path: "/admin/home" },
            {
                icon: UsersGroupRoundedIcon,
                name: "Data Pengguna",
                path: "/admin/users",
            },
            {
                icon: BuildingsIcon,
                name: "Data Departemen",
                path: "/admin/department",
            },
            { icon: TicketIcon, name: "Data Tiket", path: "/admin/history" },
        ],
    },
];

// 2. Menu untuk user departemen
const userMenuGroups: MenuGroup[] = [
    {
        title: "Menu",
        items: [
            { icon: HomeIcon, name: "Home", path: "/user/home" },
            {
                icon: TicketIcon,
                name: "Buat Tiket",
                path: "/user/ticket",
            },
        ],
    },
];

// 3. Gabungkan menu berdasarkan role (Satu-satunya deklarasi menuGroups)
const menuGroups = computed(() =>
    role.value === "it_support" ? adminMenuGroups : userMenuGroups,
);

// 4. Link logo ikut role
const homePath = computed(() =>
    role.value === "it_support" ? "/admin/home" : "/user/home",
);

// --------------------------------------------------------
// LOGIKA SIDEBAR ACTIVE & TRANSITION
// --------------------------------------------------------

const isActive = (path?: string) => (path ? page.url.startsWith(path) : false);

const setActiveMenuFromRoute = () => {
    menuGroups.value.forEach((group, groupIndex) => {
        // PERBAIKAN 2: group.items.items sebelumnya salah, diubah jadi group.items
        group.items.forEach((item, itemIndex) => {
            if (item.subItems?.some((subItem) => isActive(subItem.path))) {
                openSubmenu.value = `${groupIndex}-${itemIndex}`;
            }
        });
    });
};

watch(
    () => page.url,
    () => {
        setActiveMenuFromRoute();
    },
    { immediate: true },
);

const toggleSubmenu = (groupIndex: number, itemIndex: number) => {
    const key = `${groupIndex}-${itemIndex}`;
    openSubmenu.value = openSubmenu.value === key ? null : key;
};

const isSubmenuOpen = (groupIndex: number, itemIndex: number) => {
    const key = `${groupIndex}-${itemIndex}`;
    return openSubmenu.value === key;
};

const startTransition = (el: Element) => {
    const htmlEl = el as HTMLElement;
    htmlEl.style.height = "auto";
    const height = htmlEl.scrollHeight;
    htmlEl.style.height = "0px";
    void htmlEl.offsetHeight; // force reflow
    htmlEl.style.height = height + "px";
};

const endTransition = (el: Element) => {
    const htmlEl = el as HTMLElement;
    htmlEl.style.height = "";
};
</script>
