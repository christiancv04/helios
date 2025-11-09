import {
    Button,
    Col,
    Input,
    InputRef,
    Radio,
    Row,
    Select,
    Space,
    Table,
    Tabs,
    Typography,
} from "antd"
import {
    PlusOutlined,
    UploadOutlined,
    DownloadOutlined,
    SearchOutlined,
    FilterFilled
} from "@ant-design/icons"
import { JSX, useEffect, useMemo, useRef, useState } from "react"

import "./DepartmentList.css"
import TabPane from "antd/es/tabs/TabPane"
import { CheckboxGroupProps } from "antd/es/checkbox"
import { ColumnsType, ColumnType, TablePaginationConfig } from "antd/es/table"
import { useDepartment } from "../../hooks/useDeparment"
import { SortOption } from "../../types/api/common"
import { useDebounce } from "../../helpers/debounce"

const { Title } = Typography

const options: CheckboxGroupProps<string>["options"] = [
    { label: "Listado", value: "Listado" },
    { label: "Arbol", value: "Arbol" },
]


export default function Home(): JSX.Element {
    const [page, setPage] = useState<number>(1)
    const [perPage, setPerPage] = useState<number>(10)
    const [orderBy, setOrderBy] = useState<SortOption | null>(null)
    const [search, setSearch] = useState<string>("")
    const [columnSelected, setSelectedColumn] = useState<string>("")

    const debouncedSearch = useDebounce(search, 350)
    const searchInput = useRef<InputRef>(null)

    const getColumnSearchProps = (dataIndex: string): ColumnType<any> => ({
        filterDropdown: ({ setSelectedKeys, selectedKeys, confirm, clearFilters }) => (
            <div style={{ padding: 8 }}>
                <Input
                    ref={searchInput}
                    placeholder={`Buscar`}
                    value={selectedKeys[0]}
                    onChange={e => setSelectedKeys(e.target.value ? [e.target.value] : [])}
                    onPressEnter={() => {
                        confirm()
                        setSelectedColumn(dataIndex)
                    }}
                    style={{ marginBottom: 8, display: "block" }}
                />
            </div>
        ),
        filterIcon: (filtered: boolean) => (
            <FilterFilled
                onClick={() => setSelectedColumn(dataIndex)}
                style={{
                    cursor: "pointer",
                    transition: "color 0.2s ease",
                }}
            />
        ),
        onFilter: (value, record) =>
            record[dataIndex]?.toString().toLowerCase().includes((value as string).toLowerCase()),
    })

    const columns: ColumnsType = [
        {
            title: "División",
            dataIndex: "name",
            sorter: true,
            ...getColumnSearchProps("name"),
        },
        {
            title: "División superior",
            dataIndex: "upper_department_name",
            sorter: true,
            ...getColumnSearchProps("name"),
        },
        {
            title: "Colaboradores",
            dataIndex: "n_employees",
            sorter: true,
        },
        {
            title: "Nivel",
            dataIndex: "level",
            sorter: true,
            ...getColumnSearchProps("name"),
        },
        {
            title: "Subdivisiones",
            dataIndex: "n_subdepartments",
        },
        {
            title: "Embajador",
            dataIndex: "ambassador",
        },
    ]

    const filters = useMemo(
        () => ({
            page,
            perPage,
            search: debouncedSearch || undefined,
            field: orderBy?.field || undefined,
            order: orderBy?.order || undefined,
            column: columnSelected || undefined
        }),
        [page, perPage, debouncedSearch, orderBy]
    )

    const {
        departments,
        totalRows,
        isLoading,
    } = useDepartment(filters)

    const pagination: TablePaginationConfig = {
        current: page,
        pageSize: perPage,
        total: totalRows,
        showSizeChanger: true,
        pageSizeOptions: [5, 10, 25, 50],
        locale: { items_per_page: "/ página" },
        showTotal: (total) => `Total de departamentos: ${total}`,
        position: ["bottomLeft", "bottomRight"],
    }

    const handleTableChange = (newPagination: TablePaginationConfig, _: any, sorter: any) => {
        if (newPagination.current) {
            setPage(newPagination.current)
        }

        if (newPagination.pageSize) {
            setPerPage(newPagination.pageSize)
        }

        if (sorter && sorter.field) {
            const order =
                sorter.order === "ascend"
                    ? "asc"
                    : sorter.order === "descend"
                        ? "desc"
                        : "desc"

            setOrderBy({
                field: sorter.field,
                order,
            })
        } else {
            setOrderBy(null)
        }
    }

    return (
        <div className="page-content">
            <Row gutter={[16, 16]} className="section-header">
                <Col xs={24}>
                    <Title level={4}>Organización</Title>
                </Col>

                <Col xs={24} md={12}>
                    <Tabs defaultActiveKey="1">
                        <TabPane tab="Divisiones" key="1" />
                        <TabPane tab="Colaboradores" key="2" />
                    </Tabs>
                </Col>

                <Col xs={24} md={12} className="actions-col">
                    <Space>
                        <Button
                            type="primary"
                            icon={<PlusOutlined />}
                            className="buttons"
                        />
                        <Button
                            icon={<UploadOutlined />}
                            className="buttons btn-secondary"
                        />
                        <Button
                            icon={<DownloadOutlined />}
                            className="buttons btn-secondary"
                        />
                    </Space>
                </Col>
            </Row>

            <Row gutter={[16, 16]} className="section-body">
                <Col xs={24}>
                    <Row align="middle" justify="space-between" gutter={[8, 8]}>
                        <Col flex="auto">
                            <Radio.Group
                                className="radio-group"
                                options={options}
                                defaultValue="Listado"
                                optionType="button"
                            />
                        </Col>

                        <Col>
                            <Space>
                                <Select
                                    className="filter-select"
                                    placeholder="Columnas"
                                    style={{ width: 150 }}
                                    value={columnSelected}
                                    onChange={(value) => setSelectedColumn(value)}
                                    options={[
                                        {
                                            value: "name",
                                            label: "Division"
                                        },
                                        {
                                            value: "upper_department_name",
                                            label: "Division superior"
                                        },
                                        {
                                            value: "n_employees",
                                            label: "Colaboradores"
                                        },
                                        {
                                            value: "level",
                                            label: "Nivel"
                                        },
                                        {
                                            value: "ambassador",
                                            label: "Embajador"
                                        }
                                    ]}
                                />
                                <Input
                                    className="input-search"
                                    suffix={<SearchOutlined />}
                                    placeholder="Buscar"
                                    value={search}
                                    onChange={(e) => {
                                        setSearch(e.target.value)
                                        setPage(1)
                                    }}
                                    style={{ width: 200 }}
                                />
                            </Space>
                        </Col>
                    </Row>
                </Col>

                <Col xs={24}>
                    <Table
                        columns={columns}
                        dataSource={departments || []}
                        loading={isLoading}
                        rowSelection={{}}
                        onChange={handleTableChange}
                        pagination={pagination}
                        rowKey="id"
                    />
                </Col>
            </Row>
        </div>
    )
}
