import "./bootstrap";
import { createApp } from "vue";
import App from "@/App.vue";
import { createRouter, createWebHistory } from "vue-router";
import { createPinia } from "pinia";
import "boxicons";
import { useUserStore } from "@/store/useUserStore";
import createAxios from "@/api/axios";

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
                meta: { menu: "dashboard" },
            },
            {
                name: "product",
                path: "san-pham",
                component: () => import("@/pages/admin/Product.vue"),
                meta: { menu: "product" },
            },
            {
                name: "category",
                path: "danh-muc-san-pham",
                component: () => import("@/pages/admin/ProductCategory.vue"),
                meta: { menu: "product-category" },
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
        const token = localStorage.getItem("token");
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
            return { name: "admin" };
        }
    } catch (error) {
        if ((error.response.status = 401)) {
            localStorage.removeItem("token");
            return { name: "admin" };
        }
        console.log(error);
    }
});

app.use(router);
app.mount("#app");
