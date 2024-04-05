<template>
    <aside class="">
        <h2 class="font-bold mb-3">Quản lý</h2>
        <Menu :list="store.data" :children="false"></Menu>
    </aside>
</template>

<script setup>
import Menu from "@/components/admin/Menu.vue";
import { useMenuStore } from "@/store/useMenuStore";

const store = useMenuStore();

const list = [
    {
        name: "dashboard",
        title: "Tổng quan",
        icon: "bar-chart-alt-2",
        active: false,
        routeName: "dashboard",
    },
    {
        name: "product",
        title: "Sản phẩm",
        icon: "laptop",
        active: false,
        routeName: "product",
    },
    {
        name: "category",
        title: "Danh mục",
        icon: "category",
        active: false,
        routeName: "category",
        // children: [
        //     {
        //         name: "product-category",
        //         title: "Sản phẩm",
        //         active: false,
        //         icon: ["radio-circle", "radio-circle-marked"],
        //         routeName: "category",
        //         parent: "category",
        //     },
        //     {
        //         name: "blog-category",
        //         title: "Bài viết",
        //         active: false,
        //         icon: ["radio-circle", "radio-circle-marked"],
        //         parent: "category",
        //     },
        // ],
    },
    {
        name: "attribute",
        title: "Thuộc tính",
        icon: "sitemap",
        active: false,
        routeName: "attribute",
    },
    {
        name: "user",
        title: "Người dùng",
        icon: "user",
        active: false,
    },
];

store.setData(list);
</script>
