import { createSlice, PayloadAction } from "@reduxjs/toolkit"
import { Department } from "../types/slices/Department"


interface DepartmentState {
    selectedDepartment?: Department
}

const initialState: DepartmentState = {
    selectedDepartment: undefined,
}

const departmentSlice = createSlice({
    name: "department",
    initialState,
    reducers: {
        onSelectedDepartment: (state, action: PayloadAction<Department | undefined>) => {
            state.selectedDepartment = action.payload
        },
    },
})

export const { onSelectedDepartment } = departmentSlice.actions
export default departmentSlice.reducer
