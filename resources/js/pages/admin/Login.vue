<template>
    <div class="flex justify-center items-center h-screen">
        <div class="bg-white p-5 rounded-lg shadow-md w-[420px]">
            <header class="py-3 flex justify-center items-center">
                <img src="/assets/images/logo.svg" alt="Logo" />
            </header>
            <div>
                <div class="flex flex-col gap-1 mb-3">
                    <label for="username">Tên đăng nhập</label>
                    <input
                        type="text"
                        id="username"
                        class="border border-gray-400 outline-none px-2 py-1 rounded-md"
                    />
                </div>
                <div class="flex flex-col justify-center gap-1 mb-3">
                    <label for="password">Mật khẩu</label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            class="border border-gray-400 outline-none px-2 py-1 rounded-md w-full"
                            ref="passwordInput"
                        />
                        <box-icon
                            name="hide"
                            class="absolute right-1 top-1/2 -translate-y-1/2 cursor-pointer select-none"
                            :type="isHidePassword ? 'regular' : 'solid'"
                            @click="togglePassword"
                        ></box-icon>
                    </div>
                </div>
            </div>
            <footer>
                <button class="w-full p-2 bg-blue-500 rounded-lg text-white">
                    Đăng nhập
                </button>
            </footer>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();
document.title = route.meta.title;

const isHidePassword = ref(true);
const passwordInput = ref();
function togglePassword(e) {
    passwordInput.value.type = isHidePassword.value ? "text" : "password";
    isHidePassword.value = !isHidePassword.value;
}
</script>

<style lang="scss" scoped></style>
