import axios, { AxiosError, AxiosInstance, AxiosResponse } from "axios"

const baseURL = "http://localhost:8000/api"

export const API: AxiosInstance = axios.create({
  baseURL,
  timeout: 10000,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
})

API.interceptors.response.use(
  (response: AxiosResponse) => response,
  (error: AxiosError) => {
    return Promise.reject(error)
  }
)
