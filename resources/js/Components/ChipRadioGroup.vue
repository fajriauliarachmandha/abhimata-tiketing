<template>
    <div class="flex flex-wrap gap-2">
        <label
            v-for="item in options"
            :key="item.value"
            class="cursor-pointer select-none"
        >
            <input
                type="radio"
                :name="name"
                :value="item.value"
                :checked="modelValue === item.value"
                @change="$emit('update:modelValue', item.value)"
                class="sr-only peer"
            />
            <div
                class="px-3.5 py-1.5 text-sm font-medium rounded-lg border transition-all duration-200 ease-in-out active:scale-95 bg-gray-100 text-gray-700 border-gray-200 peer-checked:shadow-sm"
                :class="
                    item.activeClass ??
                    'peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600'
                "
            >
                {{ item.label }}
            </div>
        </label>
    </div>
</template>
<script setup>
defineProps({
    options: {
        type: Array,
        required: true,
    },
    modelValue: {
        type: String,
        required: true,
    },
    name: {
        type: String,
        required: true,
    },
});
defineEmits(["update:modelValue"]);
</script>
