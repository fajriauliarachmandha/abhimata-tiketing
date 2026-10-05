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
                        class="text-gray-400"
                    />
                </div>
                <input
                    type="text"
                    id="input-group-1"
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

        <!-- Table Container -->
        <div
            class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs border border-default rounded-sm"
        >
            <table class="w-full text-sm text-left rtl:text-right text-body">
                <thead
                    class="text-sm text-body bg-neutral-secondary-medium border-b border-t border-default-medium"
                >
                    <tr>
                        <th scope="col" class="px-6 py-3 font-medium">Nama</th>
                        <th scope="col" class="px-6 py-3 font-medium">
                            Username
                        </th>
                        <th scope="col" class="px-6 py-3 font-medium">
                            Departemen
                        </th>
                        <th scope="col" class="px-6 py-3 font-medium">Role</th>
                        <th scope="col" class="px-6 py-3 font-medium">Aksi</th>
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
                        <td class="px-6 py-4">{{ user.username }}</td>
                        <td class="px-6 py-4">
                            {{ user.department?.department_name ?? "-" }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ roleLabel(user.role) }}
                        </td>
                        <td class="px-6 py-4 space-x-3 whitespace-nowrap">
                            <button
                                type="button"
                                @click="openUpdateModal(user)"
                                class="font-medium text-fg-brand hover:underline transition-all duration-200 ease-in-out active:scale-95 bg-blue-50 hover:bg-blue-100 rounded-full py-2 px-2"
                            >
                                <PenIcon size="24" />
                            </button>
                            <button
                                type="button"
                                @click="openDeleteModal(user)"
                                class="font-medium text-fg-danger hover:underline transition-all duration-200 ease-in-out active:scale-95 bg-red-50 hover:bg-red-100 rounded-full py-2 px-2"
                            >
                                <TrashBinTrashIcon size="24" />
                            </button>
                        </td>
                    </tr>
                    <tr v-if="filteredUsers.length === 0">
                        <td colspan="5" class="px-6 py-4 text-center text-body">
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
                    class="relative w-full max-w-md max-h-[90vh] overflow-y-auto bg-neutral-primary-soft rounded-sm border border-default shadow-lg p-5"
                >
                    <div class="flex justify-between items-center pb-2">
                        <h3
                            id="modal-title"
                            class="text-lg font-medium text-heading"
                        >
                            {{
                                editingId ? "Edit Pengguna" : "Tambah Pengguna"
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
                                    for="name"
                                    class="block mb-2.5 text-sm font-medium text-heading"
                                >
                                    Nama
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    v-model="form.name"
                                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-sm focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                                    required
                                />
                                <span
                                    v-if="form.errors.name"
                                    class="text-xs text-red-500 mt-1 block"
                                >
                                    {{ form.errors.name }}
                                </span>
                            </div>

                            <div class="col-span-2">
                                <label
                                    for="username"
                                    class="block mb-2.5 text-sm font-medium text-heading"
                                >
                                    Username
                                </label>
                                <input
                                    type="text"
                                    id="username"
                                    v-model="form.username"
                                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-sm focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                                    required
                                />
                                <span
                                    v-if="form.errors.username"
                                    class="text-xs text-red-500 mt-1 block"
                                >
                                    {{ form.errors.username }}
                                </span>
                            </div>

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
                                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-sm focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                                    :required="!editingId"
                                    :placeholder="
                                        editingId
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

                            <div class="col-span-2">
                                <span
                                    class="block mb-2.5 text-sm font-medium text-heading"
                                >
                                    Departemen
                                </span>
                                <div class="flex flex-wrap gap-2">
                                    <label
                                        v-for="dept in departments"
                                        :key="dept.id"
                                        class="cursor-pointer"
                                    >
                                        <input
                                            type="radio"
                                            name="department_id"
                                            :value="dept.id"
                                            v-model="form.department_id"
                                            class="sr-only peer"
                                        />
                                        <div
                                            class="px-3 py-2 bg-gray-100 text-gray-700 text-sm rounded-full border border-gray-200 transition-all duration-200 ease-in-out active:scale-95 peer-checked:bg-dark peer-checked:text-white peer-checked:border-dark peer-checked:shadow-md peer-focus-visible:ring-2 peer-focus-visible:ring-brand"
                                        >
                                            {{ dept.department_name }}
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

                            <div class="col-span-2">
                                <span
                                    class="block mb-2.5 text-sm font-medium text-heading"
                                >
                                    Role
                                </span>
                                <div class="flex flex-wrap gap-2">
                                    <label
                                        v-for="item in roleOptions"
                                        :key="item.value"
                                        class="cursor-pointer"
                                    >
                                        <input
                                            type="radio"
                                            name="role"
                                            :value="item.value"
                                            v-model="form.role"
                                            class="sr-only peer"
                                        />
                                        <div
                                            class="px-3 py-2 bg-gray-100 text-gray-700 text-sm rounded-full border border-gray-200 transition-all duration-200 ease-in-out active:scale-95 peer-checked:bg-dark peer-checked:text-white peer-checked:border-dark peer-checked:shadow-md peer-focus-visible:ring-2 peer-focus-visible:ring-brand"
                                        >
                                            {{ item.label }}
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
                        Hapus Pengguna?
                    </h3>
                    <p id="delete-desc" class="mt-2 text-sm text-body">
                        Akun
                        <span class="font-semibold text-heading">
                            {{ deleteTarget?.name }}
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
    users: Array,
    departments: Array,
});

const roleOptions = [
    { value: "department", label: "Department" },
    { value: "it_support", label: "IT Support" },
];
const roleLabel = (role) =>
    roleOptions.find((o) => o.value === role)?.label ?? role;

const searchQuery = ref("");

// Form Tambah dan Edit
const form = useForm({
    name: "",
    username: "",
    password: "",
    department_id: "",
    role: "",
});

const editingId = ref(null);
const isModalOpen = ref(false);

const openAddModal = () => {
    editingId.value = null;
    form.reset();
    form.clearErrors();
    isModalOpen.value = true;
};

const openUpdateModal = (user) => {
    editingId.value = user.id;
    form.name = user.name;
    form.username = user.username;
    form.department_id = user.department_id;
    form.role = user.role;
    form.password = "";
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
        form.put(`/admin/users/${editingId.value}`, options);
    } else {
        form.post("/admin/users", options);
    }
};

// Modal hapus
const isDeleteOpen = ref(false);
const isDeleting = ref(false);
const deleteTarget = ref(null);

const openDeleteModal = (user) => {
    deleteTarget.value = user;
    isDeleteOpen.value = true;
};

const closeDeleteModal = () => {
    if (isDeleting.value) return;
    isDeleteOpen.value = false;
};

const confirmDelete = () => {
    if (!deleteTarget.value) return;

    router.delete(`/admin/users/${deleteTarget.value.id}`, {
        preserveScroll: true,
        onStart: () => (isDeleting.value = true),
        onSuccess: () => (isDeleteOpen.value = false),
        onError: (errors) => alert(Object.values(errors)[0]),
        onFinish: () => (isDeleting.value = false),
    });
};

// Escape menutup modal yang sedang terbuka
const onKeydown = (e) => {
    if (e.key !== "Escape") return;
    if (isDeleteOpen.value) closeDeleteModal();
    else if (isModalOpen.value) closeModal();
};
onMounted(() => window.addEventListener("keydown", onKeydown));
onBeforeUnmount(() => window.removeEventListener("keydown", onKeydown));

// search
const filteredUsers = computed(() => {
    if (!searchQuery.value.trim()) {
        return props.users;
    }
    const q = searchQuery.value.toLowerCase();
    return props.users.filter(
        (user) =>
            user.name.toLowerCase().includes(q) ||
            user.username.toLowerCase().includes(q) ||
            (user.department?.department_name ?? "")
                .toLowerCase()
                .includes(q) ||
            roleLabel(user.role).toLowerCase().includes(q),
    );
});
</script>
