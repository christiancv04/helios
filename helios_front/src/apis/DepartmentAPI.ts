import { ApiResponse } from "../types/api/api"
import { Department, DepartmentDataForm } from "../types/slices/Department"
import { API } from "./configs/axiosConfigs"

export const DepartmentAPI = {
    get: async (params?: Record<string, unknown>): Promise<ApiResponse<Department[]>> => {
        const { data } = await API.get("/department", { params })
        return data
    },

    create: async (payload: DepartmentDataForm): Promise<ApiResponse<Department>> => {
        const { data } = await API.post("/department", payload)
        return data
    },

    update: async (id: number, payload: DepartmentDataForm): Promise<ApiResponse<Department>> => {
        const { data } = await API.put(`/department/${id}`, payload)
        return data
    },

    delete: async (id: number): Promise<ApiResponse<Department>> => {
        const { data } = await API.delete(`/department/${id}`)
        return data
    },
}
