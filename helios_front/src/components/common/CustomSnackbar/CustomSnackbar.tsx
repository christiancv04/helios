import { ExclamationCircleOutlined } from "@ant-design/icons"
import { toast } from "react-hot-toast"

export const CustomSnackbar = (
    alert_type: "warning" | "success" | "error",
    message: string
): string | undefined => {
    switch (alert_type) {
        case "warning":
            return toast(message, {
                icon: <ExclamationCircleOutlined style={{ color: "orange" }} />,
            })
        case "success":
            return toast.success(message)
        case "error":
            return toast.error(message)
    }
}
