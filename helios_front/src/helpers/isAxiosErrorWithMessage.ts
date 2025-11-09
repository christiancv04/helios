import { AxiosError } from "axios";

export function isAxiosErrorWithMessage(
    error: unknown
): error is AxiosError<{ message?: string }> {
    return typeof error === "object" && !!(error as AxiosError).isAxiosError
}