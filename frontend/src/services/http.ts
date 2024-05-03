import axios, { type AxiosInstance } from 'axios'

const { value: authToken } = JSON.parse(
  localStorage.getItem('token') || '{}'
)
const http = axios.create({
  headers: {
    'Content-Type': 'application/json'
  }
})

const mode = import.meta.env.MODE

if (mode === 'development') {
  http.defaults.baseURL = import.meta.env.VITE_API_URL
}


http.defaults.headers.common['Content-Type'] = 'application/json'
http.defaults.headers.common['Accept'] = 'application/json'

if (authToken) {
  http.defaults.headers.common['Authorization'] = `Bearer ${authToken}`
}


export default http