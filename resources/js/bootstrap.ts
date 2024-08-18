import axios, { AxiosInstance, AxiosRequestHeaders } from "axios";

declare global {
    interface Window {
        axios: AxiosInstance;
    }
}

window.axios = axios.create({
    headers: {
        "X-Requested-With": "XMLHttpRequest"
    } as unknown as AxiosRequestHeaders
});
