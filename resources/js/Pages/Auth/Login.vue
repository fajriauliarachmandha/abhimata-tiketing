<template>
    <main class="bg-gray-50 px-4 md:px-8">
        <Transition
            mode="out-in"
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0 translate-x-2"
            enter-to-class="opacity-100 translate-x-0"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div class="min-h-screen flex flex-col items-center justify-center">
                <div class="max-w-md w-full">
                    <a href="#"
                        ><img
                            src="/public/abhimata-logo.webp"
                            alt="logo"
                            class="w-20 min-h-20 mb-4 mx-auto block"
                        />
                    </a>

                    <div
                        class="p-6 rounded-lg bg-white border border-slate-300 shadow-xs md:p-8"
                    >
                        <h1
                            class="text-slate-900 text-center text-3xl font-bold"
                        >
                            Login
                        </h1>

                        <form @submit.prevent="submit" class="space-y-6 mt-10">
                            <div>
                                <label
                                    for="email"
                                    class="mb-2 text-slate-900 font-medium text-sm inline-block"
                                    >Email</label
                                >
                                <input
                                    type="email"
                                    v-model="form.email"
                                    id="email"
                                    name="email"
                                    placeholder="Email"
                                    required
                                    class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-primary-600"
                                />
                                <span
                                    v-if="form.errors.email"
                                    class="text-red-500 text-sm block mt-1"
                                >
                                    {{ form.errors.email }}
                                </span>
                            </div>
                            <div>
                                <label
                                    for="password"
                                    class="mb-2 text-slate-900 font-medium text-sm inline-block"
                                    >Password</label
                                >
                                <input
                                    type="password"
                                    v-model="form.password"
                                    id="password"
                                    name="password"
                                    placeholder="••••••••"
                                    required
                                    class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-primary-600"
                                />
                            </div>

                            <button
                                :disabled="form.processing"
                                type="submit"
                                class="w-full py-2 px-3.5 text-sm rounded-full font-semibold cursor-pointer tracking-wide text-white bg-primary-500 hover:bg-primary-600 focus:ring-4 focus:ring-primary-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 transition-all duration-200 ease-in-out active:scale-95"
                            >
                                {{ form.processing ? "Masuk..." : "Masuk" }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </Transition>
    </main>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    email: "",
    password: "",
});

const submit = () => {
    form.post("/login");
};
</script>
