import axios from 'axios'
import { errorMsgToast } from '../utils/toasts'
import { getHostUrl } from '../utils/host-url'

// const url = `${getHostUrl()}/api`
const url = `http://127.0.0.1:8000/api`
const axiosApi = axios.create({
    baseURL: url,
})

axiosApi.interceptors.response.use((res) => {
    return res.data
}, (error) => {
    if (error.response.data.message) {
        errorMsgToast(error.response.data.message)
    }
    return Promise.reject(error)
})

export default axiosApi
