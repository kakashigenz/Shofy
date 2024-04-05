<template>
    <div class="relative mb-3">
        <div class="flex flex-col">
            <label class="mb-1">{{ prop.label }}</label>
            <input
                type="text"
                class="px-3 py-2 rounded-lg border border-gray-300 outline-none"
                @input="doSearch($event)"
                ref="input"
                v-model="text"
            />
        </div>
        <ul
            class="absolute z-10 top-full w-full py-2 shadow-md list-none bg-white"
            v-if="isShow"
        >
            <li
                v-for="item in data"
                :key="item.id"
                class="hover:bg-slate-300 px-3 py-1"
                @click="selectData(item.data)"
            >
                {{ item.data }}
            </li>
        </ul>
    </div>
</template>

<script setup>
import debounce from "@/helper/debounce";
import { ref } from "vue";

const prop = defineProps(["label", "debounce", "search"]);
const text = ref("");
const isShow = ref(false);
const input = ref();
let timeoutId;
const data = ref([]);

function doSearch(e) {
    if (prop.debounce > 0 && prop.search) {
        clearTimeout(timeoutId);

        timeoutId = debounce(function () {
            isShow.value = true;
            const res = prop.search(text.value);
            data.value = res;
        }, prop.debounce);
    }
}

function selectData(data) {
    input.value.value = data;
    isShow.value = false;
}
</script>

<style lang="scss" scoped></style>
