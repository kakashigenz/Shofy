import { getCookie } from "@/helper/cookie";
import axios from "axios";

function createAxios() {
    const api = axios.create({
        baseURL: "/api",
    });
    const PRODUCT = 1;
    const BLOG = 2;

    return {
        category: {
            PRODUCT,
            BLOG,
            create(data) {
                return api.post("admin/category", data, {
                    headers: {
                        Authorization: `Bearer ${getCookie("token")}`,
                    },
                });
            },
            getList(type, search, page = 1, length = 10) {
                return api.get("admin/category", {
                    params: {
                        type,
                        search,
                        start: (page - 1) * length,
                        length,
                    },
                    headers: {
                        Authorization: `Bearer ${getCookie("token")}`,
                    },
                });
            },
            getItem(id) {
                return api.get(`admin/category/${id}`, {
                    headers: {
                        Authorization: `Bearer ${getCookie("token")}`,
                    },
                });
            },
            update(id, data) {
                return api.put(`admin/category/${id}`, data, {
                    headers: {
                        Authorization: `Bearer ${getCookie("token")}`,
                    },
                });
            },
            delete(id) {
                return api.delete(`admin/category/${id}`, {
                    headers: {
                        Authorization: `Bearer ${getCookie("token")}`,
                    },
                });
            },
        },
        attribute: {
            getList(search, page = 1, length = 10) {
                const params = {
                    search,
                    page,
                    length,
                };
                return api.get("admin/attribute", {
                    params,
                    headers: {
                        Authorization: `Bearer ${getCookie("token")}`,
                    },
                });
            },
            getItem(id) {
                return api.get(`admin/attribute/${id}`, {
                    headers: {
                        Authorization: `Bearer ${getCookie("token")}`,
                    },
                });
            },
            getByCategory(id) {
                return api.get(`admin/attribute-category/${id}`, {
                    headers: {
                        Authorization: `Bearer ${getCookie("token")}`,
                    },
                });
            },
            create(data) {
                return api.post("admin/attribute", data, {
                    headers: {
                        Authorization: `Bearer ${getCookie("token")}`,
                    },
                });
            },
            update(data, id) {
                return api.put(`admin/attribute/${id}`, data, {
                    headers: {
                        Authorization: `Bearer ${getCookie("token")}`,
                    },
                });
            },
            delete(id) {
                return api.delete(`admin/attribute/${id}`, {
                    headers: {
                        Authorization: `Bearer ${getCookie("token")}`,
                    },
                });
            },
        },
        attributeValue: {
            create(data) {
                return api.post("admin/attribute-value", data, {
                    headers: {
                        Authorization: `Bearer ${getCookie("token")}`,
                    },
                });
            },
            update(id, data) {
                return api.put(`admin/attribute-value/${id}`, data, {
                    headers: {
                        Authorization: `Bearer ${getCookie("token")}`,
                    },
                });
            },
            delete(id) {
                return api.delete(`admin/attribute-value/${id}`, {
                    headers: {
                        Authorization: `Bearer ${getCookie("token")}`,
                    },
                });
            },
        },
        product: {
            create(data, headers) {
                return api.post("admin/product", data, {
                    headers: {
                        Authorization: `Bearer ${getCookie("token")}`,
                        ...headers,
                    },
                });
            },
            getList(search, page = 1, length = 10) {
                const params = {
                    search,
                    start: (page - 1) * length,
                    length,
                };
                return api.get("admin/product", {
                    params,
                    headers: {
                        Authorization: `Bearer ${getCookie("token")}`,
                    },
                });
            },
            getProduct(id) {
                return api.get(`admin/product/${id}`, {
                    headers: {
                        Authorization: `Bearer ${getCookie("token")}`,
                    },
                });
            },
            update(id, data, headers) {
                return api.post(`admin/product/${id}`, data, {
                    headers: {
                        Authorization: `Bearer ${getCookie("token")}`,
                        ...headers,
                    },
                });
            },
            delete(id) {
                return api.delete(`admin/product/${id}`, {
                    headers: {
                        Authorization: `Bearer ${getCookie("token")}`,
                    },
                });
            },
            changeStatus(id, status) {
                return api.put(
                    `admin/product/${id}`,
                    { status },
                    {
                        headers: {
                            Authorization: `Bearer ${getCookie("token")}`,
                        },
                    }
                );
            },
        },
        variation: {
            create(data) {
                return api.post("admin/variation", data, {
                    headers: {
                        Authorization: `Bearer ${getCookie("token")}`,
                    },
                });
            },
            delete(id) {
                return api.delete(`admin/variation/${id}`, {
                    headers: {
                        Authorization: `Bearer ${getCookie("token")}`,
                    },
                });
            },
        },
        variationOption: {
            create(data) {
                return api.post("admin/variation-option", data, {
                    headers: {
                        Authorization: `Bearer ${getCookie("token")}`,
                    },
                });
            },
            delete(id) {
                return api.delete(`admin/variation-option/${id}`, {
                    headers: {
                        Authorization: `Bearer ${getCookie("token")}`,
                    },
                });
            },
        },
        productItem: {
            delete(id) {
                return api.delete(`admin/product-item/${id}`, {
                    headers: {
                        Authorization: `Bearer ${getCookie("token")}`,
                    },
                });
            },
        },
        auth: {
            login(data) {
                return api.post("login", data);
            },
            logout() {
                return api.get("logout", {
                    headers: {
                        Authorization: `Bearer ${getCookie("token")}`,
                    },
                });
            },
            authorize() {
                return api.get("authorize", {
                    headers: {
                        Authorization: `Bearer ${getCookie("token")}`,
                    },
                });
            },
        },
    };
}
export default createAxios;
