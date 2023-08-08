import "./bootstrap";
import { createApp } from "vue";
import App from "@/App.vue";
import { createRouter, createWebHistory } from "vue-router";
import { createPinia } from "pinia";
import "boxicons";
import { useUserStore } from "./store/useUserStore";

const app = createApp(App);
const pinia = createPinia();

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
        component: () =>
            userStore.user?.isAdmin
                ? import("@/pages/admin/MasterAdmin.vue")
                : import("@/pages/admin/Login.vue"),
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

router.beforeEach((to, from) => {
    if (!userStore.user?.isAdmin && to.name != "admin") {
        return { name: "admin" };
    }
});

app.use(router);
app.mount("#app");
