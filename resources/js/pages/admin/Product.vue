<template>
    <div>
        <div class="bg-white">
            <div class="px-5 py-3 flex justify-between">
                <Button class="bg-green-600 p-1" :handle-click="handleCreate">
                    <box-icon name="plus" color="#fff"></box-icon>
                </Button>
                <input
                    type="text"
                    class="border border-gray-700 rounded-sm outline-none px-2"
                    v-model="searchText"
                />
            </div>
            <Table
                :header="tableHeader"
                :data="listCategory"
                @edit="handleEdit"
                @delete="handleDelete"
            ></Table>
            <div class="p-3 flex justify-end">
                <Pagination
                    :length="paginator.range"
                    :total="paginator.total"
                    @goto="handleChangePage"
                />
            </div>
        </div>
        <!-- màn thêm sản phẩm -->
        <Modal
            ref="modal"
            :title="modalTitle"
            @acceptClick="handleAccept"
            :custom-button="hideOrShowButton"
            @custom-click="
                () => {
                    toggleStatusProduct(product);
                }
            "
        >
            <Form
                :validation-schema="schema"
                class="min-w-[600px]"
                @submit="handleSubmit"
            >
                <div class="flex flex-col mb-2">
                    <label for="name" class="mb-1"
                        >Tên sản phẩm <span class="text-red-500">*</span></label
                    >
                    <Field
                        name="name"
                        id="name"
                        class="outline-none border border-gray-300 rounded-md px-3 py-2 text-sm"
                        :model-value="product?.name"
                    />
                    <ErrorMessage
                        name="name"
                        class="text-red-500"
                    ></ErrorMessage>
                </div>
                <!-- <div class="flex flex-col mb-2">
                    <label for="slug" class="mb-1"
                        >Đường dẫn <span class="text-red-500">*</span></label
                    >
                    <Field
                        name="slug"
                        id="slug"
                        class="outline-none border border-gray-300 rounded-md px-3 py-2 text-sm"
                        :model-value="slugText"
                    />
                    <ErrorMessage
                        name="slug"
                        class="text-red-500"
                    ></ErrorMessage>
                </div> -->
                <div class="flex flex-col mb-2">
                    <label for="category_id" class="mb-1"
                        >Danh mục <span class="text-red-500">*</span></label
                    >
                    <Field
                        name="category_id"
                        as="select"
                        id="category_id"
                        class="outline-none border border-gray-300 rounded-md px-3 py-2 text-sm"
                        v-model="currentCategory"
                    >
                        <option value="" disabled>Danh mục</option>
                        <option
                            v-for="item in category"
                            :key="item.id"
                            :value="item.id"
                        >
                            {{ item.name }}
                        </option>
                    </Field>
                    <ErrorMessage
                        name="category_id"
                        class="text-red-500"
                    ></ErrorMessage>
                </div>
                <!-- thuộc tính sản phẩm -->
                <div
                    class="flex flex-col mb-2"
                    v-for="(attribute, index) in attributes"
                    :key="attribute.id"
                >
                    <label :for="`attribute_${attribute.id}`" class="mb-1">{{
                        attribute.name
                    }}</label>
                    <select
                        v-if="modalMode == 1"
                        ref="attributeElems"
                        name="attribute"
                        :id="`attribute_${attribute.id}`"
                        class="outline-none border border-gray-300 rounded-md px-3 py-2 text-sm"
                    >
                        <option
                            v-for="item in attribute.values"
                            :key="item.id"
                            :value="item.id"
                        >
                            {{ item.value }}
                        </option>
                    </select>
                    <select
                        v-else-if="modalMode == 2"
                        v-model="
                            product.attribute_values[index].pivot
                                .attribute_value_id
                        "
                        ref="attributeElems"
                        name="attribute"
                        :id="`attribute_${attribute.id}`"
                        class="outline-none border border-gray-300 rounded-md px-3 py-2 text-sm"
                    >
                        <option
                            v-for="item in attribute.values"
                            :key="item.id"
                            :value="item.id"
                        >
                            {{ item.value }}
                        </option>
                    </select>
                </div>
                <!-- Hết thuộc tính sản phẩm -->
                <div class="flex flex-col mb-2 h-[280px]">
                    <label for="description" class="mb-1">Mô tả</label>
                    <QuillEditor
                        ref="editorRef"
                        class="overflow-auto"
                        id="description"
                        content-type="html"
                        :content="product?.description"
                        :toolbar="toolbar"
                    />
                </div>
                <div class="flex flex-col mb-2">
                    <label class="mb-1"
                        >Ảnh sản phẩm <span class="text-red-500">*</span>
                        <span class="text-gray-400">
                            (Tối đa 1 ảnh bìa và 5 ảnh sản phẩm)
                        </span></label
                    >
                    <div class="flex gap-x-2">
                        <InputImage
                            :number="1"
                            ref="thumbRef"
                            text="Ảnh bìa"
                            :data="
                                product?.thumb
                                    ? ['/storage/' + product.thumb]
                                    : undefined
                            "
                        />
                        <InputImage
                            :number="5"
                            ref="imagesRef"
                            :data="
                                product?.product_image?.map(
                                    (item) => '/storage/' + item.path
                                )
                            "
                        />
                    </div>
                    <p v-if="errorMessage.images" class="text-red-500">
                        {{ errorMessage.images }}
                    </p>
                </div>
                <!-- <div class="mb-2" v-if="modalMode == 2">
                    <p>Trạng thái</p>
                    <div class="flex items-center gap-x-3">
                        <input
                            type="checkbox"
                            id="show"
                            class="w-4 h-4 cursor-pointer"
                            v-model="product.show"
                        />
                        <label for="show" class="cursor-pointer"
                            >Đang bán</label
                        >
                    </div>
                </div> -->
                <div class="flex mb-2" v-if="modalMode == 1">
                    <input
                        type="checkbox"
                        name="hasManyVariation"
                        id="hasManyVariation"
                        class="mr-2"
                        v-model="hasManyVariation"
                    />
                    <label
                        for="hasManyVariation"
                        class="cursor-pointer select-none"
                        >Nhiều biến thể</label
                    >
                </div>
                <!-- thông tin chi tiết sản phẩm -->
                <div v-if="!hasManyVariation && modalMode == 1">
                    <div class="flex flex-col mb-2">
                        <label for="price"
                            >Giá <span class="text-red-500">*</span></label
                        >
                        <input
                            name="price"
                            id="price"
                            type="number"
                            class="outline-none border border-gray-300 rounded-md px-3 py-2 text-sm"
                            @keypress="allowInputNumber"
                            min="1000"
                            v-model="productItem.price"
                        />
                        <p v-if="errorMessage.price" class="text-red-500">
                            {{ errorMessage.price }}
                        </p>
                    </div>
                    <div class="flex flex-col mb-2">
                        <label for="quantity"
                            >Số lượng <span class="text-red-500">*</span></label
                        >
                        <input
                            name="quantity"
                            id="quantity"
                            class="outline-none border border-gray-300 rounded-md px-3 py-2 text-sm"
                            @keypress="allowInputNumber"
                            type="number"
                            min="1"
                            v-model="productItem.quantity"
                        />
                        <p v-if="errorMessage.quantity" class="text-red-500">
                            {{ errorMessage.quantity }}
                        </p>
                    </div>
                    <div class="flex flex-col mb-2">
                        <label for="sku">SKU</label>
                        <input
                            name="sku"
                            id="sku"
                            class="outline-none border border-gray-300 rounded-md px-3 py-2 text-sm"
                            v-model="productItem.sku"
                        />
                    </div>
                </div>

                <!-- Phần thông tin biến thể sản phẩm -->
                <VariationForm
                    :error-message="errorMessage"
                    :variations="variations"
                    :mode="0"
                    v-if="hasManyVariation && modalMode == 1"
                    ref="productVariationComponent"
                />
                <!-- phần biến thể cho việc cập nhật sản phẩm -->
                <div
                    class="flex flex-col mb-2"
                    v-if="hasManyVariation && modalMode == 2"
                >
                    <Panel title="Biến thể" :init-state="true">
                        <div class="flex flex-col justify-center gap-3">
                            <div
                                class="p-3 flex flex-col bg-gray-200 rounded-lg relative"
                                v-for="variation in variations"
                                :key="variation.id"
                            >
                                <div class="flex flex-col gap-1 mb-1">
                                    <label class=""
                                        >Tên biến thể
                                        <span class="text-red-500"
                                            >*</span
                                        ></label
                                    >
                                    <input
                                        type="text"
                                        class="border border-gray-300 rounded-lg py-1 px-2 outline-none bg-gray-300 text-gray-500"
                                        v-model="variation.name"
                                        :readonly="true"
                                    />
                                </div>
                                <div class="flex flex-col gap-1">
                                    <label
                                        >Giá trị biến thể
                                        <span class="text-red-500"
                                            >*</span
                                        ></label
                                    >
                                    <div class="flex gap-2">
                                        <span
                                            class="px-3 py-2 bg-gray-300 rounded-lg relative"
                                            v-for="op in variation?.option"
                                        >
                                            {{ op.value }}
                                            <button
                                                v-if="
                                                    variation?.option?.length >
                                                    1
                                                "
                                                type="button"
                                                class="absolute -top-1 -right-1 bg-red-500 rounded-full w-[16px] h-[16px] text-center leading-4 text-white"
                                                @click="
                                                    () => {
                                                        showDeleteOptionDialog(
                                                            op,
                                                            2
                                                        );
                                                    }
                                                "
                                            >
                                                x
                                            </button>
                                        </span>
                                        <button
                                            type="button"
                                            class="px-3 py-1 flex items-center justify-center bg-green-500 rounded-lg text-white text-xl"
                                            @click="
                                                createVariationOption(
                                                    $event,
                                                    variation,
                                                    2
                                                )
                                            "
                                        >
                                            +
                                        </button>
                                    </div>
                                </div>
                                <div
                                    class="flex justify-center items-center absolute top-1 right-3"
                                >
                                    <Button
                                        v-if="variations.length > 1"
                                        class="px-3 py-1 bg-red-500 rounded-lg text-white font-medium"
                                        :handle-click="
                                            () =>
                                                showDeleteOptionDialog(
                                                    variation,
                                                    1
                                                )
                                        "
                                    >
                                        x
                                    </Button>
                                </div>
                            </div>

                            <div class="flex justify-center">
                                <button
                                    class="p-2 border border-dashed border-blue-500 rounded-lg text-blue-500"
                                    type="button"
                                    @click="
                                        () => {
                                            createVariationOption(
                                                $event,
                                                null,
                                                1
                                            );
                                        }
                                    "
                                    v-if="variations.length < 3"
                                >
                                    Thêm biến thể
                                    {{ `(${variations.length}/3)` }}
                                </button>
                            </div>
                        </div>
                    </Panel>
                </div>
                <!-- Biểu mẫu cập nhật sản phẩm -->
                <div class="mb-2" v-if="modalMode == 2">
                    <Panel
                        v-for="item in product?.product_item"
                        :key="item.id"
                        :init-state="true"
                        :title="
                            item.variation_option.map((e) => e.value).join('-')
                        "
                        class="mb-2"
                    >
                        <div class="flex justify-between gap-4">
                            <div class="w-full">
                                <div class="flex flex-col gap-1">
                                    <label>SKU</label>
                                    <input
                                        type="text"
                                        class="outline-none border border-gray-300 rounded-md px-2"
                                        v-model="item.sku"
                                    />
                                </div>
                                <div class="flex flex-col gap-1">
                                    <label>Giá</label>
                                    <input
                                        type="text"
                                        class="outline-none border border-gray-300 rounded-md px-2"
                                        v-model="item.price"
                                    />
                                </div>
                                <div class="flex flex-col gap-1">
                                    <label>Tồn kho</label>
                                    <input
                                        type="text"
                                        class="outline-none border border-gray-300 rounded-md px-2"
                                        v-model="item.quantity"
                                    />
                                </div>
                                <div class="mt-2">
                                    <button
                                        type="button"
                                        v-if="product?.product_item.length > 1"
                                        class="block ml-auto px-3 py-2 bg-red-500 outline-none text-white rounded-lg"
                                        @click="
                                            () => {
                                                item.name =
                                                    item.variation_option
                                                        .map((e) => e.value)
                                                        .join('-');
                                                showDeleteOptionDialog(item, 3);
                                            }
                                        "
                                    >
                                        Xóa
                                    </button>
                                </div>
                            </div>
                        </div>
                    </Panel>
                </div>
                <!--Hết phần thông tin biến thể sản phẩm -->
                <!-- Kích thước sản phẩm -->
                <Panel title="Kích thước sản phẩm" init-state>
                    <div class="flex flex-col gap-1 mb-2">
                        <label
                            >Khối lượng <span class="text-sm">(g)</span>
                            <span class="text-red-500">*</span>
                        </label>
                        <Field
                            v-if="modalMode == 1"
                            name="weight"
                            class="outline-none border border-gray-300 rounded-md px-2"
                        />
                        <Field
                            v-else
                            name="weight"
                            class="outline-none border border-gray-300 rounded-md px-2"
                            :model-value="product.weight"
                        />
                        <ErrorMessage
                            name="weight"
                            class="text-red-500"
                        ></ErrorMessage>
                    </div>
                    <div class="flex gap-1">
                        <div class="flex flex-col gap-1 flex-1">
                            <label
                                >Chiều cao
                                <span class="text-sm">(cm)</span></label
                            >
                            <Field
                                v-if="modalMode == 1"
                                name="height"
                                class="outline-none border border-gray-300 rounded-md px-2"
                            />
                            <Field
                                v-else
                                name="height"
                                class="outline-none border border-gray-300 rounded-md px-2"
                                :model-value="product.height"
                            />
                            <ErrorMessage
                                name="height"
                                class="text-red-500"
                            ></ErrorMessage>
                        </div>
                        <div class="flex flex-col gap-1 flex-1">
                            <label
                                >Chiều dài
                                <span class="text-sm">(cm)</span></label
                            >
                            <Field
                                v-if="modalMode == 1"
                                name="length"
                                class="outline-none border border-gray-300 rounded-md px-2"
                            />
                            <Field
                                v-else
                                name="length"
                                class="outline-none border border-gray-300 rounded-md px-2"
                                :model-value="product.length"
                            />
                            <ErrorMessage
                                name="length"
                                class="text-red-500"
                            ></ErrorMessage>
                        </div>
                        <div class="flex flex-col gap-1 flex-1">
                            <label
                                >Chiều rộng
                                <span class="text-sm">(cm)</span></label
                            >
                            <Field
                                v-if="modalMode == 1"
                                name="width"
                                class="outline-none border border-gray-300 rounded-md px-2"
                            />
                            <Field
                                v-else
                                name="width"
                                class="outline-none border border-gray-300 rounded-md px-2"
                                :model-value="product.width"
                            />
                            <ErrorMessage
                                name="width"
                                class="text-red-500"
                            ></ErrorMessage>
                        </div>
                    </div>
                </Panel>
                <!-- Hêt phần kích thước sản phẩm -->
                <button type="submit" :v-show="false" ref="btnSubmit"></button>
                <Modal
                    ref="optionDeleteModal"
                    :title="optionDeleteData.title"
                    @accept-click="
                        () => {
                            if (optionDeleteModalData.mode == 1) {
                                deleteVariation(optionDeleteModalData.data);
                            } else if (optionDeleteModalData.mode == 2) {
                                removeOption(optionDeleteModalData.data);
                            } else if (optionDeleteModalData.mode == 3) {
                                removeProductItem(optionDeleteModalData.data);
                            }
                        }
                    "
                >
                    <p>{{ optionDeleteData.content }}</p>
                </Modal>
            </Form>
            <Modal
                ref="optionModal"
                :title="optionVariationFormData.title"
                @accept-click="submitVariationOption"
            >
                <VariationForm
                    ref="variationComponent"
                    :mode="optionVariationFormData.modeVariationTable"
                    :variations="optionVariationFormData.variations"
                    :max-option="optionVariationFormData.maxOption"
                    :variation="optionVariationFormData.variation"
                    :error-message="errorMessage"
                />
            </Modal>
        </Modal>
        <Modal
            ref="deleteDialog"
            :title="deleteDialogData.title"
            danger
            @accept-click="requestDelete"
        >
            <p class="text-center">
                {{ deleteDialogData.content }}
            </p>
        </Modal>
        <Loading v-if="isLoading"></Loading>
    </div>
</template>

<script setup>
import Button from "@/components/Button.vue";
import Modal from "@/components/Modal.vue";
import Table from "@/components/admin/Table.vue";
import InputImage from "@/components/InputImage.vue";
import { QuillEditor } from "@vueup/vue-quill";
import * as yup from "yup";
import { ErrorMessage, Field, Form } from "vee-validate";
import { computed, reactive, ref, watch } from "vue";
import createAxios from "@/api/axios";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
// import { removeVI } from "jsrmvi";
import debounce from "@/helper/debounce";
import moment from "moment";
import Loading from "@/components/Loading.vue";
import Pagination from "@/components/Pagination.vue";
import Panel from "@/components/Panel.vue";
import "@vueup/vue-quill/dist/vue-quill.snow.css";
// import BlotFormatter from "quill-blot-formatter";
import VariationForm from "@/components/admin/VariationForm.vue";

// const modules = {
//     module: BlotFormatter,
// };
const toolbar = {
    container: [
        ["bold", "italic", "underline", "strike"],
        [{ align: [] }],
        [{ size: ["small", false, "large", "huge"] }],
        ["link"],
        [{ list: "ordered" }, { list: "bullet" }],
        ["clean"],
    ],
};

const schema = yup.object({
    name: yup.string().required("Trường này là bắt buộc"),
    category_id: yup.number().typeError("Danh mục không hợp lệ"),
    varitaion: yup
        .array()
        .max(3, "Chỉ có thể tạo tối đa ${max} biến thể")
        .min(1, "Trường này là bắt buộc"),
    weight: yup
        .number()
        .required("Thông tin này là bắt buộc")
        .typeError("Cân nặng không hợp lệ"),
    height: yup.string().matches(/^\d*$/, "Chiều cao không hợp lệ"),
    length: yup.string().matches(/^\d*$/, "Chiều dài không hợp lệ"),
    width: yup.string().matches(/^\d*$/, "Chiều rộng không hợp lệ"),
});
const hasManyVariation = ref(false);
const modal = ref(null);
const btnSubmit = ref(null);
const modalTitle = ref("Thêm danh mục");
const modalMode = ref(1); // 1 is create,2 is update
const api = createAxios();
const listData = ref([]);
const category = ref([]);
const currentCategory = ref("");
const tableHeader = ["#", "Tên sản phẩm", "Danh mục", "Ngày tạo", "Thao tác"];
const deleteDialogData = reactive({
    title: "",
    content: "",
});
const deleteDialog = ref();
const searchText = ref("");
const isLoading = ref(true);
const paginator = reactive({
    page: 1,
    length: 10,
    range: 5, // so nut phan tran hien thi
    total: 0,
    //start = (page - 1) * length
});
const optionModal = ref();
const variations = ref([{}]);
const optionVariationFormData = reactive({
    title: "Thêm biến thể",
    mode: 1,
    modeVariationTable: 0,
    variations: [{}],
    variation: {},
    maxOption: undefined,
});
const productItems = ref([]);
const editorRef = ref();
const thumbRef = ref();
const imagesRef = ref();
const product = ref();
const attributes = ref([]);
const attributeElems = ref([]);
let timeoutId;
const errorMessage = ref({
    quantity: "",
    price: "",
    images: "",
    variations: new Map(),
    variationOptions: new Map(),
});
const productItem = ref({
    sku: "",
    price: 1000,
    quantity: 1,
});
const productVariationComponent = ref(null);
const variationComponent = ref(null);
const optionDeleteData = reactive({
    title: "Cảnh báo",
    content: "",
});
const optionDeleteModal = ref();
const optionDeleteModalData = reactive({
    title: "",
    content: "",
    mode: 1,
    data: {},
});
const hideOrShowButton = ref({
    text: "",
    class: "",
});

watch(
    searchText,
    () => {
        clearTimeout(timeoutId);
        timeoutId = debounce(getListProduct, 1000);
    },
    { immediate: true }
);

watch(currentCategory, (newValue) => {
    loadAttribute(newValue);
});

watch(hasManyVariation, (newValue) => {
    if (newValue) {
        // productItem.value = null;
    } else {
        variations.value = [];
        productItems.value = [];
    }
});

const listCategory = computed(() => {
    return listData.value.map((item, index) => ({
        order: index + 1,
        name: item.name,
        category: item.category?.name ?? "Không có",
        created_at: moment(item.created_at).format("DD/MM/YYYY HH:mm:ss"),
    }));
});

async function loadAttribute(idCategory) {
    isLoading.value = true;
    const tmp = await api.attribute.getByCategory(
        idCategory == 0 ? 0 : idCategory
    );
    attributes.value = tmp?.data;
    isLoading.value = false;
}

function loadVariation(value) {
    const tmp = category.value.find((item) => {
        return item.id === value;
    });
    variations.value = tmp.variation;
}

function allowInputNumber(e) {
    if (!((e.keyCode >= 48 && e.keyCode <= 57) || e.keyCode === 8)) {
        e.preventDefault();
    }
}

function openModal(mode) {
    if (mode == "create") {
        modalTitle.value = "Thêm sản phẩm";
        modalMode.value = 1;
    } else if ((mode = "edit")) {
        modalTitle.value = "Chi tiết sản phẩm";
        modalMode.value = 2;
        console.log(modalMode.value);
    } else {
        throw new Error("action invalid");
    }
    modal.value.status = true;
}

function closeModal() {
    modal.value.status = false;
}

function handleSubmit(value, meta) {
    // console.log(value);
    let valid = true;

    const form = new FormData();

    switch (modalMode.value) {
        case 1:
            // validate
            productItems.value =
                productVariationComponent.value?.productItems ?? [];
            variations.value =
                productVariationComponent.value?.variations ?? [];
            const chipData =
                productVariationComponent.value?.chipData ?? new Map();
            const chips = productVariationComponent.value?.chips ?? [];

            if (
                !thumbRef.value?.images.length ||
                !imagesRef.value?.images.length
            ) {
                errorMessage.value.images = "Trường này là bắt buộc";
                valid = false;
            } else {
                errorMessage.value.images = "";
            }

            if (!hasManyVariation.value) {
                if (!productItem.value?.price) {
                    errorMessage.value.price = "Trường này là bắt buộc";
                    valid = false;
                } else {
                    errorMessage.value.price = "";
                }

                if (!productItem.value?.quantity) {
                    errorMessage.value.quantity = "Trường này là bắt buộc";
                    valid = false;
                } else {
                    errorMessage.value.quantity = "";
                }
            } else {
                if (!variations.value?.length) {
                    toast.error("Bạn chưa thêm biến thể");
                    valid = false;
                }

                variations.value &&
                    variations.value.forEach((item, index) => {
                        if (!item.name.length) {
                            errorMessage.value.variations.set(
                                index,
                                "Trường này là bắt buộc"
                            );
                            valid = false;
                            return;
                        } else {
                            valid = true;
                            errorMessage.value.variations.clear(index);
                        }

                        if (!chips[index].data?.length) {
                            errorMessage.value.variationOptions.set(
                                index,
                                "Trường này là bắt buộc"
                            );
                            valid = false;
                            return;
                        } else {
                            valid = true;
                            errorMessage.value.variationOptions.clear(index);
                        }
                    });
            }

            if (!valid) {
                return;
            }
            const thumb = thumbRef.value.images[0];
            const images = imagesRef.value.images;
            const models = [...productItems.value];
            const attributeValues = [];

            for (const key in value) {
                form.append(key, value[key]);
            }
            form.append("description", editorRef.value.getHTML());
            form.append("status", 0);
            form.append("thumb", thumb);
            form.append(
                "variation",
                JSON.stringify(Object.fromEntries(chipData))
            );
            if (!hasManyVariation.value) {
                models.push({
                    isCheck: true,
                    ...productItem.value,
                });
            }

            form.append("product_item", JSON.stringify(models));

            images.forEach((item, index) => {
                form.append(`product_image_${index}`, item);
            });

            form.append("total_image", images.length);

            attributeElems.value.forEach((item) => {
                attributeValues.push(item.value);
            });
            form.append("attribute_values", JSON.stringify(attributeValues));
            form.append("weight", value.weight);
            form.append("height", value.height ?? 0);
            form.append("length", value.length ?? 0);
            form.append("width", value.width ?? 0);

            isLoading.value = true;
            api.product
                .create(form, {
                    "Content-Type": "multipart/form-data",
                })
                .then((res) => {
                    if (res.data.message == "success") {
                        toast.success("Thành công", {
                            autoClose: 3000,
                        });
                        closeModal();
                        getListProduct();
                    }
                })
                .catch((error) => {
                    toast.error(error.message);
                })
                .finally(() => {
                    isLoading.value = false;
                });
            break;
        case 2:
            //validate
            if (
                !thumbRef.value?.images.length ||
                !imagesRef.value?.images.length
            ) {
                errorMessage.value.images = "Bạn chưa thêm ảnh";
                valid = false;
            } else {
                errorMessage.value.images = "";
            }

            if (!hasManyVariation.value) {
                if (!productItem.value?.price) {
                    errorMessage.value.price = "Trường này là bắt buộc";
                    valid = false;
                } else {
                    errorMessage.value.price = "";
                }

                if (!productItem.value?.quantity) {
                    errorMessage.value.quantity = "Trường này là bắt buộc";
                    valid = false;
                } else {
                    errorMessage.value.quantity = "";
                }
            } else {
                if (!variations.value?.length) {
                    toast.error("Bạn chưa thêm biến thể");
                    valid = false;
                }
            }

            if (!valid) {
                return;
            }

            isLoading.value = true;

            product.value.name = value.name;
            product.value.slug = value.slug;
            product.value.category_id = value.category_id;
            product.value.description = editorRef.value.getHTML();
            product.value.thumb_deleted = thumbRef.value.deleted[0];
            product.value.images_deleted = imagesRef.value.deleted;
            product.value.weight = value.weight;
            product.value.height = value.height ?? 0;
            product.value.length = value.length ?? 0;
            product.value.width = value.width ?? 0;

            form.append("data", JSON.stringify(product.value));
            form.append("file_thumb", thumbRef.value.images[0]);

            imagesRef.value.images.forEach((item, index) => {
                form.append(`file_image_${index}`, item);
            });
            form.append("total_image", imagesRef.value.images.length);

            api.product
                .update(product.value.id, form, {
                    "Content-Type": "multipart/form-data",
                })
                .then((res) => {
                    if (res.data.message == "success") {
                        toast.success("Thành công", {
                            autoClose: 3000,
                        });
                        getListProduct();
                    }
                })
                .catch((error) => {
                    toast.error(error.message);
                })
                .finally(() => {
                    isLoading.value = false;
                    closeModal();
                });
            break;
        default:
            break;
    }
}

function handleAccept() {
    btnSubmit.value.click();
}

// function generateSlug(value) {
//     slugText.value = removeVI(value);
// }
/**get list product */
function getListProduct() {
    if (!isLoading.value) {
        isLoading.value = true;
    }
    api.product
        .getList(searchText.value, paginator.page, paginator.length)
        .then((res) => {
            listData.value = res.data.data;
            paginator.total = Math.ceil(res.data.total / paginator.length);
            if (isLoading.value) {
                isLoading.value = false;
            }
        })
        .catch((error) => {
            toast.error(error.message);
            if (isLoading.value) {
                isLoading.value = false;
            }
        });
}

function getCategory() {
    return api.category.getList(api.category.PRODUCT, "");
}

async function handleCreate() {
    try {
        // slugText.value = "";
        productItem.value = {
            sku: "",
            price: 1000,
            quantity: 1,
        };
        hideOrShowButton.value = null;
        currentCategory.value = "";
        productItems.value = [];
        variations.value = [];
        product.value = null;
        hasManyVariation.value = false;
        isLoading.value = true;
        const response = await getCategory();
        isLoading.value = false;
        category.value = response.data.data;
        openModal("create");
    } catch (error) {
        toast.error(error.message);
        isLoading.value = false;
    }
}

async function handleEdit(index) {
    try {
        currentCategory.value = "";
        productItems.value = [];
        variations.value = [];
        const elem = listData.value[index];
        isLoading.value = true;
        const res = await getCategory();
        category.value = res.data.data;
        const resProduct = await api.product.getProduct(elem.id);
        product.value = resProduct.data;
        hasManyVariation.value = product.value?.variations?.length
            ? true
            : false;
        variations.value = product.value.variations.slice();
        hideOrShowButton.value = {
            text: "",
            class: "",
        };
        if (product.value.status == 1) {
            // 1 là hiện
            hideOrShowButton.value.text = "Ẩn sản phẩm";
            hideOrShowButton.value.class = "bg-red-500";
        } else if (product.value.status == 2) {
            // 2 là ẩn
            hideOrShowButton.value.text = "Hiện sản phẩm";
            hideOrShowButton.value.class = "bg-green-500";
        }
        // slugText.value = product.value.slug;
        currentCategory.value = product.value.category_id;
        isLoading.value = false;
        openModal("edit");
    } catch (error) {
        toast.error(error.message);
        isLoading.value = false;
    }
}

function handleDelete(index) {
    const elem = listData.value[index];
    product.value = elem;
    deleteDialogData.title = "Xoá sản phẩm ";
    deleteDialogData.content = `Bạn có muốn xoá sản phẩm ${elem.name} không?`;
    deleteDialog.value.status = true;
}

function requestDelete() {
    api.product
        .delete(product.value.id)
        .then((res) => {
            if (res.data.message == "success") {
                toast.success("Xoá thành công");
                getListProduct();
            }
        })
        .catch((error) => {
            toast.error(error.message);
        })
        .finally(() => {
            deleteDialog.value.status = false;
        });
}

function handleChangePage(page) {
    paginator.page = page;
    getListProduct(api.category.PRODUCT);
}

function createVariationOption(e, item, modeVariationTable = 0) {
    optionVariationFormData.mode = 1;
    optionModal.value.status = true;
    optionVariationFormData.modeVariationTable = modeVariationTable;
    optionVariationFormData.variations = variations;
    optionVariationFormData.variation = item;
    if (item) optionVariationFormData.maxOption = 10 - item.option.length;
}

function submitVariationOption() {
    const component = variationComponent.value;
    const data = {
        product_id: product.value?.id,
        product_items: component.productItems,
        variation_options: component.chips[0].data,
    };
    isLoading.value = true;
    switch (optionVariationFormData.modeVariationTable) {
        case 1:
            const variation = component.variations[0];
            data.variation = variation;
            console.log(data);
            api.variation
                .create(data)
                .then(async (res) => {
                    if (res.data.message == "success") {
                        toast.success("Thành công");
                        optionModal.value.status = false;
                        const resProduct = await api.product.getProduct(
                            product.value.id
                        );
                        product.value = resProduct.data;
                        variations.value = product.value.variations;
                    }
                })
                .catch((error) => {
                    const message =
                        error.response.data.code == 2406
                            ? error.response.data?.message
                            : error.message;
                    toast.error(message);
                })
                .finally(() => {
                    isLoading.value = false;
                });
            break;
        case 2:
            const variation_id = optionVariationFormData.variation?.id;
            data.variation_id = variation_id;
            console.log(data);
            api.variationOption
                .create(data)
                .then(async (res) => {
                    isLoading.value = true;
                    if (res.data.message == "success") {
                        toast.success("Thành công");
                        optionModal.value.status = false;
                        const resProduct = await api.product.getProduct(
                            product.value.id
                        );
                        product.value = resProduct.data;
                        variations.value = product.value.variations;
                    }
                })
                .catch((error) => {
                    const message =
                        error.response.data.code == 2406
                            ? error.response.data?.message
                            : error.message;
                    toast.error(message);
                })
                .finally(() => {
                    isLoading.value = false;
                });
            break;
        default:
            isLoading.value = false;
            break;
    }
}

function deleteVariation(variation) {
    console.log(variation);
    isLoading.value = true;
    api.variation
        .delete(variation.id)
        .then(async (res) => {
            if (res.data.message == "success") {
                toast.success("Xoá thành công");
                optionDeleteModal.value.status = false;
                const resProduct = await api.product.getProduct(
                    product.value.id
                );
                product.value = resProduct.data;
                variations.value = product.value.variations;
            }
            return getCategory(currentCategory.value.id);
        })
        .catch((error) => {
            if (error.response.data.code == 23000) {
                toast.error(
                    "Không thể xoá thuộc tính do đã có sản phẩm liên kết"
                );
            } else {
                toast.error(error.message);
            }
        })
        .finally(() => {
            isLoading.value = false;
        });
}

function removeOption(item) {
    isLoading.value = true;
    api.variationOption
        .delete(item.id)
        .then(async (res) => {
            if (res.data.message == "success") {
                toast.success("Xoá thành công");
                optionDeleteModal.value.status = false;
                const resProduct = await api.product.getProduct(
                    product.value.id
                );
                product.value = resProduct.data;
                variations.value = product.value.variations;
            }
        })
        .catch((error) => {
            toast.error(error.message);
        })
        .finally(() => {
            isLoading.value = false;
            optionDeleteModal.value.status = false;
        });
}

function showDeleteOptionDialog(data, mode) {
    if (mode == 1) {
        //1 là xóa biến thể
        optionDeleteModal.value.status = true;
        optionDeleteModalData.mode = 1;
        optionDeleteModalData.data = data;
        optionDeleteData.content = `Xóa biến thể "${data.name}" sẽ xóa tất cả các biến thể sản phẩm có liên kết với nó. Tiếp tục?`;
    } else if (mode == 2) {
        //2 là xóa giá trị biến thể
        optionDeleteModal.value.status = true;
        optionDeleteModalData.data = data;
        optionDeleteModalData.mode = 2;
        optionDeleteData.content = `Xóa giá trị "${data.value}" sẽ xóa tất cả các biến thể sản phẩm có liên kết với nó. Tiếp tục?`;
    } else if (mode == 3) {
        //3 là biến thể sản phẩm
        optionDeleteModal.value.status = true;
        optionDeleteModalData.data = data;
        optionDeleteModalData.mode = 3;
        optionDeleteData.content = `Bạn có muốn xóa biến thể sản phẩm ${data.name} không?`;
    }
}

async function removeProductItem(data) {
    try {
        isLoading.value = true;
        const response = await api.productItem.delete(data.id);
        if (response.data.message == "success") {
            toast.success("Xoá thành công");
            const resProduct = await api.product.getProduct(product.value.id);
            product.value = resProduct.data;
            variations.value = product.value.variations;
        }
    } catch (error) {
        toast.error(error.message);
    }

    isLoading.value = false;
    optionDeleteModal.value.status = false;
}

async function toggleStatusProduct(item) {
    try {
        const status = product.value.status == 1 ? 2 : 1;
        const res = await api.product.changeStatus(item.id, status);
        if (res.data.message == "success") {
            toast.success("Thành công");
            closeModal();
        }
    } catch (error) {
        toast.error(error.message);
    }
}
</script>

<style lang="scss" scoped></style>
