import { Layout, Image, Menu, Space, Avatar, Typography, Badge } from "antd"
import type { MenuProps } from "antd"
import { JSX } from "react"
import {
    DownOutlined,
    BellFilled,
    QuestionCircleFilled,
    ShoppingFilled
} from "@ant-design/icons"
import "./Header.css"
import XLogo from "../../icons/X-Logo.png"


const { Header: HeaderAnt } = Layout
const { Text } = Typography

const menuItems: MenuProps["items"] = [
    { key: "dashboard", label: "Dashboard" },
    { key: "organization", label: "Organización" },
    {
        key: "models",
        label: (
            <span>
                Modelos <DownOutlined style={{ fontSize: 10, marginLeft: 4 }} />
            </span>
        ),
        children: [
            { key: "1", label: "Modelo 01" },
            { key: "2", label: "Modelo 03" },
            { key: "3", label: "Modelo 04" },
        ],
    },
    {
        key: "tracking",
        label: (
            <span>
                Seguimiento <DownOutlined style={{ fontSize: 10, marginLeft: 4 }} />
            </span>
        ),
        children: [
            { key: "4", label: "Seguimiento 01" },
            { key: "5", label: "Seguimiento 02" },
            { key: "6", label: "Seguimiento 03" },
        ],
    },
]

export default function Header(): JSX.Element {
    return (
        <HeaderAnt className="app-header">
            <Space align="center" size="large" className="app-header-left">
                <Image
                    preview={false}
                    src={XLogo}
                    alt="Helios logo"
                    width={50}
                    height={30}
                    className="app-logo"
                />

                <Menu
                    mode="horizontal"
                    items={menuItems}
                    className="app-header-menu"
                    theme="dark"
                    overflowedIndicator={null}
                />
            </Space>

            <Space className="app-header-right" size="large" align="center">
                <ShoppingFilled className="header-icon" />
                <QuestionCircleFilled className="header-icon" />
                <Badge count={3} size="small">
                    <BellFilled className="header-icon" />
                </Badge>

                <Space size="small" align="center" className="user-section">
                    <Avatar className="user-avatar">A</Avatar>
                    <Text className="user-role">
                        Administrador
                        <DownOutlined style={{ fontSize: 10, marginLeft: 4 }} />
                    </Text>
                </Space>
            </Space>
        </HeaderAnt>
    )
}
