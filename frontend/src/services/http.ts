import axios, { type AxiosInstance } from 'axios'

const {value: authToken } = JSON.parse(
  localStorage.getItem('token') || '{}'
)


const http = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
  headers: {
    'Content-Type': 'application/json'
  }
})


http.defaults.headers.common['Content-Type'] = 'application/json'
http.defaults.headers.common['Accept'] = 'application/json'

if (authToken) {
  http.defaults.headers.common['Authorization'] = `Bearer ${authToken}`
}


export default http