import "./bootstrap";
import { createApp } from "vue";
import App from "@/App.vue";
import { createRouter, createWebHistory } from "vue-router";
import { createPinia } from "pinia";
import "boxicons";
import { useUserStore } from "@/store/useUserStore";
import createAxios from "@/api/axios";
import { getCookie } from "./helper/cookie";

const app = createApp(App);
const pinia = createPinia();
const api = createAxios();

app.use(pinia);

//route config
const userStore = useUserStore();

const routes = [
    {
        name: "admin",
        path: "/admin",
        children: [
            {
                name: "dashboard",
                path: "dashboard",
                component: () => import("@/pages/admin/Dashboard.vue"),
                meta: { menu: "dashboard", title: "Trang chủ" },
            },
            {
                name: "product",
                path: "san-pham",
                component: () => import("@/pages/admin/Product.vue"),
                meta: { menu: "product", title: "Quản lý sản phẩm" },
            },
            {
                name: "category",
                path: "danh-muc-san-pham",
                component: () => import("@/pages/admin/ProductCategory.vue"),
                meta: { menu: "product-category", title: "Quản lý danh mục" },
            },
            {
                name: "attribute",
                path: "thuoc-tinh",
                component: () => import("@/pages/admin/Attribute.vue"),
                meta: { menu: "attribute", title: "Quản lý thuộc tính" },
            },
        ],
        meta: { title: "Đăng nhập" },
        components: {
            default: () => import("@/pages/admin/MasterAdmin.vue"),
            login: () => import("@/pages/admin/Login.vue"),
        },
    },
    {
        name: "notfound",
        path: "/:pathMatch(.*)",
        component: () => import("@/pages/admin/NotFound.vue"),
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from) => {
    try {
        const token = getCookie("token");
        if (token) {
            const response = await api.auth.authorize();
            if (response.status == 200 && response.data) {
                userStore.setUser(response.data);
                if (to.name == "admin") {
                    return { name: "dashboard" };
                }
            }
        }
        // no token
        if (!token && to.name != "admin") {
            userStore.setUser(null);
            return { name: "admin" };
        }
    } catch (error) {
        if ((error.response.status = 401)) {
            userStore.setUser(null);
            return { name: "admin" };
        }
        console.log(error);
    }
});

router.afterEach((to, from) => {
    document.title = to.meta.title;
});

app.use(router);
app.mount("#app");
