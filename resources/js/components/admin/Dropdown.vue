<template>
    <div
        class="flex items-center cursor-pointer gap-x-2 relative select-none"
        @click="toggleDropdown"
    >
        <slot></slot>
        <div
            v-if="isShow"
            class="bg-white border absolute top-[calc(100%+8px)] w-full py-3"
        >
            <ul>
                <li
                    v-for="(item, index) in props.data"
                    :key="index"
                    class="pl-5 py-2"
                >
                    {{ item.title }}
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";

const isShow = ref(false); // status dropdown

const props = defineProps({
    data: {
        type: Array,
        required: true,
    },
});
function toggleDropdown() {
    isShow.value = !isShow.value;
}
</script>

<style lang="scss" scoped></style>
