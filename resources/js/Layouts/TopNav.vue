<template>
    <nav
        class="bg-neutral-primary fixed w-full z-20 top-0 start-0 border-b border-default"
    >
        <div
            class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4"
        >
            <Link
                href="/admin/dashboard"
                class="flex items-center space-x-3 rtl:space-x-reverse"
            >
                <span
                    class="self-center text-xl text-heading font-semibold whitespace-nowrap"
                >
                    E-Tiket
                </span>
            </Link>

            <div
                class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse"
            >
                <!-- User dropdown -->
                <div class="relative" ref="userMenuRef">
                    <button
                        type="button"
                        class="flex text-sm bg-neutral-primary rounded-full focus:ring-4 focus:ring-neutral-tertiary"
                        :aria-expanded="userMenuOpen"
                        @click="userMenuOpen = !userMenuOpen"
                    >
                        <span class="sr-only">Open user menu</span>
                        <img
                            class="w-8 h-8 rounded-full"
                            :src="avatarUrl"
                            alt="user photo"
                        />
                    </button>

                    <div
                        v-if="userMenuOpen"
                        class="absolute end-0 top-full mt-2 z-50 bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-44"
                    >
                        <div class="px-4 py-3 text-sm border-b border-default">
                            <span class="block text-heading font-medium">{{
                                user.name
                            }}</span>
                            <span class="block text-body truncate">{{
                                user.username
                            }}</span>
                        </div>
                        <ul class="p-2 text-sm text-body font-medium">
                            <li>
                                <Link
                                    href="/logout"
                                    method="post"
                                    as="button"
                                    class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded"
                                >
                                    Sign out
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Hamburger -->
                <button
                    type="button"
                    class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-body rounded-base md:hidden hover:bg-neutral-secondary-soft hover:text-heading focus:outline-none focus:ring-2 focus:ring-neutral-tertiary"
                    aria-controls="navbar-user"
                    :aria-expanded="mobileMenuOpen"
                    @click="mobileMenuOpen = !mobileMenuOpen"
                >
                    <span class="sr-only">Open main menu</span>
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
                            v-if="!mobileMenuOpen"
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
            </div>

            <!-- Menu: hidden di HP kecuali dibuka, selalu tampil di md ke atas -->
            <div
                id="navbar-user"
                class="items-center justify-between w-full md:flex md:w-auto md:order-1"
                :class="{ hidden: !mobileMenuOpen }"
            >
                <ul
                    class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-default rounded-base bg-neutral-secondary-soft md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-neutral-primary"
                >
                    <li v-for="item in menuItems" :key="item.href">
                        <Link
                            :href="item.href"
                            class="block py-2 px-3 rounded md:p-0"
                            :class="
                                isActive(item.href)
                                    ? 'text-white bg-brand md:bg-transparent md:text-fg-brand'
                                    : 'text-heading hover:bg-neutral-tertiary md:hover:bg-transparent md:hover:text-fg-brand'
                            "
                        >
                            {{ item.label }}
                        </Link>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { computed, ref, onMounted, onBeforeUnmount } from "vue";
import { Link, usePage, router } from "@inertiajs/vue3";

const page = usePage();
const user = computed(() => page.props.auth.user);

const avatarUrl = computed(
    () =>
        `https://api.dicebear.com/10.x/sprouts/svg?seed=${encodeURIComponent(user.value?.name ?? "user")}`,
);

const menuItems = [
    { label: "Home", href: "/admin/dashboard" },
    { label: "", href: "/admin/dashboard" },
    // tambahkan menu lain di sini
];
const isActive = (href) => page.url.startsWith(href);

const userMenuOpen = ref(false);
const mobileMenuOpen = ref(false);
const userMenuRef = ref(null);

const closeOnOutsideClick = (e) => {
    if (userMenuRef.value && !userMenuRef.value.contains(e.target)) {
        userMenuOpen.value = false;
    }
};

let removeNavigateListener;
onMounted(() => {
    document.addEventListener("click", closeOnOutsideClick);
    removeNavigateListener = router.on("navigate", () => {
        userMenuOpen.value = false;
        mobileMenuOpen.value = false;
    });
});
onBeforeUnmount(() => {
    document.removeEventListener("click", closeOnOutsideClick);
    removeNavigateListener?.();
});
</script>
t>
