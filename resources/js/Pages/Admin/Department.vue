<template>
    <NavAdmin />
    <main class="px-4 pt-20 pb-6 lg:ms-64 lg:pt-24">
        <!-- Search & Add Data -->
        <div class="flex items-center justify-between space-x-2 py-4">
            <label for="input-group-1" class="sr-only">Search</label>
            <div class="relative">
                <div
                    class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none"
                >
                    <MinimalisticMagnifierIcon
                        size="20"
                        class="text-gray-200"
                    />
                </div>
                <input
                    type="text"
                    id="input-group-1"
                    v-model="searchQuery"
                    class="block w-full h-10 ps-9 px-3 text-sm text-heading bg-white border border-default-medium rounded-sm shadow-xs placeholder:text-body focus:ring-brand focus:border-brand"
                    placeholder="Pencarian . . ."
                />
            </div>
            <button
                type="button"
                @click="openAddModal"
                class="inline-flex items-center justify-center gap-1.5 h-10 px-4 text-sm font-semibold text-white bg-primary-500 hover:bg-primary-600 border border-transparent rounded-sm shadow-xs focus:outline-none focus:ring-4 focus:ring-brand-medium active:scale-95 transition duration-200"
            >
                <AddIcon size="24" />
                <span>Tambah</span>
            </button>
        </div>

        <!-- Table Container -->
        <div
            class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs border border-default rounded-sm"
        >
            <table class="w-full text-sm text-left rtl:text-right text-body">
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
                        <th scope="col" class="px-6 py-3 font-medium">Aksi</th>
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
                                class="font-medium text-fg-brand hover:underline transition-all duration-200 ease-in-out active:scale-95 bg-blue-50 hover:bg-blue-100 rounded-full py-2 px-2"
                            >
                                <PenIcon size="24" />
                            </button>
                            <button
                                type="button"
                                @click="openDeleteModal(dept)"
                                class="font-medium text-fg-danger hover:underline transition-all duration-200 ease-in-out active:scale-95 bg-red-50 hover:bg-red-100 rounded-full py-2 px-2"
                            >
                                <TrashBinTrashIcon size="24" />
                            </button>
                        </td>
                    </tr>
                    <tr v-if="filteredDepartments.length === 0">
                        <td colspan="3" class="px-6 py-4 text-center text-body">
                            Tidak ada data yang cocok.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal tambah/edit -->
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="isModalOpen"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50"
                role="dialog"
                aria-modal="true"
                aria-labelledby="modal-title"
                @click.self="closeModal"
            >
                <div
                    class="relative w-full max-w-md bg-neutral-primary-soft rounded-sm border border-default shadow-lg p-5"
                >
                    <div class="flex justify-between items-center pb-2">
                        <h3
                            id="modal-title"
                            class="text-lg font-medium text-heading"
                        >
                            {{
                                editingId
                                    ? "Edit Departemen"
                                    : "Tambah Departemen"
                            }}
                        </h3>
                        <button
                            type="button"
                            @click="closeModal"
                            class="text-body bg-transparent hover:text-heading hover:bg-neutral-tertiary rounded-sm w-9 h-9 flex items-center justify-center ms-auto transition-all duration-200 ease-in-out active:scale-95"
                        >
                            <CloseIcon size="24" />
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>

                    <form @submit.prevent="handleSubmit">
                        <div class="grid gap-4 grid-cols-2 py-2">
                            <div class="col-span-2">
                                <label
                                    for="kode"
                                    class="block mb-2.5 text-sm font-medium text-heading"
                                >
                                    Kode Departemen
                                </label>
                                <input
                                    type="text"
                                    id="kode"
                                    v-model="form.department_code"
                                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-sm focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
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
                                >
                                    Nama Departemen
                                </label>
                                <input
                                    type="text"
                                    id="nama"
                                    v-model="form.department_name"
                                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-sm focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
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
                            class="flex justify-end items-center gap-3 border-default pt-4 mt-2"
                        >
                            <button
                                type="button"
                                @click="closeModal"
                                class="text-body bg-neutral-secondary-medium border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading shadow-xs font-medium leading-5 rounded-full text-sm px-4 py-3 focus:outline-none transition-all duration-200 ease-in-out active:scale-95"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="inline-flex items-center text-white bg-primary-500 hover:bg-primary-600 border border-transparent shadow-xs font-medium leading-5 rounded-full text-sm px-4 py-3 focus:outline-none transition-all duration-200 ease-in-out active:scale-95 disabled:opacity-60"
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
                        </div>
                    </form>
                </div>
            </div>
        </Transition>

        <!-- Modal konfirmasi hapus -->
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="isDeleteOpen"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50"
                role="alertdialog"
                aria-modal="true"
                aria-labelledby="delete-title"
                aria-describedby="delete-desc"
                @click.self="closeDeleteModal"
            >
                <div
                    class="relative w-full max-w-sm bg-neutral-primary-soft rounded-sm border border-default shadow-lg p-5 text-center"
                >
                    <div
                        class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-red-50 text-red-600"
                    >
                        <TrashBinTrashIcon size="28" />
                    </div>

                    <h3
                        id="delete-title"
                        class="text-lg font-semibold text-heading"
                    >
                        Hapus Departemen?
                    </h3>
                    <p id="delete-desc" class="mt-2 text-sm text-body">
                        Departemen
                        <span class="font-semibold text-heading">
                            {{ deleteTarget?.department_name }}
                        </span>
                        akan dihapus permanen dan tidak bisa dikembalikan.
                    </p>

                    <div class="mt-6 flex gap-3">
                        <button
                            type="button"
                            @click="closeDeleteModal"
                            :disabled="isDeleting"
                            class="flex-1 inline-flex items-center justify-center h-10 px-4 text-sm font-semibold text-body bg-neutral-secondary-medium border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading rounded-sm shadow-xs focus:outline-none transition duration-200 active:scale-95 disabled:opacity-60"
                        >
                            Batal
                        </button>
                        <button
                            type="button"
                            @click="confirmDelete"
                            :disabled="isDeleting"
                            class="flex-1 inline-flex items-center justify-center h-10 px-4 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 border border-transparent rounded-sm shadow-xs focus:outline-none focus:ring-4 focus:ring-red-200 transition duration-200 active:scale-95 disabled:opacity-60"
                        >
                            {{ isDeleting ? "Menghapus..." : "Ya, Hapus" }}
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </main>
</template>

<script setup>
import { computed, ref, onMounted, onBeforeUnmount } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import NavAdmin from "../../Layouts/NavAdmin.vue";
import { TrashBinTrashIcon } from "@solar-icons/vue/bold/trash-bin-trash";
import { PenIcon } from "@solar-icons/vue/bold/pen";
import { CloseIcon } from "@solar-icons/vue/bold/close";
import { MinimalisticMagnifierIcon } from "@solar-icons/vue/linear/minimalistic-magnifier";
import { AddIcon } from "@solar-icons/vue/bold/add";

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
const isModalOpen = ref(false);

const openAddModal = () => {
    editingId.value = null;
    form.reset();
    form.clearErrors();
    isModalOpen.value = true;
};

const openUpdateModal = (dept) => {
    editingId.value = dept.id;
    form.department_code = dept.department_code;
    form.department_name = dept.department_name;
    form.clearErrors();
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};

const handleSubmit = () => {
    const options = {
        onSuccess: () => {
            closeModal();
            form.reset();
        },
    };

    if (editingId.value) {
        form.put(`/admin/department/${editingId.value}`, options);
    } else {
        form.post("/admin/department", options);
    }
};

// tutup modal dengan tombol Escape
const onKeydown = (e) => {
    if (e.key !== "Escape") return;
    if (isDeleteOpen.value) closeDeleteModal();
    else if (isModalOpen.value) closeModal();
};

onMounted(() => window.addEventListener("keydown", onKeydown));
onBeforeUnmount(() => window.removeEventListener("keydown", onKeydown));

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

const isDeleteOpen = ref(false);
const isDeleting = ref(false);
const deleteTarget = ref(null);

const openDeleteModal = (dept) => {
    deleteTarget.value = dept;
    isDeleteOpen.value = true;
};

const closeDeleteModal = () => {
    if (isDeleting.value) return;
    isDeleteOpen.value = false;
};

const confirmDelete = () => {
    if (!deleteTarget.value) return;

    router.delete(`/admin/department/${deleteTarget.value.id}`, {
        preserveScroll: true,
        onStart: () => (isDeleting.value = true),
        onSuccess: () => (isDeleteOpen.value = false),
        onFinish: () => (isDeleting.value = false),
    });
};
</script>
