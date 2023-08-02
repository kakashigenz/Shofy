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
                return api.post("category", data);
            },
            getList(type, search, page = 1, length = 10) {
                return api.get("category", {
                    params: {
                        type,
                        search,
                        start: (page - 1) * length,
                        length,
                    },
                });
            },
            getItem(id) {
                return api.get(`category/${id}`);
            },
            update(id, data) {
                return api.put(`category/${id}`, data);
            },
            delete(id) {
                return api.delete(`category/${id}`);
            },
        },
        variation: {
            create(data) {
                return api.post("variation", data);
            },
            update(data, id) {
                return api.put(`variation/${id}`, data);
            },
            delete(id) {
                return api.delete(`variation/${id}`);
            },
        },
        product: {
            create(data, headers) {
                return api.post("product", data, {
                    headers,
                });
            },
            getList(search, page = 1, length = 10) {
                const params = {
                    search,
                    start: (page - 1) * length,
                    length,
                };
                return api.get("product", { params });
            },
            getProduct(id) {
                return api.get(`product/${id}`);
            },
            update(id, data, headers) {
                return api.post(`product/${id}`, data, {
                    headers,
                });
            },
            delete(id){
                return api.delete(`product/${id}`)
            }
        },
        variationOption: {
            create(data) {
                return api.post("variation-option", data);
            },
            delete(id) {
                return api.delete(`variation-option/${id}`);
            },
        },
    };
}
export default createAxios;
