<template>
    <header
        class="sticky top-0 w-full bg-white dark:bg-slate-800 p-4 flex items-center"
    >
        <router-link
            :to="{ name: 'admin' }"
            class="flex items-center justify-between"
        >
            <img
                src="/assets/images/logo.svg"
                alt="This is a logo"
                class="w-25"
            />
        </router-link>
        <div class="flex items-center ml-auto">
            <box-icon name="moon" class="mr-5 cursor-pointer"></box-icon>
            <Dropdown :data="dataDropdown">
                <img
                    src="/assets/images/tanjiro.jpg"
                    alt="This is a avatar"
                    class="w-[40px] h-[40px] rounded-full"
                />
                <span>Nguyen Quang</span>
                <box-icon type="solid" name="chevron-down"></box-icon>
            </Dropdown>
        </div>
    </header>
</template>

<script setup>
import createAxios from "@/api/axios";
import Dropdown from "./Dropdown.vue";
import { useRouter } from "vue-router";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import { useUserStore } from "@/store/useUserStore";

const api = createAxios();
const router = useRouter();
const userStore = useUserStore();

const dataDropdown = [
    {
        title: "Đổi mật khẩu",
    },
    {
        title: "Đăng xuất",
        handleClick: logout,
    },
];

async function logout() {
    try {
        const response = await api.auth.logout();
        if (response.status == 200) {
            localStorage.removeItem("token");
            userStore.setUser(null);
            router.push({ name: "admin" });
        }
    } catch (error) {
        toast.error("Có lỗi xảy ra vui lòng thử lại sau");
        console.log(error);
    }
}
</script>
