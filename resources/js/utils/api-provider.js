import axios from "axios";
import Loading from "@/utils/loading";
import Toast from "@/utils/custom-toast";

const metaToken = document.querySelector('meta[name="csrf-token"]');
const csrfToken = metaToken ? metaToken.getAttribute("content") : "";

const http = axios.create({
    headers: {
        "X-CSRF-TOKEN": csrfToken,
        Accept: "application/json",
    },
});

http.interceptors.request.use(
    (config) => {
        Loading.start();
        return config;
    },
    (error) => {
        Loading.stop();
        return Promise.reject(error);
    },
);

http.interceptors.response.use(
    (response) => {
        Loading.stop();
        return response;
    },
    (error) => {
        Loading.stop();
        ApiProvider.handleAxiosError(error);
        return Promise.reject(error);
    },
);

const ApiProvider = {
    async get(url, params = null) {
        try {
            const response = await http.get(url, { params });
            return response.data;
        } catch (error) {
            throw error;
        }
    },
    async post(url, data = null) {
        try {
            const response = await http.post(url, data);
            return response.data;
        } catch (error) {
            throw error;
        }
    },
    async put(url, data = null) {
        try {
            const response = await http.put(url, data);
            return response.data;
        } catch (error) {
            throw error;
        }
    },
    async delete(url) {
        try {
            const response = await http.delete(url);
            return response.data;
        } catch (error) {
            throw error;
        }
    },
    handleAxiosError(error) {
        const response = error.response;

        console.error("api_provider_error", error);

        if (!response) {
            Toast.error("Network Error or Server Down");
            return;
        }

        switch (response.status) {
            case 422: Toast.error(response.data.message || "Bad Request"); break;
            case 400: Toast.error(response.data.message || "Bad Request"); break;
            case 404: Toast.error("Data not found"); break;
            case 419: Toast.error("Page Expired. Refresh the page").then(() => window.location.reload()); break;
            case 403: Toast.error("Access Denied"); break;
            case 401: Toast.error("Invalid Session").then(() => window.location.reload()); break;
            default: Toast.error(response.status >= 500 ? "Internal Server Error" : "Unknown Error"); break;
        }
    },
};

export default ApiProvider;
