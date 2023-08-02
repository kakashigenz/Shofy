import { createRouter, createWebHistory } from "vue-router";

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
        meta: { menu: "test" },
        component: () => import("@/pages/admin/MasterAdmin.vue"),
    },
    {
        path: "/:pathMatch(.*)",
        component: () => import("@/pages/admin/NotFound.vue"),
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from) => {
    // suy gnhi co nen de trang thai active o store khong
});

export { router };
