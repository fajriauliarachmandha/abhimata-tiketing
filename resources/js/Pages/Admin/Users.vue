<template>
    <AdminLayout>
        <main>
            <!-- ==================== BAGIAN ATAS: pencarian + tombol tambah ==================== -->
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
                        class="block w-full h-10 ps-9 pe-3 text-sm text-heading bg-white border border-default-medium rounded-sm shadow-xs placeholder:text-body focus:ring-brand focus:border-brand dark:bg-gray-900 dark:text-white dark:border-gray-700 dark:placeholder:text-gray-500"
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

            <!-- ==================== TABEL PENGGUNA ==================== -->
            <div
                class="relative max-w-full overflow-x-auto bg-neutral-primary-soft shadow-xs border border-default rounded-sm"
            >
                <table
                    class="w-full min-w-[720px] text-sm text-left rtl:text-right text-body"
                >
                    <thead
                        class="text-sm text-body bg-neutral-secondary-medium border-b border-t border-default-medium"
                    >
                        <tr>
                            <!-- Judul kolom dibuat dari array `tableHeaders` di bawah -->
                            <th
                                v-for="header in tableHeaders"
                                :key="header"
                                scope="col"
                                class="px-6 py-3 font-medium"
                            >
                                {{ header }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="user in filteredUsers"
                            :key="user.id"
                            class="bg-neutral-primary-soft border-b border-default hover:bg-neutral-secondary-medium"
                        >
                            <td
                                class="px-6 py-4 font-semibold text-heading whitespace-nowrap"
                            >
                                {{ user.name }}
                            </td>
                            <td class="px-6 py-4">{{ user.email }}</td>
                            <td class="px-6 py-4">
                                {{ user.department?.department_name ?? "-" }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ roleLabel(user.role) }}
                            </td>
                            <td class="px-6 py-4 space-x-3 whitespace-nowrap">
                                <button
                                    type="button"
                                    @click="openEditModal(user)"
                                    class="font-medium text-fg-brand transition-all duration-200 ease-in-out active:scale-95 bg-blue-50 hover:bg-blue-100 rounded-full p-2 dark:bg-blue-500/15 dark:hover:bg-blue-500/25 dark:text-blue-400"
                                >
                                    <PenIcon size="24" />
                                </button>
                                <button
                                    type="button"
                                    @click="openDeleteModal(user)"
                                    class="font-medium text-fg-danger transition-all duration-200 ease-in-out active:scale-95 bg-red-50 hover:bg-red-100 rounded-full p-2 dark:bg-red-500/15 dark:hover:bg-red-500/25 dark:text-red-400"
                                >
                                    <TrashBinTrashIcon size="24" />
                                </button>
                            </td>
                        </tr>

                        <!-- Pesan kalau tidak ada data -->
                        <tr v-if="filteredUsers.length === 0">
                            <td
                                :colspan="tableHeaders.length"
                                class="px-6 py-4 text-center text-body"
                            >
                                Tidak ada data yang cocok.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- ==================== MODAL TAMBAH / EDIT ==================== -->
            <Transition name="fade">
                <div
                    v-if="isFormModalOpen"
                    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="modal-title"
                    @click.self="closeFormModal"
                >
                    <div
                        class="relative w-full max-w-md max-h-[90vh] overflow-y-auto bg-neutral-primary-soft rounded-sm border border-default shadow-lg p-5"
                    >
                        <div class="flex justify-between items-center pb-2">
                            <h3
                                id="modal-title"
                                class="text-lg font-medium text-heading"
                            >
                                {{ modalTitle }}
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

                        <form @submit.prevent="submitForm">
                            <div class="grid gap-4 grid-cols-2 py-2">
                                <!-- Nama -->
                                <div class="col-span-2">
                                    <label
                                        for="name"
                                        class="block mb-2.5 text-sm font-medium text-heading"
                                    >
                                        Nama
                                    </label>
                                    <input
                                        type="text"
                                        id="name"
                                        v-model="form.name"
                                        :class="inputClass"
                                        required
                                    />
                                    <span
                                        v-if="form.errors.name"
                                        class="text-xs text-red-500 mt-1 block"
                                    >
                                        {{ form.errors.name }}
                                    </span>
                                </div>

                                <!-- Email -->
                                <div class="col-span-2">
                                    <label
                                        for="email"
                                        class="block mb-2.5 text-sm font-medium text-heading"
                                    >
                                        Email
                                    </label>
                                    <input
                                        type="text"
                                        id="email"
                                        v-model="form.email"
                                        :class="inputClass"
                                        required
                                    />
                                    <span
                                        v-if="form.errors.email"
                                        class="text-xs text-red-500 mt-1 block"
                                    >
                                        {{ form.errors.email }}
                                    </span>
                                </div>

                                <!-- Password (saat edit boleh dikosongkan) -->
                                <div class="col-span-2">
                                    <label
                                        for="password"
                                        class="block mb-2.5 text-sm font-medium text-heading"
                                    >
                                        Password
                                    </label>
                                    <input
                                        type="password"
                                        id="password"
                                        v-model="form.password"
                                        :class="inputClass"
                                        :required="!isEditing"
                                        :placeholder="
                                            isEditing
                                                ? 'Kosongkan jika tidak ingin mengganti password'
                                                : ''
                                        "
                                    />
                                    <span
                                        v-if="form.errors.password"
                                        class="text-xs text-red-500 mt-1 block"
                                    >
                                        {{ form.errors.password }}
                                    </span>
                                </div>

                                <!-- Departemen (pilihan berbentuk tombol bulat) -->
                                <div class="col-span-2">
                                    <span
                                        class="block mb-2.5 text-sm font-medium text-heading"
                                    >
                                        Departemen
                                    </span>
                                    <div class="flex flex-wrap gap-2">
                                        <label
                                            v-for="department in departments"
                                            :key="department.id"
                                            class="cursor-pointer"
                                        >
                                            <input
                                                type="radio"
                                                name="department_id"
                                                :value="department.id"
                                                v-model="form.department_id"
                                                class="sr-only peer"
                                            />
                                            <div :class="chipClass">
                                                {{ department.department_name }}
                                            </div>
                                        </label>
                                    </div>
                                    <span
                                        v-if="form.errors.department_id"
                                        class="text-xs text-red-500 mt-1 block"
                                    >
                                        {{ form.errors.department_id }}
                                    </span>
                                </div>

                                <!-- Role (pilihan berbentuk tombol bulat) -->
                                <div class="col-span-2">
                                    <span
                                        class="block mb-2.5 text-sm font-medium text-heading"
                                    >
                                        Role
                                    </span>
                                    <div class="flex flex-wrap gap-2">
                                        <label
                                            v-for="role in roleOptions"
                                            :key="role.value"
                                            class="cursor-pointer"
                                        >
                                            <input
                                                type="radio"
                                                name="role"
                                                :value="role.value"
                                                v-model="form.role"
                                                class="sr-only peer"
                                            />
                                            <div :class="chipClass">
                                                {{ role.label }}
                                            </div>
                                        </label>
                                    </div>
                                    <span
                                        v-if="form.errors.role"
                                        class="text-xs text-red-500 mt-1 block"
                                    >
                                        {{ form.errors.role }}
                                    </span>
                                </div>
                            </div>

                            <div
                                class="flex justify-end items-center gap-3 border-default pt-4 mt-2"
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
                                    :disabled="form.processing"
                                    class="inline-flex items-center text-white bg-primary-500 hover:bg-primary-600 border border-transparent shadow-xs font-medium leading-5 rounded-full text-sm px-4 py-3 focus:outline-none transition-all duration-200 ease-in-out active:scale-95 disabled:opacity-60"
                                >
                                    {{ submitLabel }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Transition>

            <!-- ==================== MODAL KONFIRMASI HAPUS ==================== -->
            <Transition name="fade">
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
                            Hapus Pengguna?
                        </h3>
                        <p id="delete-desc" class="mt-2 text-sm text-body">
                            Akun
                            <span class="font-semibold text-heading">
                                {{ userToDelete?.name }}
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
    </AdminLayout>
</template>

<script setup>
import { computed, ref, onMounted, onBeforeUnmount, watch } from "vue";
import Swal from "sweetalert2";
import { router, useForm, usePage } from "@inertiajs/vue3";
import AdminLayout from "../../Layouts/AdminLayout.vue";
import { TrashBinTrashIcon } from "@solar-icons/vue/bold/trash-bin-trash";
import { PenIcon } from "@solar-icons/vue/bold/pen";
import { CloseIcon } from "@solar-icons/vue/bold/close";
import { MinimalisticMagnifierIcon } from "@solar-icons/vue/linear/minimalistic-magnifier";
import { AddIcon } from "@solar-icons/vue/bold/add";

// ==================== DATA DARI LARAVEL ====================
// `users` dan `departments` dikirim dari UsersController lewat Inertia::render(...)
const props = defineProps({
    users: Array,
    departments: Array,
});

// ==================== DATA TETAP (konstanta) ====================
const tableHeaders = ["Nama", "Email", "Departemen", "Role", "Aksi"];

const roleOptions = [
    { value: "department", label: "Department" },
    { value: "it_support", label: "IT Support" },
];

// Ubah nilai role dari database ("it_support") jadi tulisan yang enak dibaca ("IT Support")
const roleLabel = (role) =>
    roleOptions.find((option) => option.value === role)?.label ?? role;

// Class yang dipakai berulang di form
const inputClass =
    "bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-sm focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body";

const chipClass =
    "px-3 py-2 bg-gray-100 text-gray-700 text-sm rounded-full border border-gray-200 transition-all duration-200 ease-in-out active:scale-95 peer-checked:bg-dark peer-checked:text-white peer-checked:border-dark peer-checked:shadow-md peer-focus-visible:ring-2 peer-focus-visible:ring-brand";

// ==================== NOTIFIKASI (SWEETALERT) ====================
const page = usePage();

const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
});

// Pesan dari controller (->with('success', ...) / ->with('error', ...)) tampil sebagai toast
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

const filteredUsers = computed(() => {
    const keyword = searchQuery.value.trim().toLowerCase();
    if (!keyword) return props.users; // tidak ada pencarian = tampilkan semua

    return props.users.filter((user) => {
        const searchableFields = [
            user.name,
            user.email,
            user.department?.department_name,
            roleLabel(user.role),
        ];

        return searchableFields.some((field) =>
            field?.toLowerCase().includes(keyword),
        );
    });
});

// ==================== FORM TAMBAH / EDIT ====================
// useForm dari Inertia: menyimpan isi form, error validasi, dan status "sedang mengirim"
const form = useForm({
    name: "",
    email: "",
    password: "",
    department_id: "",
    role: "",
});

const isFormModalOpen = ref(false);

// id pengguna yang sedang diedit. null = sedang menambah pengguna baru
const editingId = ref(null);
const isEditing = computed(() => editingId.value !== null);

// Teks yang berubah tergantung mode tambah / edit
const modalTitle = computed(() =>
    isEditing.value ? "Edit Pengguna" : "Tambah Pengguna",
);

const submitLabel = computed(() => {
    if (form.processing) {
        return isEditing.value ? "Updating..." : "Saving...";
    }
    return isEditing.value ? "Update" : "Simpan";
});

const openAddModal = () => {
    editingId.value = null;
    form.reset(); // kosongkan isi form
    form.clearErrors();
    isFormModalOpen.value = true;
};

const openEditModal = (user) => {
    editingId.value = user.id;
    form.name = user.name;
    form.email = user.email;
    form.department_id = user.department_id;
    form.role = user.role;
    form.password = ""; // password lama tidak ditampilkan
    form.clearErrors();
    isFormModalOpen.value = true;
};

const closeFormModal = () => {
    isFormModalOpen.value = false;
};

const submitForm = () => {
    const options = {
        preserveScroll: true,
        onSuccess: () => {
            closeFormModal();
            form.reset();
        },
        // Kalau gagal, modal tetap terbuka supaya isian tidak hilang
        onError: (errors) => {
            Swal.fire({
                icon: "error",
                title: isEditing.value
                    ? "Gagal memperbarui Pengguna"
                    : "Gagal menambahkan Pengguna",
                text:
                    Object.values(errors)[0] ??
                    "Periksa kembali data yang diisi.",
            });
        },
    };

    if (isEditing.value) {
        form.put(`/admin/users/${editingId.value}`, options);
    } else {
        form.post("/admin/users", options);
    }
};

// ==================== MODAL HAPUS ====================
const isDeleteModalOpen = ref(false);
const isDeleting = ref(false);
const userToDelete = ref(null);

const openDeleteModal = (user) => {
    userToDelete.value = user;
    isDeleteModalOpen.value = true;
};

const closeDeleteModal = () => {
    if (isDeleting.value) return; // jangan tutup saat proses hapus berjalan
    isDeleteModalOpen.value = false;
};

const confirmDelete = () => {
    if (!userToDelete.value) return;

    router.delete(`/admin/users/${userToDelete.value.id}`, {
        preserveScroll: true,
        onStart: () => (isDeleting.value = true),
        onSuccess: () => (isDeleteModalOpen.value = false),
        onError: (errors) => {
            Swal.fire({
                icon: "error",
                title: "Gagal menghapus Pengguna",
                text: Object.values(errors)[0],
            });
        },
        onFinish: () => (isDeleting.value = false),
    });
};

// ==================== TOMBOL ESCAPE ====================
// Escape menutup modal yang sedang terbuka
const closeOnEscape = (event) => {
    if (event.key !== "Escape") return;

    if (isDeleteModalOpen.value) closeDeleteModal();
    else if (isFormModalOpen.value) closeFormModal();
};

onMounted(() => window.addEventListener("keydown", closeOnEscape));
onBeforeUnmount(() => window.removeEventListener("keydown", closeOnEscape));
</script>

<style scoped>
/* Animasi muncul/hilang untuk kedua modal (dipakai lewat <Transition name="fade">) */
.fade-enter-active {
    transition: opacity 200ms ease-out;
}
.fade-leave-active {
    transition: opacity 150ms ease-in;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
