<template>
    <div class="flex justify-center items-center h-screen">
        <form
            class="bg-white p-5 rounded-lg shadow-md w-[420px]"
            @submit="onSubmit"
        >
            <header class="py-3 flex justify-center items-center">
                <img src="/assets/images/logo.svg" alt="Logo" />
            </header>
            <div>
                <div class="flex flex-col gap-1 mb-3">
                    <label for="email">Tên đăng nhập</label>
                    <input
                        type="email"
                        id="email"
                        class="border border-gray-400 outline-none px-2 py-1 rounded-md"
                        v-model="emailValue"
                    />
                    <p v-if="errors.email" class="text-red-500">
                        {{ errors.email }}
                    </p>
                </div>
                <div class="flex flex-col justify-center gap-1 mb-3">
                    <label for="password">Mật khẩu</label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            class="border border-gray-400 outline-none px-2 py-1 rounded-md w-full"
                            ref="passwordInput"
                            v-model="passwordValue"
                        />
                        <box-icon
                            name="hide"
                            class="absolute right-1 top-1/2 -translate-y-1/2 cursor-pointer select-none"
                            :type="isHidePassword ? 'regular' : 'solid'"
                            @click="togglePassword"
                        ></box-icon>
                    </div>
                    <p v-if="errors.password" class="text-red-500">
                        {{ errors.password }}
                    </p>
                </div>
            </div>
            <footer>
                <button
                    type="submit"
                    class="w-full p-2 bg-blue-500 rounded-lg text-white"
                >
                    Đăng nhập
                </button>
            </footer>
        </form>
    </div>
</template>

<script setup>
import { useField, useForm } from "vee-validate";
import { ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import * as yup from "yup";
import createAxios from "@/api/axios";
import { useUserStore } from "@/store/useUserStore";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

const api = createAxios();
const store = useUserStore();
const route = useRoute();
const router = useRouter();
document.title = route.meta.title;

const isHidePassword = ref(true);
const passwordInput = ref();
function togglePassword(e) {
    passwordInput.value.type = isHidePassword.value ? "text" : "password";
    isHidePassword.value = !isHidePassword.value;
}
const { handleSubmit, errors } = useForm({
    validationSchema: yup.object({
        email: yup
            .string()
            .email("Email không hợp lệ")
            .required("Vui lòng không để trống"),
        password: yup.string().required("Vui lòng không để trống"),
    }),
});
const onSubmit = handleSubmit((value) => {
    api.auth
        .login(value)
        .then((res) => {
            if (res.data.message == "success" && res.data.token) {
                localStorage.setItem("token", res.data.token);
                store.setUser(res.data.user);
                router.push({ name: "dashboard" });
            }
        })
        .catch((err) => {
            if (err.response?.data) {
                toast.error(err.response.data.message);
            } else {
                console.error(err);
            }
        });
});
const { value: emailValue } = useField("email");
const { value: passwordValue } = useField("password");
</script>

<style lang="scss" scoped></style>
