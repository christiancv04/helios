import type { AxiosError } from "axios"

import { CustomSnackbar } from "../components/common/CustomSnackbar/CustomSnackbar"
import { ApiResponse } from "../types/api/api"
import { isAxiosErrorWithMessage } from "./isAxiosErrorWithMessage"

export function handleMutationSuccess<T = unknown>(
    response: ApiResponse<T>,
    defaultMessage?: string
): boolean {
    
    if (!response.status) {
        CustomSnackbar("error", response.data?.message || "Error desconocido")
        return false
    }

    const message = response?.data?.message || defaultMessage || "Operación realizada correctamente"
    CustomSnackbar("success", message)
    
    return true
}

export function handleMutationError(error: unknown): void {
    let message = "Error desconocido"

    if (isAxiosErrorWithMessage(error)) {
        const err = error as AxiosError<{ data?: { message?: string }; message?: string }>
        message =
            err.response?.data?.data?.message ??
            err.response?.data?.message ??
            err.message ??
            message
    } else if (error instanceof Error) {
        message = error.message
    }

    CustomSnackbar("error", message)
}
