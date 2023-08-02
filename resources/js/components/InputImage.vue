<template>
    <div class="flex">
        <label
            v-if="url.length < props.number"
            class="h-[120px] w-[120px] border border-dashed border-gray-400 flex justify-center items-center text-xl text-gray-300 cursor-pointer order-1"
            @click="openFileDialog"
        >
            Chọn ảnh
        </label>
        <input
            type="file"
            accept="image/png,image/jpeg"
            @change="handleChange"
            class="hidden"
            ref="inputImage"
        />
        <div v-if="url?.length > 0" class="gap-2 flex">
            <a
                v-for="(item, index) in url"
                :href="item"
                :key="index"
                target="_blank"
                rel="noopener noreferrer"
                class="h-[120px] w-[120px] gap-2 relative cursor-zoom-in"
            >
                <img :src="item" alt="" class="w-full h-full object-cover" />
                <button
                    class="absolute w-6 h-6 top-0 right-0 text-white bg-red-500"
                    @click.prevent="removeImage($event, index)"
                >
                    x
                </button>
            </a>
        </div>
    </div>
</template>

<script setup>
import { onUnmounted, ref } from "vue";

const props = defineProps({
    number: {
        type: Number,
        required: true,
    },
    data: Array,
});

const images = ref([]);
const url = ref([]);
const inputImage = ref();
const deleted = [];

defineExpose({ images, deleted });
const emit = defineEmits(["changeData"]);

if (props.data) {
    url.value = [...props.data];
    images.value = [...props.data];
}

function handleChange(e) {
    const files = inputImage.value.files;
    if (files && files.length > 0) {
        for (const item of files) {
            images.value.push(item);
            const link = URL.createObjectURL(item);
            url.value.push(link);
        }
        emit("changeData", images.value, undefined);
    }
}

function removeImage(e, index) {
    inputImage.value.value = "";
    if (typeof images.value[index] == "string") {
        const nameFile = images.value[index].split("/").pop();
        deleted.push(nameFile);
    }
    images.value.splice(index, 1);
    if (url.value && url.value.length > 0) {
        URL.revokeObjectURL(url.value[index]);
        url.value.splice(index, 1);
        emit("changeData", images.value, index);
    }
}

function openFileDialog(e) {
    inputImage.value.click();
}

onUnmounted(() => {
    url.value.forEach((item) => {
        URL.revokeObjectURL(item);
    });
});
</script>

<style lang="scss" scoped></style>
