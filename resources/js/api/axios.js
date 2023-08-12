import axios from "axios";

const token = localStorage.getItem("token");

function createAxios() {
    const api = axios.create({
        baseURL: "/api/admin",
    });
    const PRODUCT = 1;
    const BLOG = 2;

    return {
        category: {
            PRODUCT,
            BLOG,
            create(data) {
                return api.post("category", data, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                    },
                });
            },
            getList(type, search, page = 1, length = 10) {
                return api.get("category", {
                    params: {
                        type,
                        search,
                        start: (page - 1) * length,
                        length,
                    },
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                    },
                });
            },
            getItem(id) {
                return api.get(`category/${id}`, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                    },
                });
            },
            update(id, data) {
                return api.put(`category/${id}`, data, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                    },
                });
            },
            delete(id) {
                return api.delete(`category/${id}`, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                    },
                });
            },
        },
        variation: {
            create(data) {
                return api.post("variation", data, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                    },
                });
            },
            update(data, id) {
                return api.put(`variation/${id}`, data, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                    },
                });
            },
            delete(id) {
                return api.delete(`variation/${id}`, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                    },
                });
            },
        },
        product: {
            create(data, headers) {
                return api.post("product", data, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
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
                return api.get("product", {
                    params,
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                    },
                });
            },
            getProduct(id) {
                return api.get(`product/${id}`, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                    },
                });
            },
            update(id, data, headers) {
                return api.post(`product/${id}`, data, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                        ...headers,
                    },
                });
            },
            delete(id) {
                return api.delete(`product/${id}`, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                    },
                });
            },
        },
        variationOption: {
            create(data) {
                return api.post("variation-option", data, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                    },
                });
            },
            delete(id) {
                return api.delete(`variation-option/${id}`, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
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
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                    },
                });
            },
            authorize() {
                return api.get("authorize", {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                    },
                });
            },
        },
    };
}
export default createAxios;
