import axios, { type AxiosInstance } from 'axios'

const {value: authToken } = JSON.parse(
  localStorage.getItem('token') || '{}'
)


const http = axios.create({
  baseURL: 'http://127.0.0.1:8000/api/v1/',
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