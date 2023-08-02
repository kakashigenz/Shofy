<template>
    <div class="min-w-[120px] select-none">
        <header
            class="flex justify-between px-1 relative border border-gray-300 items-center cursor-pointer"
            @click="toggle"
        >
            <span>{{ currentValue.value }}</span>
            <span class="flex items-center">
                <box-icon name="chevron-down"></box-icon>
            </span>
            <ul
                v-if="show"
                class="absolute top-full left-0 right-0 border border-gray-300 z-10 bg-gray-200"
            >
                <li
                    v-for="item in props.options"
                    :key="item.id"
                    class="hover:bg-blue-500 hover:text-white bg px-1 flex justify-between items-center"
                    @click="select($event, item)"
                >
                    <span>{{ item.value }}</span>
                    <slot name="remove" :item="item" />
                </li>
            </ul>
        </header>
    </div>
</template>

<script setup>
import { computed, ref } from "vue";

const props = defineProps({
    modelValue: {
        type: Number,
        required: true,
    },
    options: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(["update:modelValue"]);

const show = ref(false);

const currentValue = computed(() => {
    return props.options.find((item) => item.id === props.modelValue);
});

function toggle(e) {
    show.value = !show.value;
}

function select(e, item) {
    emit("update:modelValue", item.id);
}
</script>

<style lang="scss" scoped></style>
