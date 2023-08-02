<template>
    <div class="w-full max-w-[1920px] flex flex-col mx-auto min-h-screen">
        <Header></Header>
        <div class="flex flex-1">
            <Sidebar class="bg-white w-[248px] p-5 border"></Sidebar>
            <main class="flex-1 p-5">
                <router-view></router-view>
            </main>
        </div>
    </div>
</template>

<script setup>
import Header from "@/components/admin/Header.vue";
import Sidebar from "@/components/admin/Sidebar.vue";
</script>
<style lang="scss" scoped></style>
