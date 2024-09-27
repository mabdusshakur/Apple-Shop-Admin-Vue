import axios from 'axios';

// Create an Axios instance
const axiosInstance = axios.create();

// Add a response interceptor
axiosInstance.interceptors.response.use(
    response => response,
    error => {
        console.log(error.response);
        if (error.response && error.response.status === 401) {
            window.location.href = '/login';
        }
        return new Promise(() => { });
    }
);

export default axiosInstance;