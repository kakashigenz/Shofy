<template>
    <ul class="cursor-text flex items-center flex-wrap">
        <li
            v-for="(item, index) in data"
            :key="index"
            class="rounded-md bg-gray-300 mr-2 px-2 py-1 flex items-center text-base cursor-default mb-1"
        >
            <span class="mr-1">{{ item }}</span>
            <span
                class="cursor-pointer border border-gray-600 rounded-full w-5 h-5 flex justify-center items-center"
                @click="handleRemove($event, index)"
            >
                <box-icon name="x"></box-icon>
            </span>
        </li>
        <li class="flex-1">
            <input
                type="text"
                class="outline-none w-full"
                v-model="text"
                @keydown="handlePress"
                placeholder="Nhập nội dung và nhấn enter"
            />
        </li>
    </ul>
</template>

<script setup>
import { ref } from "vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

const data = ref([]);
const text = ref("");

defineExpose({
    data,
});

const props = defineProps({
    unique: Boolean,
    limit: Number,
});

const emit = defineEmits(["changeValue"]);

function handleRemove(e, index) {
    data.value.splice(index, 1);
    emit("changeValue");
}

function handlePress(e) {
    let isContain = false;
    if (props.unique) {
        isContain = data.value.includes(text.value);
    }
    if (e.keyCode === 8 && text.value.trim() === "") {
        data.value.pop();
    } else if (e.keyCode === 13) {
        e.preventDefault();
        if (isContain) {
            toast.error("Giá trị đã tồn tại");
            return;
        }
        if (props.limit && data.value.length > props.limit) {
            toast.error(`Chỉ có thể thêm ${props.limit} giá trị`);
            return;
        }
        if (text.value.trim()) {
            data.value.push(text.value.trim());
            text.value = "";
            emit("changeValue");
        }
    }
}
</script>

<style lang="scss" scoped></style>
