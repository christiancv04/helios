import { QueryObserverResult, RefetchOptions, useMutation, useQuery, useQueryClient } from "@tanstack/react-query"
import { useMemo } from "react"
import { useDispatch, useSelector } from "react-redux"
import { Department, DepartmentDataForm } from "../types/slices/Department"
import { RootState } from "../store/store"
import { ApiResponse } from "../types/api/api"
import { DepartmentAPI } from "../apis/DepartmentAPI"
import { CustomSnackbar } from "../components/common/CustomSnackbar/CustomSnackbar"
import { handleMutationError, handleMutationSuccess } from "../helpers/mutation-response-helper"
import { onSelectedDepartment } from "../store/departmentSlice"

export interface UseDepartmentReturn {
    departments: Department[]
    totalRows: number
    selectedDepartment?: Department
    isLoading: boolean
    setSelectedDepartment: (data: Department | undefined) => void
    createDepartment: (payload: DepartmentDataForm) => Promise<ApiResponse<Department>>
    updateDepartment: (id: number, payload: DepartmentDataForm) => Promise<ApiResponse<Department>>
    deleteDepartment: (id: number) => Promise<ApiResponse<Department>>
    refetchDepartments: (options?: RefetchOptions) => Promise<QueryObserverResult<ApiResponse<Department[]>, Error>>
}

export const useDepartment = (
    filters?: Record<string, unknown>,
    autoFetch = true
): UseDepartmentReturn => {
    const dispatch = useDispatch()
    const queryClient = useQueryClient()
    const { selectedDepartment } = useSelector((state: RootState) => state.department)

    const {
        data,
        isFetching,
        refetch: refetchDepartments,
    } = useQuery<ApiResponse<Department[]>>({
        queryKey: ["departments", filters],
        queryFn: async () => {
            const res = await DepartmentAPI.get(filters)
            if (!res.status) {
                CustomSnackbar("error", res?.data?.message || "Error al obtener prestadores")
            }
            return res
        },
        placeholderData: (prev) => prev,
        refetchOnWindowFocus: false,
        enabled: autoFetch
    })

    const createDepartmentMutation = useMutation({
        mutationFn: (payload: DepartmentDataForm) => DepartmentAPI.create(payload),
        onSuccess: (response) => {
            if (handleMutationSuccess(response, "Prestador creado correctamente")) {
                void queryClient.invalidateQueries({ queryKey: ["departments"] })
            }
        },
        onError: handleMutationError,
    })

    const updateDepartmentMutation = useMutation({
        mutationFn: ({ id, payload }: { id: number; payload: DepartmentDataForm }) =>
            DepartmentAPI.update(id, payload),
        onSuccess: (response) => {
            if (handleMutationSuccess(response, "Prestador actualizado correctamente")) {
                void queryClient.invalidateQueries({ queryKey: ["departments"] })
            }
        },
        onError: handleMutationError,
    })

    const deleteDepartmentMutation = useMutation({
        mutationFn: (id: number) => DepartmentAPI.delete(id),
        onSuccess: (response) => {
            if (handleMutationSuccess(response, "Prestador eliminado correctamente")) {
                void queryClient.invalidateQueries({ queryKey: ["departments"] })
            }
        },
        onError: handleMutationError,
    })

    const setSelectedDepartment = (data: Department | undefined): void => {
        dispatch(onSelectedDepartment(data))
    }

    const isLoading = useMemo(
        () =>
            isFetching ||
            createDepartmentMutation.isPending ||
            updateDepartmentMutation.isPending ||
            deleteDepartmentMutation.isPending,
        [
            isFetching,
            createDepartmentMutation.isPending,
            updateDepartmentMutation.isPending,
            deleteDepartmentMutation.isPending,
        ]
    )

    return {
        departments: data?.data?.detail || [],
        totalRows: data?.data?.total || 0,
        selectedDepartment,
        isLoading,
        setSelectedDepartment,
        createDepartment: createDepartmentMutation.mutateAsync,
        updateDepartment: async (id, payload) =>
            updateDepartmentMutation.mutateAsync({ id, payload }),
        deleteDepartment: deleteDepartmentMutation.mutateAsync,
        refetchDepartments,
    }
}
