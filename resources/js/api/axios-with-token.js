import axios from 'axios'
import router from '../router'
import { errorMsgToast } from '../utils/toasts'
import { getHostUrl } from '../utils/host-url'
import { store } from '../store'

// const url = `${getHostUrl()}/api`
const url = `http://127.0.0.1:8000/api`
const axiosApi = axios.create({
    baseURL: url,
})

axiosApi.interceptors.request.use(config => {
    config.headers = {
        Authorization: `Bearer ${localStorage.getItem('infyChatAppToken')}`,
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    }
    return config
})

axiosApi.interceptors.response.use((res) => {
    return res.data
}, (error) => {
    if (error.response.data.message) {
        errorMsgToast(error.response.data.message)
    }
    if (+error.response.status === 401) {
        localStorage.removeItem('infyChatAppToken')
        store.state.infyChatAppToken = ''
        router.push('/login')
    }
    return Promise.reject(error)
})

export default axiosApi
