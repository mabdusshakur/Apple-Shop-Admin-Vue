import axiosInstance from "./axios";
window.axios = axiosInstance;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
