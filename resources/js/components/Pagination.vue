<template>
    <ul class="flex">
        <li
            class="flex items-center p-1 cursor-pointer rounded-md mr-2 select-none"
            @click="handleAction('first')"
            :class="currentPage <= 1 ? 'bg-gray-400 cursor-not-allowed' : ''"
        >
            <box-icon
                name="chevrons-left"
                :color="currentPage <= 1 ? '#fff' : '#3b82f6'"
            ></box-icon>
        </li>
        <li
            class="flex items-center border p-1 cursor-pointer rounded-md mr-2 select-none"
            @click="handleAction('prev')"
            :class="currentPage <= 1 ? 'bg-gray-400 cursor-not-allowed' : ''"
        >
            <box-icon
                name="chevron-left"
                :color="currentPage <= 1 ? '#fff' : '#3b82f6'"
            ></box-icon>
        </li>
        <li
            v-for="item in data"
            :key="item"
            @click="goToPage(item)"
            class="flex items-center p-1 px-3 cursor-pointer rounded-md mr-2 select-none"
            :class="
                item === currentPage
                    ? 'bg-blue-500 text-white'
                    : 'border text-blue-500'
            "
        >
            {{ item }}
        </li>
        <li
            class="flex items-center border p-1 cursor-pointer rounded-md mr-2 select-none"
            @click="handleAction('next')"
            :class="
                currentPage >= props.total
                    ? 'bg-gray-400 cursor-not-allowed'
                    : ''
            "
        >
            <box-icon
                name="chevron-right"
                :color="currentPage >= props.total ? '#fff' : '#3b82f6'"
            ></box-icon>
        </li>
        <li
            class="flex items-center border p-1 cursor-pointer rounded-md mr-2 select-none"
            @click="handleAction('last')"
            :class="
                currentPage >= props.total
                    ? 'bg-gray-400 cursor-not-allowed'
                    : ''
            "
        >
            <box-icon
                name="chevrons-right"
                :color="currentPage >= props.total ? '#fff' : '#3b82f6'"
            ></box-icon>
        </li>
    </ul>
</template>

<script setup>
import { onBeforeMount, ref, watch } from "vue";

const props = defineProps({
    total: {
        type: Number,
        required: true,
    },
    length: {
        type: Number,
        required: true,
    },
});

const emit = defineEmits(["goto"]);

const data = ref([]);

const currentPage = ref(1);

onBeforeMount(() => {});

function goToPage(item) {
    currentPage.value = item;
}

function handleAction(action) {
    switch (action) {
        case "first":
            currentPage.value = 1;
            break;
        case "prev":
            if (currentPage.value > 1) {
                currentPage.value--;
            }
            break;
        case "next":
            if (currentPage.value < props.total) {
                currentPage.value++;
            }
            break;
        case "last":
            currentPage.value = props.total;
            break;
        default:
            throw new Error("action invalid");
    }
}

watch(currentPage, (newValue) => {
    emit("goto", newValue);
    const index = Math.ceil(props.length / 2) - 1;
    const middle = data.value[index];
    const distance = Math.abs(newValue - middle);
    let count = distance;
    let last = data.value[props.length - 1];
    let first = data.value[0];
    if (newValue > middle) {
        while (count && last < props.total) {
            data.value.shift();
            data.value.push(++last);
            count--;
        }
    } else if (newValue < middle) {
        while (count && first > 1) {
            data.value.pop();
            data.value.unshift(--first);
            count--;
        }
    }
});

watch(
    () => props.total,
    () => {
        const length = props.total > props.length ? props.length : props.total;
        data.value = [];
        for (let i = 1; i <= length; i++) {
            data.value.push(i);
        }
    }
);
</script>

<style lang="scss" scoped></style>
