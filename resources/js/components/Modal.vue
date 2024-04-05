<template>
    <Teleport to="body">
        <Transition name="fade">
            <div
                v-if="status"
                class="fixed inset-0 bg-black bg-opacity-30 flex justify-center items-center overflow-y-auto"
            >
                <div class="bg-white rounded-md max-w-[960px]">
                    <header class="flex justify-between p-3 border-b">
                        <h3 class="font-bold text-xl">{{ props.title }}</h3>
                        <Button
                            class="bg-gray-200 p-1"
                            :handle-click="handleClose"
                        >
                            <box-icon
                                name="x"
                                class="cursor-pointer"
                            ></box-icon>
                        </Button>
                    </header>
                    <div class="p-3 max-h-[80vh] overflow-y-auto">
                        <slot />
                    </div>
                    <footer class="flex justify-end p-3 border-t">
                        <Button
                            class="bg-gray-400 text-white mr-2 px-3 py-2"
                            :handle-click="handleClose"
                            >Huỷ bỏ</Button
                        >
                        <Button
                            class="text-white px-3 py-2"
                            :class="props.danger ? 'bg-red-500' : 'bg-blue-500'"
                            @click="handleAccept"
                            >Xác nhận</Button
                        >
                        <Button
                            v-if="props.customButton"
                            class="text-white px-3 py-2 ml-2"
                            :class="props.customButton?.class"
                            @click="$emit('customClick')"
                            >{{ props.customButton?.text }}</Button
                        >
                    </footer>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import Button from "./Button.vue";
import { ref } from "vue";

const status = ref(false);

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    danger: {
        type: Boolean,
    },
    customButton: {
        type: Object,
    },
});

const emit = defineEmits(["acceptClick", "customClick"]);

function handleClose(e) {
    status.value = false;
}

function handleAccept(e) {
    emit("acceptClick");
}

defineExpose({
    status,
});
</script>

<style lang="scss" scoped>
.fade-enter-active,
.fade-leave-active {
    transition: all 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
