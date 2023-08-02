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
import Dropdown from "./Dropdown.vue";

const dataDropdown = [
    {
        title: "This is 1",
    },
    {
        title: "This is 2",
    },
    {
        title: "This is 3",
    },
];
</script>
