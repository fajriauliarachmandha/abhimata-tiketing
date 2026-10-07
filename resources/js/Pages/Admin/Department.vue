<template>
    <NavAdmin />

    <main class="px-4 pt-20 pb-6 lg:ms-64 lg:pt-24">
        <!-- ==================== PENCARIAN + TOMBOL TAMBAH ==================== -->
        <div class="flex items-center justify-between space-x-2 py-4">
            <label for="search" class="sr-only">Search</label>
            <div class="relative">
                <div
                    class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none"
                >
                    <MinimalisticMagnifierIcon
                        size="20"
                        class="text-gray-400"
                    />
                </div>
                <input
                    type="text"
                    id="search"
                    v-model="searchQuery"
                    class="block w-full h-10 ps-9 pe-3 text-sm text-heading bg-white border border-default-medium rounded-sm shadow-xs placeholder:text-body focus:ring-brand focus:border-brand"
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

        <!-- ==================== TABEL DEPARTEMEN ==================== -->
        <div
            class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs border border-default rounded-sm"
        >
            <table class="w-full text-sm text-left text-body">
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
                        v-for="department in filteredDepartments"
                        :key="department.id"
                        class="bg-neutral-primary-soft border-b border-default hover:bg-neutral-secondary-medium"
                    >
                        <td
                            class="px-6 py-4 font-semibold text-heading whitespace-nowrap"
                        >
                            {{ department.department_code }}
                        </td>
                        <td class="px-6 py-4">
                            {{ department.department_name }}
                        </td>
                        <td class="px-6 py-4 space-x-3 whitespace-nowrap">
                            <!-- Edit -->
                            <button
                                type="button"
                                @click="openEditModal(department)"
                                class="font-medium text-fg-brand transition-all duration-200 ease-in-out active:scale-95 bg-blue-50 hover:bg-blue-100 rounded-full p-2"
                            >
                                <PenIcon size="24" />
                            </button>
                            <!-- Hapus -->
                            <button
                                type="button"
                                @click="openDeleteModal(department)"
                                class="font-medium text-fg-danger transition-all duration-200 ease-in-out active:scale-95 bg-red-50 hover:bg-red-100 rounded-full p-2"
                            >
                                <TrashBinTrashIcon size="24" />
                            </button>
                        </td>
                    </tr>

                    <!-- Kalau hasil pencarian kosong -->
                    <tr v-if="filteredDepartments.length === 0">
                        <td colspan="3" class="px-6 py-4 text-center text-body">
                            Tidak ada data yang cocok.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- ==================== MODAL ==================== -->

        <!-- Modal 1: tambah / edit departemen (satu modal untuk dua mode) -->
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="isFormModalOpen"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50"
                role="dialog"
                aria-modal="true"
                aria-labelledby="form-modal-title"
                @click.self="closeFormModal"
            >
                <div
                    class="relative w-full max-w-md bg-neutral-primary-soft rounded-sm border border-default shadow-lg p-5"
                >
                    <!-- Judul + tombol tutup -->
                    <div class="flex justify-between items-center pb-2">
                        <h3
                            id="form-modal-title"
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
                            @click="closeFormModal"
                            class="text-body bg-transparent hover:text-heading hover:bg-neutral-tertiary rounded-sm w-9 h-9 flex items-center justify-center ms-auto transition-all duration-200 ease-in-out active:scale-95"
                        >
                            <CloseIcon size="24" />
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="submitDepartment">
                        <div class="space-y-4 py-2">
                            <div>
                                <label
                                    for="department-code"
                                    class="block mb-2.5 text-sm font-medium text-heading"
                                >
                                    Kode Departemen
                                </label>
                                <input
                                    type="text"
                                    id="department-code"
                                    v-model="departmentForm.department_code"
                                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-sm focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                                    required
                                />
                                <span
                                    v-if="departmentForm.errors.department_code"
                                    class="text-xs text-red-500 mt-1 block"
                                >
                                    {{ departmentForm.errors.department_code }}
                                </span>
                            </div>

                            <div>
                                <label
                                    for="department-name"
                                    class="block mb-2.5 text-sm font-medium text-heading"
                                >
                                    Nama Departemen
                                </label>
                                <input
                                    type="text"
                                    id="department-name"
                                    v-model="departmentForm.department_name"
                                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-sm focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                                    required
                                />
                                <span
                                    v-if="departmentForm.errors.department_name"
                                    class="text-xs text-red-500 mt-1 block"
                                >
                                    {{ departmentForm.errors.department_name }}
                                </span>
                            </div>
                        </div>

                        <!-- Tombol -->
                        <div
                            class="flex justify-end items-center gap-3 pt-4 mt-2"
                        >
                            <button
                                type="button"
                                @click="closeFormModal"
                                class="text-body bg-neutral-secondary-medium border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading shadow-xs font-medium leading-5 rounded-full text-sm px-4 py-3 focus:outline-none transition-all duration-200 ease-in-out active:scale-95"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                :disabled="departmentForm.processing"
                                class="inline-flex items-center text-white bg-primary-500 hover:bg-primary-600 border border-transparent shadow-xs font-medium leading-5 rounded-full text-sm px-4 py-3 focus:outline-none transition-all duration-200 ease-in-out active:scale-95 disabled:opacity-60"
                            >
                                {{ submitButtonText }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Transition>

        <!-- Modal 2: konfirmasi hapus -->
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="isDeleteModalOpen"
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
                            {{ departmentToDelete?.department_name }}
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
                            @click="deleteDepartment"
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
import { computed, ref, onMounted, onBeforeUnmount, watch } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import { AddIcon } from "@solar-icons/vue/bold/add";
import { CloseIcon } from "@solar-icons/vue/bold/close";
import { PenIcon } from "@solar-icons/vue/bold/pen";
import { TrashBinTrashIcon } from "@solar-icons/vue/bold/trash-bin-trash";
import { MinimalisticMagnifierIcon } from "@solar-icons/vue/linear/minimalistic-magnifier";
import NavAdmin from "../../Layouts/NavAdmin.vue";
import Swal from "sweetalert2";

// ==================== DATA DARI CONTROLLER ====================
const props = defineProps({
    departments: Array,
});

// ==================== NOTIFIKASI (SWEETALERT) ====================
const page = usePage();

const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
});

// Pesan dari controller (->with('success'/'error', ...)) tampil sebagai toast
watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.success)
            Toast.fire({ icon: "success", title: flash.success });
        if (flash?.error) Toast.fire({ icon: "error", title: flash.error });
    },
    { deep: true },
);

// ==================== PENCARIAN ====================
const searchQuery = ref("");

const filteredDepartments = computed(() => {
    if (!searchQuery.value.trim()) {
        return props.departments;
    }

    const keyword = searchQuery.value.toLowerCase();
    return props.departments.filter(
        (department) =>
            department.department_code.toLowerCase().includes(keyword) ||
            department.department_name.toLowerCase().includes(keyword),
    );
});

// ==================== TAMBAH & EDIT DEPARTEMEN ====================
// Satu modal dipakai untuk dua mode:
//   editingId = null    -> mode tambah
//   editingId = angka   -> mode edit (angka = id departemen yang diedit)
const isFormModalOpen = ref(false);
const editingId = ref(null);

const departmentForm = useForm({
    department_code: "",
    department_name: "",
});

const submitButtonText = computed(() => {
    if (departmentForm.processing) {
        return editingId.value ? "Updating..." : "Saving...";
    }
    return editingId.value ? "Update" : "Simpan";
});

const openAddModal = () => {
    editingId.value = null;
    departmentForm.reset();
    departmentForm.clearErrors();
    isFormModalOpen.value = true;
};

const openEditModal = (department) => {
    editingId.value = department.id;
    departmentForm.department_code = department.department_code;
    departmentForm.department_name = department.department_name;
    departmentForm.clearErrors();
    isFormModalOpen.value = true;
};

const closeFormModal = () => {
    isFormModalOpen.value = false;
};

const submitDepartment = () => {
    const isEdit = !!editingId.value;

    const options = {
        preserveScroll: true,
        onSuccess: () => {
            closeFormModal();
            departmentForm.reset();
            // toast sukses muncul otomatis lewat flash dari controller
        },
        onError: (errors) => {
            // modal tetap terbuka supaya data tidak hilang
            Swal.fire({
                icon: "error",
                title: isEdit
                    ? "Gagal memperbarui departemen"
                    : "Gagal menambahkan departemen",
                text:
                    Object.values(errors)[0] ??
                    "Periksa kembali data yang diisi.",
            });
        },
    };

    if (isEdit) {
        departmentForm.put(`/admin/department/${editingId.value}`, options);
    } else {
        departmentForm.post("/admin/department", options);
    }
};

// ==================== HAPUS DEPARTEMEN ====================
const isDeleteModalOpen = ref(false);
const isDeleting = ref(false);
const departmentToDelete = ref(null);

const openDeleteModal = (department) => {
    departmentToDelete.value = department;
    isDeleteModalOpen.value = true;
};

const closeDeleteModal = () => {
    if (isDeleting.value) return;
    isDeleteModalOpen.value = false;
};

const deleteDepartment = () => {
    if (!departmentToDelete.value) return;

    router.delete(`/admin/department/${departmentToDelete.value.id}`, {
        preserveScroll: true,
        onStart: () => (isDeleting.value = true),
        onSuccess: () => (isDeleteModalOpen.value = false),
        onFinish: () => (isDeleting.value = false),
    });
};

// ==================== TUTUP MODAL DENGAN TOMBOL ESCAPE ====================
const closeModalOnEscape = (event) => {
    if (event.key !== "Escape") return;

    if (isDeleteModalOpen.value) closeDeleteModal();
    else if (isFormModalOpen.value) closeFormModal();
};

onMounted(() => window.addEventListener("keydown", closeModalOnEscape));
onBeforeUnmount(() =>
    window.removeEventListener("keydown", closeModalOnEscape),
);
</script>
