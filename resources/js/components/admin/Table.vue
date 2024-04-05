<template>
    <table class="w-full">
        <thead>
            <tr class="border-b">
                <th
                    v-for="(item, index) in props.header"
                    :key="index"
                    class="px-5 py-3 text-left"
                >
                    {{ item }}
                </th>
            </tr>
        </thead>
        <tbody>
            <tr
                v-for="(item, index) in props.data"
                :key="index"
                class="border-b"
            >
                <td
                    v-for="elem in item"
                    class="px-5 py-3 overflow-ellipsis whitespace-nowrap overflow-hidden max-w-[250px]"
                    :title="elem"
                >
                    {{ elem }}
                </td>
                <td class="flex items-center px-5 py-3">
                    <Button
                        class="bg-blue-500 p-[6px] mr-3"
                        @click="clickEdit($event, index)"
                    >
                        <box-icon color="#fff" name="edit"></box-icon>
                    </Button>
                    <Button
                        class="bg-red-500 p-[6px]"
                        @click="clickDelete($event, index)"
                    >
                        <box-icon color="#fff" name="trash"></box-icon>
                    </Button>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script setup>
import Button from "@/components/Button.vue";
const props = defineProps({
    data: {
        type: Array,
        required: true,
    },
    header: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(["edit", "delete"]);

function clickEdit(e, index) {
    emit("edit", index);
}

function clickDelete(e, index) {
    emit("delete", index);
}
</script>

<style lang="scss" scoped></style>
