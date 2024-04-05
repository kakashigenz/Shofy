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
                :data="listAttribute"
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
                    <label for="name" class="mb-1"
                        >Tên thuộc tính
                        <span class="text-red-500">*</span></label
                    >
                    <Field
                        name="name"
                        id="name"
                        class="outline-none border border-gray-300 rounded-md px-3 py-2 text-sm"
                        :model-value="currentAttribute?.name"
                    >
                    </Field>
                    <ErrorMessage name="name" class="text-red-500" />
                </div>
                <div class="flex flex-col mb-2">
                    <label for="category_id" class="mb-1"
                        >Danh mục <span class="text-red-500">*</span></label
                    >
                    <Field
                        as="select"
                        name="category_id"
                        id="category_id"
                        class="outline-none border border-gray-300 rounded-md px-3 py-2 text-sm"
                        :model-value="currentAttribute?.category_id"
                    >
                        <option value="" selected disabled>Danh mục</option>
                        <option
                            v-for="(item, index) in categories"
                            :value="item.id"
                            :key="index"
                        >
                            {{ item.name }}
                        </option>
                    </Field>
                    <ErrorMessage name="category_id" class="text-red-500" />
                </div>
                <div v-if="modalMode == 1" class="flex flex-col mb-2">
                    <label for="values" class="mb-1"
                        >Giá trị <span class="text-red-500">*</span></label
                    >
                    <Field
                        type="hidden"
                        name="values"
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
                                v-for="item in currentAttribute.values"
                                :key="item.id"
                                class="flex items-center p-2"
                            >
                                <p
                                    class="min-w-[80px] border-b border-gray-300"
                                >
                                    {{ item.value }}
                                </p>
                                <Button
                                    class="ml-2 bg-gray-200"
                                    :handle-click="
                                        (e) => {
                                            editAttributeValue(e, item);
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
                                    @click="createAttributeValue"
                                >
                                    Thêm giá trị
                                </button>
                            </li>
                        </ul>
                    </Panel>
                </div>
                <button type="submit" :v-show="false" ref="btnSubmit"></button>
            </Form>
            <Modal
                ref="attributeValueModal"
                :title="attributeValue.title"
                :custom-button="
                    attributeValue.mode == 2 ? attributeValue.button : null
                "
                @accept-click="
                    () => {
                        attributeValueSubmitBtn.click();
                    }
                "
                @custom-click="deleteAttributeValue"
            >
                <Form
                    :validation-schema="attributeValue.schema"
                    @submit="submitAttributeValue"
                >
                    <div class="flex flex-col">
                        <label for="attribute_value">Giá trị</label>
                        <Field
                            name="attribute_value"
                            id="attribute_value"
                            class="outline-none border border-gray-300 rounded-md px-3 py-2 text-sm"
                            :model-value="attributeValue.name"
                        />
                        <ErrorMessage
                            name="attribute_value"
                            class="text-red-500"
                        />
                    </div>
                    <button
                        class="hidden"
                        ref="attributeValueSubmitBtn"
                    ></button>
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
import { computed, onMounted, reactive, ref, watch } from "vue";
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
    category_id: yup
        .number("Danh mục không hợp lệ")
        .required("Trường này là bắt buộc"),
    // slug: yup.string().required("Trường này là bắt buộc"),
    // variation: yup
    //     .array()
    //     .max(3, "Chỉ có thể tạo tối đa ${max} biến thể")
    //     .min(1, "Tối thiểu ${min} biến thể"),
});
const modal = ref(null);
const btnSubmit = ref(null);
const modalTitle = ref("Thêm thuộc tính");
const modalMode = ref(1); // 1 is create,2 is update
const api = createAxios();
const slugText = ref("");
const listData = ref([]);
const categories = ref([]);
const currentAttribute = ref({});
const tableHeader = ["#", "Tên thuộc tính", "Danh mục", "Ngày tạo", "Thao tác"];
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
const attributeValueModal = ref();
const attributeValue = reactive({
    title: "Chỉnh sửa thuộc tính",
    button: {
        class: "bg-red-500 ml-2",
        text: "Xoá",
    },
    schema: yup.object({
        attribute_value: yup.string().required("Trường này là bắt buộc"),
    }),
    mode: 1,
    name: "",
    id: undefined,
});
const attributeValueSubmitBtn = ref();
let timeoutId;

onMounted(() => {
    getCategories();
});

watch(
    searchText,
    (newValue, oldValue) => {
        clearTimeout(timeoutId);
        timeoutId = debounce(getListAttribute, 1000);
    },
    { immediate: true }
);
//list attribute
const listAttribute = computed(() => {
    return listData.value.map((item, index) => ({
        order: index + 1,
        name: item.name,
        category: item.category?.name,
        created_at: moment(item.created_at).format("DD/MM/YYYY HH:mm:ss"),
    }));
});

function openModal(mode) {
    // const current = currentCategory.value;
    if (mode == "create") {
        modalTitle.value = "Thêm thuộc tính";
        modalMode.value = 1;
    } else if ((mode = "edit")) {
        modalTitle.value = "Sửa thuộc tính";
        modalMode.value = 2;
    } else {
        throw new Error("action invalid");
    }
    // parent.value = listData.value.filter((item) => current?.slug !== item.slug);
    // console.log(currentAttribute.value);
    modal.value.status = true;
}

function closeModal() {
    modal.value.status = false;
}
//hàm xử lý gửi thông tin thuộc tính
function handleSubmit(value) {
    switch (modalMode.value) {
        case 1:
            api.attribute
                .create(value)
                .then((res) => {
                    if (res.data.message == "success") {
                        toast.success("Thành công", {
                            autoClose: 3000,
                        });
                        closeModal();
                        getListAttribute();
                    }
                })
                .catch((error) => {
                    toast.error(error.message);
                });
            break;
        case 2:
            api.attribute
                .update(value, currentAttribute.value.id)
                .then((res) => {
                    if (res.data.message == "success") {
                        toast.success("Thành công", {
                            autoClose: 3000,
                        });
                        closeModal();
                        getListAttribute();
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

async function getCategories() {
    const res = await api.category.getList(1, "");
    categories.value = res.data.data;
}
//get list attribute
function getListAttribute() {
    if (!isLoading.value) {
        isLoading.value = true;
    }
    api.attribute
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

function getAttribute(id) {
    return api.attribute.getItem(id);
}

function handleCreate() {
    currentAttribute.value = null;
    slugText.value = "";
    openModal("create");
}

async function handleEdit(index) {
    try {
        const elem = listData.value[index];
        isLoading.value = true;
        const res = await getAttribute(elem.id);
        isLoading.value = false;
        currentAttribute.value = res.data;
        // slugText.value = removeVI(currentCategory.value.slug);
        openModal("edit");
    } catch (error) {
        toast.error(error.message);
    }
}

function handleDelete(index) {
    const elem = listData.value[index];
    currentAttribute.value = elem;
    deleteDialogData.title = "Xoá thuộc tính ";
    deleteDialogData.content = `Bạn có muốn xoá thuộc tính ${elem.name} không?`;
    deleteDialog.value.status = true;
}

function requestDelete() {
    api.attribute
        .delete(currentAttribute.value.id)
        .then((res) => {
            if (res.data.message == "success") {
                toast.success("Xoá thành công");
                getListAttribute(api.category.PRODUCT);
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
    getListAttribute(api.category.PRODUCT);
}

function handleAddValue() {
    chipData.value = chip.value.data.slice();
}

function createAttributeValue(e) {
    attributeValue.mode = 1;
    attributeValueModal.value.status = true;
    attributeValue.name = "";
    attributeValue.title = "Thêm giá trị";
}
// logic xử lý chỉnh sửa thuộc tính
function editAttributeValue(e, item) {
    attributeValue.mode = 2;
    attributeValueModal.value.status = true;
    attributeValue.name = item.value;
    attributeValue.id = item.id;
    attributeValue.title = "Chỉnh sửa giá trị";
}

function submitAttributeValue(value) {
    const data = {
        value: value.attribute_value,
        attribute_id: currentAttribute.value.id,
    };
    switch (attributeValue.mode) {
        case 1:
            api.attributeValue
                .create(data)
                .then((res) => {
                    if (res.data.message == "success") {
                        toast.success("Thành công");
                        attributeValueModal.value.status = false;
                    }
                    return getAttribute(currentAttribute.value.id);
                })
                .then((res) => {
                    currentAttribute.value = res.data;
                })
                .catch((error) => {
                    toast.error(error.message);
                });
            break;
        case 2:
            api.attributeValue
                .update(data, attributeValue.id)
                .then((res) => {
                    if (res.data.message == "success") {
                        toast.success("Thành công");
                        variationModal.value.status = false;
                    }
                    return getAttribute(currentCategory.value.id);
                })
                .then((res) => {
                    currentAttribute.value = res.data;
                })
                .catch((error) => {
                    toast.error(error.message);
                });
            break;
        default:
            throw new Error("action invalid");
    }
}

function deleteAttributeValue() {
    const id = attributeValue.id;
    api.attributeValue
        .delete(id)
        .then((res) => {
            if (res.data.message == "success") {
                toast.success("Xoá thành công");
                attributeValueModal.value.status = false;
            }
            return getAttribute(currentAttribute.value.id);
        })
        .then((res) => {
            currentAttribute.value = res.data;
        })
        .catch((error) => {
            if (error.response.data.code == 23000) {
                toast.error("Không thể xoá giá trị do đã có sản phẩm liên kết");
            } else {
                toast.error(error.message);
            }
        });
}
</script>

<style lang="scss" scoped></style>
