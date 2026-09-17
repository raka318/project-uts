import axios from 'axios'

const baseUrl = (
  import.meta.env.VITE_API_BASE_URL ||
  'http://127.0.0.1:8000'
).replace(/\/$/, '')

const api = axios.create({
  baseURL: `${baseUrl}/api`,
  timeout: 15000,

  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    
    // Helps when using the free ngrok tunnel
    'ngrok-skip-browser-warning': 'true',
  },
})

api.interceptors.request.use(
  (config) => {
    const token =
      localStorage.getItem('token') ||
      localStorage.getItem('auth_token') ||
      localStorage.getItem('access_token')

    if (token) {
      config.headers = config.headers || {}

      config.headers.Authorization = `Bearer ${token}`
    }

    return config
  },

  (error) => {
    return Promise.reject(error)
  }
)

export default api