<template>
    <div class="rounded-lg border border-gray-300 overflow-hidden">
        <div
            class="px-3 py-2 bg-gray-300 flex justify-between cursor-pointer"
            @click="toggle"
        >
            <h3>{{ props.title }}</h3>
            <box-icon
                name="chevron-right"
                :class="isActive ? 'rotate-90' : ''"
                class="icon"
            ></box-icon>
        </div>
        <Transition name="dropdown">
            <div v-if="isActive">
                <div class="p-3">
                    <slot />
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref } from "vue";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    initState: {
        type: Boolean,
    },
});

const isActive = ref(props.initState);

function toggle(e) {
    isActive.value = !isActive.value;
}
</script>

<style lang="scss" scoped>
.icon {
    transition: all 0.3s ease;
}
.dropdown-enter-active,
.dropdown-leave-active {
    transition: all 0.3s ease;
    max-height: 100vh;
}

.dropdown-enter-from,
.dropdown-leave-to {
    max-height: 0;
}
</style>
