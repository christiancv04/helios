export interface Department {
    id: number
    name: string
    n_employees: number
    level: number
    ambassador: string
    status: number
    idcompany: number
    status_label: string
    level_label: string

    subdepartments: Department[]
    n_subdepartments: number

    upper_department_name?: string
    upper_department_n_employees?: number
    upper_department_level?: number
    upper_department_ambassador?: string
}


export interface DepartmentDataForm {
    name: string
    level?: 1 | 2
    ambassador?: string
    idcompany: number
}