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
                class="min-w-[420px]"
                :on-submit="handleSubmit"
            >
                <div class="flex flex-col mb-2">
                    <label for="name" class="mb-1">Tên danh mục</label>
                    <Field
                        name="name"
                        id="name"
                        @update:model-value="generateSlug"
                        class="outline-none border border-gray-300 rounded-md px-3 py-2 text-sm"
                        :model-value="currentCategory?.name"
                    >
                    </Field>
                    <ErrorMessage name="name" class="text-red-500" />
                </div>
                <div class="flex flex-col mb-2">
                    <label for="slug" class="mb-1">Đường dẫn</label>
                    <Field
                        :model-value="slugText"
                        name="slug"
                        id="slug"
                        class="outline-none border border-gray-300 rounded-md px-3 py-2 text-sm"
                    >
                    </Field>
                    <ErrorMessage name="slug" class="text-red-500" />
                </div>
                <div class="flex flex-col mb-2">
                    <label for="parent_category_id" class="mb-1"
                        >Danh mục cha</label
                    >
                    <Field
                        as="select"
                        name="parent_category_id"
                        id="parent_category_id"
                        class="outline-none border border-gray-300 rounded-md px-3 py-2 text-sm"
                        :model-value="currentCategory?.parent_category_id"
                    >
                        <option value="" selected>Không có</option>
                        <option
                            v-for="(item, index) in parent"
                            :value="item.id"
                            :key="index"
                        >
                            {{ item.name }}
                        </option>
                    </Field>
                    <ErrorMessage
                        name="parent_category_id"
                        class="text-red-500"
                    />
                </div>
                <div v-if="modalMode == 1" class="flex flex-col mb-2">
                    <label for="variation" class="mb-1">Thuộc tính</label>
                    <Field
                        type="hidden"
                        name="variation"
                        :model-value="chipData"
                    ></Field>
                    <Chip
                        ref="chip"
                        @changeValue="handleAddValue"
                        class="border rounded-md border-gray-300 px-3 py-2"
                    />
                    <ErrorMessage name="variation" class="text-red-500" />
                </div>
                <div v-else-if="modalMode == 2" class="flex flex-col mb-2">
                    <Panel title="Thuộc tính" :init-state="true">
                        <ul>
                            <li
                                v-for="item in currentCategory.variation"
                                :key="item.id"
                                class="flex items-center p-2"
                            >
                                <p
                                    class="min-w-[80px] border-b border-gray-300"
                                >
                                    {{ item.name }}
                                </p>
                                <Button
                                    class="ml-2 bg-gray-200"
                                    :handle-click="
                                        (e) => {
                                            editVariation(e, item);
                                        }
                                    "
                                    type="button"
                                >
                                    <box-icon name="edit"></box-icon>
                                </Button>
                            </li>
                            <li class="flex items-center p-2">
                                <button
                                    type="button"
                                    class="border border-dashed border-blue-500 text-blue-500 text-center px-3 py-1 rounded-lg cursor-pointer"
                                    @click="createVariation"
                                >
                                    Thêm thuộc tính
                                </button>
                            </li>
                        </ul>
                    </Panel>
                </div>
                <button type="submit" :v-show="false" ref="btnSubmit"></button>
            </Form>
            <Modal
                ref="variationModal"
                :title="variation.title"
                :custom-button="variation.mode == 2 ? variation.button : null"
                @accept-click="
                    () => {
                        variationSubmitBtn.click();
                    }
                "
                @custom-click="deleteVariation"
            >
                <Form
                    :validation-schema="variation.schema"
                    @submit="submitVariation"
                >
                    <div class="flex flex-col">
                        <label for="variation_name">Tên thuộc tính</label>
                        <Field
                            name="variation_name"
                            id="variation_name"
                            class="outline-none border border-gray-300 rounded-md px-3 py-2 text-sm"
                            :model-value="variation.name"
                        />
                        <ErrorMessage
                            name="variation_name"
                            class="text-red-500"
                        />
                    </div>
                    <button class="hidden" ref="variationSubmitBtn"></button>
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
import * as yup from "yup";
import { Field, ErrorMessage, Form } from "vee-validate";
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

const schema = yup.object({
    name: yup.string().required("Trường này là bắt buộc"),
    slug: yup.string().required("Trường này là bắt buộc"),
    variation: yup
        .array()
        .max(3, "Chỉ có thể tạo tối đa ${max} biến thể")
        .min(1,"Tối thiểu ${min} biến thể")
});
const modal = ref(null);
const btnSubmit = ref(null);
const modalTitle = ref("Thêm danh mục");
const modalMode = ref(1); // 1 is create,2 is update
const api = createAxios();
const slugText = ref("");
const listData = ref([]);
const parent = ref([]);
const currentCategory = ref({});
const tableHeader = [
    "#",
    "Tên danh mục",
    "Đường dẫn",
    "Danh mục cha",
    "Ngày tạo",
    "Thao tác",
];
const deleteDialogData = reactive({
    title: "",
    content: "",
});
const deleteDialog = ref();
const searchText = ref("");
const isLoading = ref(true);
const chip = ref();
const chipData = ref([]);
const paginator = reactive({
    page: 1,
    length: 10,
    range: 5, // so nut phan tran hien thi
    total: 0,
    //start = (page - 1) * length
});
const variationModal = ref();
const variation = reactive({
    title: "Chỉnh sửa thuộc tính",
    button: {
        class: "bg-red-500 ml-2",
        text: "Xoá",
    },
    schema: yup.object({
        variation_name: yup.string().required("Trường này là bắt buộc"),
    }),
    mode: 1,
    name: "",
    id: undefined,
});
const variationSubmitBtn = ref();

watch(
    searchText,
    (newValue, oldValue) => {
        debounce(getListCategory, 1000, api.category.PRODUCT);
    },
    { immediate: true }
);

const listCategory = computed(() => {
    return listData.value.map((item, index) => ({
        order: index + 1,
        name: item.name,
        slug: item.slug,
        parent: item.parent_category?.name ?? "Không có",
        created_at: moment(item.created_at).format("DD/MM/YYYY HH:mm:ss"),
    }));
});

function openModal(mode) {
    const current = currentCategory.value;
    if (mode == "create") {
        modalTitle.value = "Thêm danh mục";
        modalMode.value = 1;
    } else if ((mode = "edit")) {
        modalTitle.value = "Sửa danh mục";
        modalMode.value = 2;
    } else {
        throw new Error("action invalid");
    }
    parent.value = listData.value.filter((item) => current?.slug !== item.slug);
    console.log(currentCategory.value);
    modal.value.status = true;
}

function closeModal() {
    modal.value.status = false;
}

function handleSubmit(value) {
    value.type = 1;

    switch (modalMode.value) {
        case 1:
            api.category
                .create(value)
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
                });
            break;
        case 2:
            api.category
                .update(currentCategory.value.id, value)
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

function getListCategory(type) {
    if (!isLoading.value) {
        isLoading.value = true;
    }
    api.category
        .getList(type, searchText.value, paginator.page, paginator.length)
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

function getCategory(id) {
    return api.category.getItem(id);
}

function handleCreate() {
    currentCategory.value = null;
    slugText.value = "";
    openModal("create");
}

async function handleEdit(index) {
    try {
        const elem = listData.value[index];
        isLoading.value = true;
        const res = await getCategory(elem.id);
        isLoading.value = false;
        currentCategory.value = res.data;
        slugText.value = removeVI(currentCategory.value.slug);
        openModal("edit");
    } catch (error) {
        toast.error(error.message);
    }
}

function handleDelete(index) {
    const elem = listData.value[index];
    currentCategory.value = elem;
    deleteDialogData.title = "Xoá danh mục ";
    deleteDialogData.content = `Bạn có muốn xoá danh mục ${elem.name} không?`;
    deleteDialog.value.status = true;
}

function requestDelete() {
    api.category
        .delete(currentCategory.value.id)
        .then((res) => {
            if (res.data.message == "success") {
                toast.success("Xoá thành công");
                getListCategory(api.category.PRODUCT);
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

function handleAddValue() {
    chipData.value = chip.value.data.slice();
}

function createVariation(e) {
    variation.mode = 1;
    variationModal.value.status = true;
    variation.name = "";
}

function editVariation(e, item) {
    variation.mode = 2;
    variationModal.value.status = true;
    variation.name = item.name;
    variation.id = item.id;
}

function submitVariation(value) {
    const data = {
        name: value.variation_name,
        category_id: currentCategory.value.id,
    };
    switch (variation.mode) {
        case 1:
            api.variation
                .create(data)
                .then((res) => {
                    if (res.data.message == "success") {
                        toast.success("Thành công");
                        variationModal.value.status = false;
                    }
                    return getCategory(currentCategory.value.id);
                })
                .then((res) => {
                    currentCategory.value = res.data;
                })
                .catch((error) => {
                    toast.error(error.message);
                });
            break;
        case 2:
            api.variation
                .update(data, variation.id)
                .then((res) => {
                    if (res.data.message == "success") {
                        toast.success("Thành công");
                        variationModal.value.status = false;
                    }
                    return getCategory(currentCategory.value.id);
                })
                .then((res) => {
                    currentCategory.value = res.data;
                })
                .catch((error) => {
                    toast.error(error.message);
                });
            break;
        default:
            throw new Error("action invalid");
    }
}

function deleteVariation() {
    api.variation
        .delete(variation.id)
        .then((res) => {
            if (res.data.message == "success") {
                toast.success("Xoá thành công");
                variationModal.value.status = false;
            }
            return getCategory(currentCategory.value.id);
        })
        .then((res) => {
            currentCategory.value = res.data;
        })
        .catch((error) => {
            if (error.response.data.code == 23000) {
                toast.error(
                    "Không thể xoá thuộc tính do đã có sản phẩm liên kết"
                );
            } else {
                toast.error(error.message);
            }
        });
}
</script>

<style lang="scss" scoped></style>
