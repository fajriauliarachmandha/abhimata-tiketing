<template>
    <div
        class="min-h-screen bg-gray-100 flex justify-center font-sans text-gray-900"
    >
        <div
            class="w-full max-w-md bg-white min-h-screen shadow-2xl relative flex flex-col"
        >
            <main
                class="flex-1 p-4 flex flex-col items-center justify-center overflow-hidden"
            >
                <Transition
                    mode="out-in"
                    enter-active-class="transition duration-200 ease-out"
                    enter-from-class="opacity-0 translate-x-2"
                    enter-to-class="opacity-100 translate-x-0"
                    leave-active-class="transition duration-150 ease-in"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div
                        :key="$page.url"
                        class="flex flex-col items-center w-full"
                    >
                        <img
                            class="h-44 w-44"
                            src="/public/abhimata-logo.webp"
                            alt="image description"
                        />
                        <form
                            @submit.prevent="submit"
                            class="max-w-sm mx-auto w-full py-6"
                        >
                            <label
                                for="input-group-1"
                                class="block mb-2.5 text-sm font-medium text-heading"
                                >Username</label
                            >
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="20"
                                        height="20"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="#000"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-circle-user-round preview-icon"
                                    >
                                        <path
                                            d="M17.925 20.056a6 6 0 0 0-11.851.001"
                                        />
                                        <circle cx="12" cy="11" r="4" />
                                        <circle cx="12" cy="12" r="10" />
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    id="input-group-1"
                                    v-model="form.username"
                                    class="block w-full ps-9 pe-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body"
                                    placeholder="Username . . ."
                                />
                            </div>
                            <span
                                v-if="form.errors.username"
                                class="text-red-500 text-sm block mt-1"
                            >
                                {{ form.errors.username }}
                            </span>

                            <label
                                for="input-group-1"
                                class="block mb-2.5 text-sm font-medium text-heading"
                                >Password</label
                            >
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="20"
                                        height="20"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="#000"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-eye preview-icon"
                                    >
                                        <path
                                            d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"
                                        />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </div>
                                <input
                                    type="password"
                                    id="input-group-1"
                                    v-model="form.password"
                                    class="block w-full ps-9 pe-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body"
                                    placeholder="Password . . ."
                                />
                            </div>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none w-full mt-5"
                            >
                                {{ form.processing ? "Masuk..." : "Masuk" }}
                            </button>
                        </form>
                    </div>
                </Transition>
            </main>
        </div>
    </div>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    username: "",
    password: "",
});

const submit = () => {
    form.post("/login");
};
</script>
