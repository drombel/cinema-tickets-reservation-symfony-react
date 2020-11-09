import axios from 'axios';

const instance = axios.create({
    baseURL: `${window.location.origin}/api`,
    // timeout: 1000,
});
export default instance;