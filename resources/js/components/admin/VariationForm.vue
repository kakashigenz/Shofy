<template>
    <div>
        <div class="flex flex-col mb-2">
            <Panel title="Biến thể" :init-state="true">
                <div class="flex flex-col justify-center gap-3">
                    <div
                        class="p-3 flex flex-col bg-gray-200 rounded-lg relative"
                        v-for="(variation, index) in variations"
                        :key="variation.id"
                    >
                        <div class="flex flex-col gap-1 mb-1">
                            <label class=""
                                >Tên biến thể
                                <span class="text-red-500">*</span></label
                            >
                            <input
                                type="text"
                                class="border border-gray-300 rounded-lg py-1 px-2 outline-none"
                                v-model="variation.name"
                                :class="
                                    props.mode == 2
                                        ? 'bg-gray-300 text-gray-400 select-none'
                                        : ''
                                "
                                :readonly="props.mode == 2"
                            />
                            <p
                                v-if="props.errorMessage.variations.has(index)"
                                class="text-red-500"
                            >
                                {{ props.errorMessage.variations.get(index) }}
                            </p>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label
                                >Giá trị biến thể
                                <span class="text-red-500">*</span></label
                            >
                            <div>
                                <Chip
                                    ref="chips"
                                    :limit="props.maxOption"
                                    class="border border-gray-300 rounded-lg py-1 px-2 outline-none"
                                    unique
                                    @change-value="
                                        () => {
                                            changeValueChip(variation, index);
                                        }
                                    "
                                />
                                <p
                                    v-if="
                                        props.errorMessage.variationOptions.has(
                                            index
                                        )
                                    "
                                    class="text-red-500"
                                >
                                    {{
                                        props.errorMessage.variationOptions.get(
                                            index
                                        )
                                    }}
                                </p>
                            </div>
                        </div>
                        <div
                            class="flex justify-center items-center absolute top-1 right-3"
                        >
                            <Button
                                class="px-3 py-1 bg-red-500 rounded-lg text-white font-medium"
                                :handle-click="
                                    () => removeVariation(variation, index)
                                "
                                v-if="props.mode == 0"
                            >
                                x
                            </Button>
                        </div>
                    </div>

                    <div class="flex justify-center">
                        <button
                            class="p-2 border border-dashed border-blue-500 rounded-lg text-blue-500"
                            type="button"
                            @click="addVariation"
                            v-if="variations.length < 3 && props.mode == 0"
                        >
                            Thêm biến thể
                            {{ `(${variations.length}/3)` }}
                        </button>
                    </div>
                </div>
            </Panel>
        </div>
        <div class="mb-2" v-if="productItems.length > 0">
            <table class="border-collapse w-full">
                <caption class="bg-gray-300 p-2 text-left">
                    Sản phẩm tương ứng
                </caption>
                <thead>
                    <tr>
                        <th class="border border-gray-300 p-1">Chọn</th>
                        <!-- <th class="border border-gray-300 p-1">Ảnh</th> -->
                        <th class="border border-gray-300 p-1">Thuộc tính</th>
                        <th class="border border-gray-300 p-1">SKU</th>
                        <th class="border border-gray-300 p-1">Giá</th>
                        <th class="border border-gray-300 p-1">Tồn kho</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in productItems" :key="index">
                        <td class="border border-gray-300">
                            <input
                                type="hidden"
                                v-if="item.id"
                                v-model="item.id"
                            />
                            <div class="flex justify-center">
                                <input
                                    type="checkbox"
                                    class="h-4 w-4 cursor-pointer"
                                    v-model="item.isCheck"
                                />
                            </div>
                        </td>
                        <!-- ảnh để maping từng biến thể -->
                        <!-- <td class="border border-gray-300">
                                    <div class="flex justify-center">
                                        <InputImage
                                            :number="1"
                                            @change-data="
                                                (images) => {
                                                    productImage.push(images);
                                                }
                                            "
                                        />
                                    </div>
                                </td> -->
                        <td class="border border-gray-300 text-center">
                            {{
                                item.variation
                                    .map((item) => item.value)
                                    .join("-")
                            }}
                        </td>
                        <td class="border border-gray-300 p-1">
                            <div class="flex justify-center">
                                <input
                                    type="text"
                                    class="px-2 py-1 border border-gray-300 outline-none"
                                    v-model="item.sku"
                                />
                            </div>
                        </td>
                        <td class="border border-gray-300 p-1">
                            <div class="flex justify-center">
                                <input
                                    type="number"
                                    class="p-1 border border-gray-300 outline-none"
                                    v-model="item.price"
                                    @keypress="allowInputNumber"
                                    min="1"
                                />
                                <span class="border border-gray-300 p-1"
                                    >đ</span
                                >
                            </div>
                        </td>
                        <td class="border border-gray-300">
                            <div class="flex justify-center">
                                <input
                                    type="number"
                                    class="px-2 py-1 border border-gray-300 outline-none"
                                    v-model="item.quantity"
                                    @keypress="allowInputNumber"
                                    min="1"
                                />
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { nextTick, onMounted, ref, watch } from "vue";
import Panel from "../Panel.vue";
import { v4 as uuidv4 } from "uuid";
import Chip from "../Chip.vue";
import Button from "../Button.vue";

const props = defineProps({
    errorMessage: {
        type: Object,
        required: true,
    },
    mode: {
        type: Number,
        //0 la them san pham
        //1 la them bien the
        //2 la them gia tri bien the
    },
    variations: {
        type: Array,
        required: true,
    },
    maxOption: {
        type: Number,
    },
    variation: {
        type: Object,
    },
});

const variations = ref([]);
const chipData = ref(new Map());
const chips = ref();
const productItems = ref([]);
// const errorMessage = ref({
//     variations: new Map(),
//     variationOptions: new Map(),
// });

onMounted(async () => {
    if (props.variation) {
        variations.value = [props.variation];

        props.variations.forEach((variation) => {
            const data = {
                name: variation?.name,
                option: variation.option.map((item) => item.value),
            };
            if (variation.id != props.variation.id)
                chipData.value.set(variation.id, data);
        });

        await nextTick();

        chipData.value.set(props.variation?.id, {
            name: props.variation?.name,
            option: chips.value[0].data,
        });
    }

    if (props.mode == 1) {
        addVariation();

        props.variations.forEach((variation) => {
            const data = {
                name: variation?.name,
                option: variation.option.map((item) => item.value),
            };
            chipData.value.set(variation.id, data);
        });

        await nextTick();

        const variation = variations.value[0];
        chipData.value.set(variation.id, {
            name: variation.name,
            option: chips.value[0].data,
        });
    }
});

defineExpose({
    variations,
    productItems,
    chipData,
    chips,
});

function changeValueChip(variation, index) {
    const id = variation.id;
    const data = {
        name: variation?.name,
        option: chips.value[index].data,
    };
    chipData.value.set(id, data);
}

function removeVariation(variation, index) {
    // xóa các giá trị biến thể để watch chạy logic generate table
    chipData.value.delete(variation.id);
    variations.value.splice(index, 1);
}

function addVariation() {
    variations.value.push({
        id: uuidv4(),
        name: "",
    });
}

function allowInputNumber(e) {
    if (!((e.keyCode >= 48 && e.keyCode <= 57) || e.keyCode === 8)) {
        e.preventDefault();
    }
}

watch(
    chipData,
    (newValue) => {
        let res = [];

        for (const elem of newValue) {
            if (res.length == 0) {
                res = elem[1].option.map((item) => {
                    return {
                        isCheck: true,
                        sku: "",
                        price: 1000,
                        quantity: 1,
                        variation: [
                            {
                                variation_id: elem[0],
                                value: item,
                            },
                        ],
                    };
                });
            } else {
                const tmp = [...res];
                res = [];
                for (const first of tmp) {
                    if (!elem[1].option?.length && props.mode == 0) {
                        res = [...tmp];
                        break;
                    }
                    for (const second of elem[1].option) {
                        res.push({
                            isCheck: true,
                            sku: "",
                            price: 1000,
                            quantity: 1,
                            variation: [
                                ...first.variation,
                                {
                                    variation_id: elem[0],
                                    value: second,
                                },
                            ],
                        });
                    }
                }
            }
        }
        // console.log(res);
        productItems.value = [...res];
    },
    { deep: true }
);
</script>

<style lang="scss" scoped></style>
