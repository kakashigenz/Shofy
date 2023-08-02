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
        <Modal ref="modal" :title="modalTitle" @acceptClick="handleAccept">
            <Form
                :validation-schema="schema"
                class="min-w-[600px]"
                @submit="handleSubmit"
            >
                <div class="flex flex-col mb-2">
                    <label for="name" class="mb-1">Tên sản phẩm</label>
                    <Field
                        name="name"
                        id="name"
                        class="outline-none border border-gray-300 rounded-md px-3 py-2 text-sm"
                        @update:model-value="generateSlug"
                        :model-value="product?.name"
                    />
                    <ErrorMessage
                        name="name"
                        class="text-red-500"
                    ></ErrorMessage>
                </div>
                <div class="flex flex-col mb-2">
                    <label for="slug" class="mb-1">Đường dẫn</label>
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
                </div>
                <div class="flex flex-col mb-2">
                    <label for="category_id" class="mb-1">Danh mục</label>
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
                <div class="flex flex-col mb-2 h-[280px]">
                    <label for="description" class="mb-1">Mô tả</label>
                    <QuillEditor
                        ref="editorRef"
                        class="overflow-auto"
                        id="description"
                        content-type="html"
                        :content="product?.description"
                        :modules="modules"
                        :toolbar="toolbar"
                    />
                    <p class="text-red-500"></p>
                </div>
                <div class="flex flex-col mb-2">
                    <label for="description" class="mb-1">Ảnh bìa</label>
                    <InputImage
                        :number="1"
                        ref="thumbRef"
                        :data="
                            product?.thumb
                                ? ['/storage/' + product.thumb]
                                : undefined
                        "
                    />
                    <p class="text-red-500"></p>
                </div>
                <div class="mb-2" v-if="modalMode == 2">
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
                </div>
                <div class="flex flex-col mb-2" v-if="modalMode == 1">
                    <Panel title="Thuộc tính" :init-state="false">
                        <ul>
                            <li
                                v-for="(item, index) in variation"
                                :key="item.id"
                                class="flex items-center p-2"
                            >
                                <p class="min-w-[80px]">{{ item.name }}:</p>
                                <div>
                                    <Chip
                                        ref="chips"
                                        unique
                                        :limit="10"
                                        @change-value="
                                            () => {
                                                changeValueChip(item, index);
                                            }
                                        "
                                    ></Chip>
                                </div>
                            </li>
                        </ul>
                    </Panel>
                </div>
                <div class="mb-2" v-if="productItem.length > 0">
                    <table class="border-collapse w-full">
                        <caption class="bg-gray-300 p-2 text-left">
                            Sản phẩm tương ứng
                        </caption>
                        <thead>
                            <tr>
                                <th class="border border-gray-300 p-1">Chọn</th>
                                <th class="border border-gray-300 p-1">Ảnh</th>
                                <th class="border border-gray-300 p-1">
                                    Thuộc tính
                                </th>
                                <th class="border border-gray-300 p-1">SKU</th>
                                <th class="border border-gray-300 p-1">Giá</th>
                                <th class="border border-gray-300 p-1">
                                    Tồn kho
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(item, index) in productItem"
                                :key="index"
                            >
                                <td class="border border-gray-300">
                                    <div class="flex justify-center">
                                        <input
                                            type="checkbox"
                                            class="h-4 w-4 cursor-pointer"
                                            v-model="item.isCheck"
                                        />
                                    </div>
                                </td>
                                <td class="border border-gray-300">
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
                                </td>
                                <td class="border border-gray-300 text-center">
                                    {{ item.variation.join("-") }}
                                </td>
                                <td class="border border-gray-300 p-1">
                                    <div class="flex">
                                        <input
                                            type="text"
                                            class="px-2 py-1 border border-gray-300 outline-none"
                                            v-model="item.sku"
                                        />
                                    </div>
                                </td>
                                <td class="border border-gray-300 p-1">
                                    <div class="flex">
                                        <input
                                            type="text"
                                            class="p-1 border border-gray-300 outline-none"
                                            v-model="item.price"
                                            @keypress="allowInputNumber"
                                        />
                                        <span class="border border-gray-300 p-1"
                                            >đ</span
                                        >
                                    </div>
                                </td>
                                <td class="border border-gray-300">
                                    <div class="flex">
                                        <input
                                            type="text"
                                            class="px-2 py-1 border border-gray-300 outline-none"
                                            v-model="item.quantity"
                                            @keypress="allowInputNumber"
                                        />
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
                            <div class="w-[30%]">
                                <InputImage
                                    :number="1"
                                    :data="
                                        item.image
                                            ? ['/storage/' + item.image]
                                            : undefined
                                    "
                                    ref="productImage"
                                />
                            </div>
                            <div class="w-[70%]">
                                <div class="flex flex-col gap-1">
                                    <label>SKU</label>
                                    <input
                                        type="text"
                                        class="outline-none border border-gray-300 rounded-md px-2"
                                        v-model="item.SKU"
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
                                <div>
                                    <p>Trạng thái</p>
                                    <div class="flex items-center gap-x-3">
                                        <input
                                            type="checkbox"
                                            class="w-4 h-4 cursor-pointer"
                                            v-model="item.show"
                                        />
                                        <label>Đang bán</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 border mt-6 flex flex-col gap-y-3">
                            <div
                                v-for="(vari, index) in variation"
                                :key="vari.id"
                                class="flex"
                            >
                                <label class="mr-3 min-w-[80px]"
                                    >{{ vari.name }}:</label
                                >
                                <SelectInput
                                    v-model="item.variation_option[index].id"
                                    :options="vari.option"
                                >
                                    <template #remove="prop">
                                        <button
                                            type="button"
                                            class="text-sm rounded-full bg-red-500 w-4 h-4 text-white leading-[14px] text-center"
                                            @click.stop="
                                                removeOption($event, prop.item)
                                            "
                                        >
                                            x
                                        </button>
                                    </template>
                                </SelectInput>
                                <button
                                    type="button"
                                    class="outline-none flex items-center ml-2 border rounded-lg border-gray-300"
                                    @click="createVariationOption($event, vari)"
                                >
                                    <box-icon name="plus"></box-icon>
                                </button>
                            </div>
                            <div class="p-10">
                                <Button :handle-click="handleDelete">Xoá sản phẩm này</Button>
                            </div>
                        </div>
                    </Panel>
                </div>
                <button type="submit" :v-show="false" ref="btnSubmit"></button>
            </Form>
            <Modal
                ref="optionModal"
                :title="optionVariation.title"
                @accept-click="
                    () => {
                        optionSubmitBtn.click();
                    }
                "
            >
                <Form
                    v-if="optionVariation.mode == 1"
                    :validation-schema="optionVariation.schema"
                    @submit="submitVariationOption"
                >
                    <label class="mr-2">Giá trị</label>
                    <Field
                        name="variation_option"
                        class="px-2 outline-none border border-gray-300"
                    />
                    <ErrorMessage name="variation_option" />
                    <button class="hidden" ref="optionSubmitBtn"></button>
                </Form>
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
import Chip from "@/components/Chip.vue";
import InputImage from "@/components/InputImage.vue";
import { QuillEditor } from "@vueup/vue-quill";
import * as yup from "yup";
import { ErrorMessage, Field, Form } from "vee-validate";
import { computed, reactive, ref, watch } from "vue";
import createAxios from "@/api/axios";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import { removeVI } from "jsrmvi";
import debounce from "@/helper/debounce";
import moment from "moment";
import Loading from "@/components/Loading.vue";
import Pagination from "@/components/Pagination.vue";
import Panel from "@/components/Panel.vue";
import "@vueup/vue-quill/dist/vue-quill.snow.css";
import BlotFormatter from "quill-blot-formatter";
import SelectInput from "@/components/admin/SelectInput.vue";

const modules = {
    module: BlotFormatter,
};
const toolbar = {
    container: [
        ["bold", "italic", "underline", "strike"],
        [{ align: [] }],
        [{ size: ["small", false, "large", "huge"] }],
        ["link", "image"],
        [{ list: "ordered" }, { list: "bullet" }],
        ["clean"],
    ],
};

const schema = yup.object({
    name: yup.string().required("Trường này là bắt buộc"),
    category_id: yup.number().typeError("Danh mục không hợp lệ"),
    slug: yup.string().required("Trường này là bắt buộc"),
    varitaion: yup
        .array()
        .max(3, "Chỉ có thể tạo tối đa ${max} biến thể")
        .min(1, "Trường này là bắt buộc"),
});
const modal = ref(null);
const btnSubmit = ref(null);
const modalTitle = ref("Thêm danh mục");
const modalMode = ref(1); // 1 is create,2 is update
const api = createAxios();
const slugText = ref("");
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
const chips = ref();
const chipData = ref(new Map()); // data optionVariation in chip
const paginator = reactive({
    page: 1,
    length: 10,
    range: 5, // so nut phan tran hien thi
    total: 0,
    //start = (page - 1) * length
});
const optionModal = ref();
const variation = ref();
const optionVariation = reactive({
    title: "Thêm giá trị thuộc tính",
    schema: yup.object({
        variation_option: yup.string().required("Trường này là bắt buộc"),
    }),
    mode: 1,
    value: "",
    id: undefined,
});
const optionSubmitBtn = ref();
const productItem = ref([]);
const editorRef = ref();
const thumbRef = ref();
const productImage = ref([]);
const product = ref();

watch(
    searchText,
    () => {
        debounce(getListCategory, 1000);
    },
    { immediate: true }
);

watch(currentCategory, (newValue) => {
    if (newValue) {
        loadVariation(newValue);
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

function changeValueChip(parentVariation, index) {
    const data = {
        variation: parentVariation.id,
        option: chips.value[index].data,
    };
    chipData.value.set(parentVariation.id, data);
}

function loadVariation(value) {
    const tmp = category.value.find((item) => {
        return item.id === value;
    });
    variation.value = tmp.variation;
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
                        variation: [item],
                    };
                });
            } else {
                const tmp = [...res];
                res = [];
                for (const first of tmp) {
                    for (const second of elem[1].option) {
                        res.push({
                            isCheck: true,
                            sku: "",
                            price: 1000,
                            quantity: 1,
                            variation: [...first.variation, second],
                        });
                    }
                }
            }
        }
        productItem.value = [...res];
    },
    { deep: true }
);

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
        modalTitle.value = "Sửa sản phẩm";
        modalMode.value = 2;
    } else {
        throw new Error("action invalid");
    }
    modal.value.status = true;
}

function closeModal() {
    modal.value.status = false;
}

function handleSubmit(value) {
    const form = new FormData();

    switch (modalMode.value) {
        case 1:
            isLoading.value = true;

            for (const key in value) {
                form.append(key, value[key]);
            }
            form.append("description", editorRef.value.getHTML());
            form.append("show", true);
            form.append("thumb", thumbRef.value.images[0]);
            form.append(
                "variation",
                JSON.stringify(Object.fromEntries(chipData.value))
            );
            form.append("product_item", JSON.stringify(productItem.value));
            productImage.value.forEach((item, index) => {
                form.append(`product_image_${index}`, item[0]);
            });

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
                        getListCategory(api.category.PRODUCT);
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
            isLoading.value = true;

            product.value.name = value.name;
            product.value.slug = value.slug;
            product.value.category_id = value.category_id;
            product.value.description = editorRef.value.getHTML();
            product.value.thumb_deleted = thumbRef.value.deleted[0];
            productImage.value.forEach((item, index) => {
                form.append(`file_item_${index}`, item.images[0]);
                product.value[`product_image_delete_${index}`] =
                    item.deleted[0];
            });
            form.append("data", JSON.stringify(product.value));
            form.append("file_thumb", thumbRef.value.images[0]);

            api.product
                .update(product.value.id, form, {
                    "Content-Type": "multipart/form-data",
                })
                .then((res) => {
                    if (res.data.message == "success") {
                        toast.success("Thành công", {
                            autoClose: 3000,
                        });
                        closeModal();
                        getListCategory(api.category.PRODUCT);
                    }
                })
                .catch((error) => {
                    toast.error(error.message);
                })
                .finally(() => {
                    isLoading.value = false;
                });
        default:
            break;
    }
}

function handleAccept() {
    btnSubmit.value.click();
}

function generateSlug(value) {
    slugText.value = removeVI(value);
}
/**get list product */
function getListCategory() {
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
        slugText.value = "";
        currentCategory.value = "";
        productItem.value = [];
        variation.value = [];
        product.value = null;
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
        productItem.value = [];
        variation.value = [];
        const elem = listData.value[index];
        isLoading.value = true;
        const res = await getCategory();
        category.value = res.data.data;
        const resProduct = await api.product.getProduct(elem.id);
        product.value = resProduct.data;
        slugText.value = product.value.slug;
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
                getListCategory()
            }
        })
        .catch((error) => {
            if (error.response.data.code == 23000) {
                toast.error(
                    "Không thể xoá danh mục do đã có danh mục con hoặc sản phẩm liên kết"
                );
            } else {
                toast.error(error.message);
            }
        });
    deleteDialog.value.status = false;
}

function handleChangePage(page) {
    paginator.page = page;
    getListCategory(api.category.PRODUCT);
}

function createVariationOption(e, item) {
    optionVariation.mode = 1;
    optionModal.value.status = true;
    optionVariation.id = item.id;
}

function submitVariationOption(value) {
    const data = {
        value: value.variation_option,
        variation_id: optionVariation.id,
    };
    api.variationOption
        .create(data)
        .then((res) => {
            if (res.data.message == "success") {
                toast.success("Thành công");
                optionModal.value.status = false;
            }
            isLoading.value = true;
            return getCategory();
        })
        .then((res) => {
            category.value = res.data.data;
            loadVariation(currentCategory.value);
        })
        .catch((error) => {
            toast.error(error.message);
        })
        .finally(() => {
            isLoading.value = false;
        });
}

// function deleteVariation() {
//     api.variation
//         .delete(variation.id)
//         .then((res) => {
//             if (res.data.message == "success") {
//                 toast.success("Xoá thành công");
//                 variationModal.value.status = false;
//             }
//             return getCategory(currentCategory.value.id);
//         })
//         .then((res) => {
//             currentCategory.value = res.data;
//         })
//         .catch((error) => {
//             if (error.response.data.code == 23000) {
//                 toast.error(
//                     "Không thể xoá thuộc tính do đã có sản phẩm liên kết"
//                 );
//             } else {
//                 toast.error(error.message);
//             }
//         });
// }

function removeOption(e, item) {
    api.variationOption
        .delete(item.id)
        .then((res) => {
            if (res.data.message == "success") {
                toast.success("Xoá thành công");
            }
            isLoading.value = true;
            return getCategory();
        })
        .then((res) => {
            category.value = res.data.data;
            loadVariation(currentCategory.value);
        })
        .catch((error) => {
            if (error.response.data.code == 23000) {
                toast.error(
                    "Không thể xoá giá trị này do đã có sản phẩm liên kết"
                );
            } else {
                toast.error(error.message);
            }
        })
        .finally(() => {
            isLoading.value = false;
        });
}
</script>

<style lang="scss" scoped></style>
