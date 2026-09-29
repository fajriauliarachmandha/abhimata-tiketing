<template>
    <AdminLayout>
        <main class="py-6">
            <!-- Search & Add Data -->
            <div class="flex items-center justify-between space-x-2 py-4">
                <label for="input-group-1" class="sr-only">Search</label>
                <div class="relative">
                    <div
                        class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none"
                    >
                        <svg
                            class="w-4 h-4 text-body"
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
                                stroke-width="2"
                                d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"
                            />
                        </svg>
                    </div>
                    <input
                        type="text"
                        id="input-group-1"
                        v-model="searchQuery"
                        class="block w-full max-w-96 ps-9 pe-3 py-4 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body"
                        placeholder="Pencarian . . ."
                    />
                </div>
                <button
                    type="button"
                    @click="openAddModal"
                    class="inline-flex items-center justify-center gap-2 shrink-0 text-white bg-primary-500 border border-transparent shadow-xs font-medium text-sm leading-5 rounded-full px-4 py-4 hover:bg-primary-600 focus:outline-none focus:ring-4 focus:ring-brand-medium active:scale-95 transition duration-200 ease-in-out"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M10 12h4" />
                        <path d="M10 21v-3a2 2 0 013.05-1.702" />
                        <path d="M10 8h4" />
                        <path d="M16 19h6" />
                        <path d="M18 7h2a2 2 0 012 2v4.355" />
                        <path d="M19 16v6" />
                        <path d="M6 10H4a2 2 0 00-2 2v7a2 2 0 002 2h8.535" />
                        <path d="M6 21V5a2 2 0 012-2h8a2 2 0 012 2v7.126" />
                    </svg>
                    <span>Tambah</span>
                </button>
            </div>

            <!-- Table Container -->
            <div
                class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default"
            >
                <table
                    class="w-full text-sm text-left rtl:text-right text-body"
                >
                    <thead
                        class="text-sm text-body bg-neutral-secondary-medium border-b border-t border-default-medium"
                    >
                        <tr>
                            <th scope="col" class="px-6 py-3 font-medium">
                                Kode Departemen
                            </th>
                            <th scope="col" class="px-6 py-3 font-medium">
                                Departemen
                            </th>
                            <th scope="col" class="px-6 py-3 font-medium">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="dept in filteredDepartments"
                            :key="dept.id"
                            class="bg-neutral-primary-soft border-b border-default hover:bg-neutral-secondary-medium"
                        >
                            <td
                                class="px-6 py-4 font-semibold text-heading whitespace-nowrap"
                            >
                                {{ dept.department_code }}
                            </td>
                            <td class="px-6 py-4">
                                {{ dept.department_name }}
                            </td>
                            <td class="px-6 py-4 space-x-3">
                                <button
                                    type="button"
                                    @click="openUpdateModal(dept)"
                                    class="font-medium text-fg-brand hover:underline"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="18"
                                        height="18"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="#1447E9"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-square-pen preview-icon"
                                    >
                                        <path
                                            d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"
                                        />
                                        <path
                                            d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"
                                        />
                                    </svg>
                                </button>
                                <button
                                    type="button"
                                    @click="handleDelete(dept)"
                                    class="font-medium text-fg-danger hover:underline"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="18"
                                        height="18"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="#C70036"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-trash preview-icon"
                                    >
                                        <path d="M10 11v6" />
                                        <path d="M14 11v6" />
                                        <path
                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"
                                        />
                                        <path d="M3 6h18" />
                                        <path
                                            d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"
                                        />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        <tr v-if="filteredDepartments.length === 0">
                            <td
                                colspan="3"
                                class="px-6 py-4 text-center text-body"
                            >
                                Tidak ada data yang cocok.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- drawer component -->
            <Transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0 scale-95"
                enter-to-class="opacity-100 scale-100"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100 scale-100"
                leave-to-class="opacity-0 scale-95"
            >
                <div
                    v-if="isDrawerOpen"
                    id="drawer-bottom-example"
                    class="fixed bottom-0 inset-x-0 mx-auto z-[100] w-full max-w-md px-4 pb-4 pt-3 overflow-y-auto transition-transform bg-neutral-primary-soft transform-none rounded-t-2xl shadow-[0_-10px_40px_rgba(0,0,0,0.1)] border-t border-default"
                    tabindex="-1"
                    aria-labelledby="drawer-bottom-label"
                >
                    <div class="flex justify-between pb-2 items-center">
                        <h3 class="text-lg font-medium text-heading">
                            {{
                                editingId
                                    ? "Edit Departemen"
                                    : "Tambah Departemen"
                            }}
                        </h3>
                        <button
                            type="button"
                            @click="isDrawerOpen = false"
                            class="text-body bg-transparent hover:text-heading hover:bg-neutral-tertiary rounded-base w-9 h-9 flex items-center justify-center ms-auto transition-all duration-200 ease-in-out hover:scale-105 active:scale-95"
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
                            <span class="sr-only">Close menu</span>
                        </button>
                    </div>
                    <form @submit.prevent="handleSubmit">
                        <div class="grid gap-4 grid-cols-2 py-2">
                            <div class="col-span-2">
                                <label
                                    for="kode"
                                    class="block mb-2.5 text-sm font-medium text-heading"
                                    >Kode Departemen</label
                                >
                                <input
                                    type="text"
                                    id="kode"
                                    v-model="form.department_code"
                                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                                    required
                                />
                                <span
                                    v-if="form.errors.department_code"
                                    class="text-xs text-red-500 mt-1 block"
                                >
                                    {{ form.errors.department_code }}
                                </span>
                            </div>
                            <div class="col-span-2">
                                <label
                                    for="nama"
                                    class="block mb-2.5 text-sm font-medium text-heading"
                                    >Nama Departemen</label
                                >
                                <input
                                    type="text"
                                    id="nama"
                                    v-model="form.department_name"
                                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                                    required
                                />
                                <span
                                    v-if="form.errors.department_name"
                                    class="text-xs text-red-500 mt-1 block"
                                >
                                    {{ form.errors.department_name }}
                                </span>
                            </div>
                        </div>
                        <div
                            class="flex justify-end items-center space-x-4 border-t border-default pt-4 md:pt-6"
                        >
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="inline-flex items-center text-white bg-primary-500 hover:bg-primary-600 box-border border border-transparent focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-full text-sm px-4 py-4 focus:outline-none transition-all duration-200 ease-in-out hover:scale-105 active:scale-95 mt-2"
                            >
                                {{
                                    form.processing
                                        ? editingId
                                            ? "Updating..."
                                            : "Saving..."
                                        : editingId
                                          ? "Update"
                                          : "Simpan"
                                }}
                            </button>
                            <button
                                type="button"
                                @click="isDrawerOpen = false"
                                class="text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-full text-sm px-4 py-4 focus:outline-none transition-all duration-200 ease-in-out hover:scale-105 active:scale-95"
                            >
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </Transition>
        </main>
    </AdminLayout>
</template>

<script setup>
import { computed, ref } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import AdminLayout from "../../Layouts/AdminLayout.vue";

const props = defineProps({
    departments: Array,
});

const searchQuery = ref("");

// Form Tambah dan Edit
const form = useForm({
    department_code: "",
    department_name: "",
});

const editingId = ref(null);
const isDrawerOpen = ref(false);

const openAddModal = () => {
    editingId.value = null;
    form.reset();
    form.clearErrors();
    isDrawerOpen.value = true;
};

const openUpdateModal = (dept) => {
    editingId.value = dept.id;
    form.department_code = dept.department_code;
    form.department_name = dept.department_name;
    form.clearErrors();
    isDrawerOpen.value = true;
};

const handleSubmit = () => {
    if (editingId.value) {
        form.put(`/admin/department/${editingId.value}`, {
            onSuccess: () => {
                isDrawerOpen.value = false;
                form.reset();
            },
        });
    } else {
        form.post("/admin/department", {
            onSuccess: () => {
                isDrawerOpen.value = false;
                form.reset();
            },
        });
    }
};

const handleDelete = (dept) => {
    if (
        !confirm(`Yakin ingin menghapus departemen "${dept.department_name}"?`)
    ) {
        return;
    }

    router.delete(`/admin/department/${dept.id}`, {
        preserveScroll: true,
    });
};

// search
const filteredDepartments = computed(() => {
    if (!searchQuery.value.trim()) {
        return props.departments;
    }
    const q = searchQuery.value.toLowerCase();
    return props.departments.filter(
        (dept) =>
            dept.department_code.toLowerCase().includes(q) ||
            dept.department_name.toLowerCase().includes(q),
    );
});
</script>
