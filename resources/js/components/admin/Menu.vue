<template>
    <ul :class="isChildren ? 'list-children pl-3' : ''">
        <li
            class="nav-item"
            v-for="(item, index) in props.list"
            :key="index"
            :class="item.active ? 'active' : ''"
        >
            <RouterLink
                :to="item.routeName ? { name: item.routeName } : '#'"
                class="item-header select-none h-[48px] pl-3 py-2 rounded-sm cursor-pointer hover:bg-gray-100 flex items-center justify-between transition-all"
                @click="handleToggle($event, item)"
                :class="!item.children && item.active ? 'bg-gray-200' : ''"
            >
                <div class="flex items-center">
                    <box-icon
                        v-if="!isChildren || !Array.isArray(item.icon)"
                        :name="item.icon"
                        class="mr-2"
                    ></box-icon>
                    <box-icon
                        v-else-if="Array.isArray(item.icon) && item.active"
                        :name="item.icon[1]"
                        class="mr-2"
                    ></box-icon>
                    <box-icon
                        v-else
                        :name="item.icon[0]"
                        class="mr-2"
                    ></box-icon>
                    <span class="text-sm">{{ item.title }}</span>
                </div>
                <box-icon
                    v-if="item.children"
                    class="item-arrow transition-all"
                    name="chevron-right"
                ></box-icon>
            </RouterLink>

            <Menu
                v-if="item.children"
                :children="item.children ? true : false"
                :list="item.children"
            ></Menu>
        </li>
    </ul>
</template>

<script setup>
import { useMenuStore } from "@/store/useMenuStore";
import { onBeforeMount } from "vue";
import { useRoute } from "vue-router";

const store = useMenuStore();
const route = useRoute();

const props = defineProps({
    list: {
        type: Array,
        required: true,
    },
    children: {
        type: Boolean,
        required: true,
    },
});

const isChildren = props.children;

function handleToggle(e, item) {
    //check has children
    if (item.children) {
        item.active = !item.active;
    } else {
        item.active = true;
        if (store.prevElem && store.prevElem.name !== item.name) {
            if (store.prevElem.parent === item.parent) {
                store.prevElem.active = false;
            } else {
                setItem(store.data, store.prevElem.name, false);
            }
        }
        store.setPrevElem(item);
    }
}

function setItem(menu, name, value) {
    if (menu) {
        for (const item of menu) {
            if (item.name === name) {
                item.active = value;
                if (value) {
                    store.setPrevElem(item);
                }
                return true;
            }
            if (setItem(item.children, name, value)) {
                item.active = value;
                return true;
            }
        }
    }
    return false;
}

function activeItem(name) {
    setItem(props.list, name, true);
}

onBeforeMount(() => {
    activeItem(route.meta.menu);
});
</script>

<style lang="scss" scoped>
.list-children {
    > .nav-item {
        > .item-header {
            margin-bottom: -48px;
            opacity: 0;
            visibility: hidden;
        }
    }
}

.nav-item.active {
    > .item-header {
        .item-arrow {
            transform: rotate(90deg);
        }
    }

    > .item-header + .list-children {
        > .nav-item {
            > .item-header {
                margin-bottom: 0;
                opacity: 1;
                visibility: visible;
            }
        }
    }
}
</style>
