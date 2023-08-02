<template>
    <button
        class="rounded-md flex items-center hover:scale-95 transition-all"
        @click="handleClick"
        :type="props.type ?? 'button'"
    >
        <slot />
    </button>
</template>

<script setup>
const props = defineProps({
    handleClick: Function,
    type: String,
});
</script>

<style lang="scss" scoped></style>
