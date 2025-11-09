import { Layout } from "antd"
import { Outlet } from "react-router-dom"
import { JSX } from "react"
import Header from "../Header/Header"

const { Content } = Layout

export default function MainLayout(): JSX.Element {
    return (
        <Layout style={{ minHeight: "100vh" }}>
            <Header />
            <Content style={{ padding: "0px 10px" }}>
                <Outlet />
            </Content>
        </Layout>
    )
}
