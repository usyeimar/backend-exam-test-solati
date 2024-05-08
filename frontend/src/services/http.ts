import axios, { type AxiosInstance } from 'axios'

const { value: authToken } = JSON.parse(
  localStorage.getItem('token') || '{}'
)


const http = axios.create({ headers: { 'Content-Type': 'application/json' } })

http.interceptors.request.use((config) => {
  if (authToken) {
    config.headers.Authorization = `Bearer ${authToken}`
  }
  return config
})


const mode = import.meta.env.MODE

if (mode === 'development') {
  http.defaults.baseURL = import.meta.env.VITE_API_URL
}



export default http