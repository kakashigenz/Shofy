<template>
    <router-view v-if="userStore.user?.isAdmin"></router-view>
    <router-view name="login" v-else></router-view>
</template>

<script setup>
import { useUserStore } from "./store/useUserStore";

const userStore = useUserStore();
</script>
