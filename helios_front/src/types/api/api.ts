export interface ApiResponse<T = undefined> {
    timestamp: string
    code: number
    status: boolean
    data: {
        message: string
        detail?: T
        total?: number
    }
}