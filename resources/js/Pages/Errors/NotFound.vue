<template>
    <main
        class="flex items-center justify-center w-full min-h-screen py-8 text-gray-900 md:py-16"
    >
        <div
            class="relative flex flex-col items-center w-full gap-8 px-8 md:px-18 xl:px-40 md:gap-16"
        >
            <h1
                class="text-9xl md:text-[300px] w-full select-none text-center font-black text-gray-400"
            >
                404
            </h1>
            <p class="text-3xl font-bold text-center">
                Halaman tidak ditemukan
            </p>
            <p
                class="text-2xl font-medium text-center text-gray-500 break-words"
            >
                Alamat yang kamu buka tidak ada, atau halamannya sudah
                dipindahkan. Periksa kembali alamatnya.
            </p>

            <div
                class="flex flex-col justify-between w-full gap-8 md:flex-row md:gap-32 xl:px-16"
            >
                <!-- Kembali ke halaman sebelumnya -->
                <button
                    type="button"
                    @click="goBack"
                    class="flex items-center justify-center w-full gap-4 p-3 font-semibold border-2 border-blue-500 rounded shadow-lg md:w-fit hover:bg-blue-500 hover:text-white md:p-6 focus:outline-none hover:scale-105 active:scale-90 hover:shadow-xl"
                >
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
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 12H5m0 0 4-4m-4 4 4 4"
                        />
                    </svg>
                    Halaman Sebelumnya
                </button>

                <!-- Ke beranda -->
                <Link
                    :href="homePath"
                    class="flex items-center justify-center w-full gap-4 p-3 font-semibold border-2 border-green-500 rounded shadow-lg md:w-fit hover:bg-green-500 hover:text-white md:p-6 focus:outline-none hover:scale-105 active:scale-90 hover:shadow-xl"
                >
                    <HomeIcon size="24" />
                    Ke Beranda
                </Link>
            </div>
        </div>
    </main>
</template>

<script setup>
import { Link, router, usePage } from "@inertiajs/vue3";
import { HomeIcon } from "@solar-icons/vue/bold/home";
import { computed } from "vue";

// Kalau tidak ada halaman sebelumnya (misalnya URL diketik langsung), ke beranda
const goBack = () => {
    if (window.history.length > 1) {
        window.history.back();
    } else {
        router.visit("/");
    }
};

const page = usePage();

// Tujuan tombol beranda menurut role. Kalau belum login / data auth tidak ada, ke halaman login.
const homePath = computed(() => {
    const role = page.props.auth?.user?.role;
    if (role === "it_support") return "/admin/home";
    if (role === "department") return "/user/home";
    return "/login";
});
</script>
