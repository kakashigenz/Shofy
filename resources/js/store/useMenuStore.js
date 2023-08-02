import { defineStore } from "pinia";
export const useMenuStore = defineStore("menu", {
    state: () => ({
        prevElem: null,
        data: [],
    }),
    actions: {
        setPrevElem(elem) {
            this.prevElem = elem;
        },
        setData(data) {
            this.data = data;
        },
    },
});
