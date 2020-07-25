import React from "react";
import Axios from "axios";
import * as Qs from "query-string";

import DataGridColumnSorter from "./DataGridColumnSorter";
import DataGridLoading from "./DataGridLoading";

class DataGrid extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            columns: [],
            data: {},
            currentPage: 1,
            filters: {},
            orders: [],
            loading: true
        };
    }

    componentDidMount() {
        const { conf } = this.props;

        this.setState({
            columns: conf.columns
        });

        setTimeout(() => this.getData(null), 500);
    }

    async getData(page) {
        const { filters, orders } = this.state;
        const {
            endpoint,
            currentPageResolver,
            sortResolver,
            filterResolver
        } = this.props.conf;

        const getEndpoint = endpoint.endsWith("/") ? endpoint : endpoint + "/";
        const filterFields = filterResolver(filters);
        const orderFields = sortResolver(orders.filter(o => o.active));

        const params = new URLSearchParams(
            Object.assign(
                page ? page : {},
                orderFields ? orderFields : {},
                filterFields ? filterFields : {}
            )
        ).toString();

        console.log(params);

        this.setState({ loading: true }, () => {
            Axios.get(`${getEndpoint}?${params}`).then(res => {
                const data = res.data;
                this.setState({
                    data,
                    currentPage: currentPageResolver(data)
                });
                setTimeout(() => this.setState({ loading: false }), 300);
            });
        });
    }

    async orderHandler(column, direction, active) {
        const { orders } = this.state;

        await this.setState({
            orders: [
                ...orders.filter(o => o.column !== column && o.active),
                {
                    active,
                    column,
                    direction
                }
            ]
        });

        this.getData(null);
    }

    async onFilterChange(e, filter) {
        const value = e.target.value;
        await this.setState(prevState => ({
            filters: {
                ...prevState.filters,
                [filter]: value
            }
        }));

        this.getData(null);
    }

    async onNextPage() {
        const { nextPageResolver } = this.props.conf;
        const { data } = this.state;

        if (data) this.getData(nextPageResolver(data));
        else this.getData(null);
    }

    async onPreviousPage() {
        const { previousPageResolver } = this.props.conf;
        const { data } = this.state;

        if (data) this.getData(previousPageResolver(data));
        else this.getData(null);
    }

    renderHeaders() {
        const { columns } = this.state;

        return columns.map((c, i) => {
            const query = c.query ? c.query : c.name;
            const key = "gridTh_" + i;
            const sortable = c.sortable;

            return (
                <th key={key}>
                    <div className="d-flex align-items-center justify-content-between">
                        <span>{c.title}</span>
                        {sortable ? (
                            <DataGridColumnSorter
                                column={query}
                                handler={(column, direction, active) =>
                                    this.orderHandler(column, direction, active)
                                }
                            />
                        ) : null}
                    </div>
                </th>
            );
        });
    }

    renderFilters() {
        return this.state.columns.map((c, i) => {
            const key = "gridThf_" + i;
            const query = c.query ? c.query : c.name;
            const filterable = c.filterable;

            return (
                <th key={key}>
                    <div className="admin-table-wrapper-filters-group pt-0 ml-0">
                        <input
                            disabled={!filterable}
                            placeholder="--Sin filtro--"
                            className="form-control"
                            onChange={e => this.onFilterChange(e, query)}
                            type="search"
                        ></input>
                    </div>
                </th>
            );
        });
    }

    renderDataRows() {
        const allowed = this.state.columns.map(x => x.name);
        const rawData = this.state.data.data;

        if (!rawData) return null;

        const data = rawData.map(raw =>
            this.preferredOrder(
                Object.keys(raw)
                    .filter(key => allowed.includes(key))
                    .reduce((obj, key) => {
                        obj[key] = raw[key];
                        return obj;
                    }, {}),
                allowed
            )
        );

        return data.map((row, iRow) => {
            const keyRow = "gridDr_" + iRow;
            const keyComponent = "gridComp_" + iRow;
            const cells = Object.keys(row).map((key, iCell) => {
                const keyCell = "gridDc_" + iCell;
                return <td key={keyCell}>{row[key]}</td>;
            });
            const { actionComponents } = this.props.conf;

            const components = actionComponents.map((c, i) =>
                React.cloneElement(c, { key: `gridComp_${iRow}_${i}`, row })
            );

            return (
                <tr key={keyRow}>
                    {cells}
                    <td>
                        <div className="admin-table-actions-col-wrapper">
                            {components}
                        </div>
                    </td>
                </tr>
            );
        });
    }

    preferredOrder(obj, order) {
        var newObject = {};
        for (var i = 0; i < order.length; i++) {
            if (obj.hasOwnProperty(order[i])) {
                newObject[order[i]] = obj[order[i]];
            }
        }
        return newObject;
    }

    render() {
        const { title } = this.props;
        const { loading } = this.state;

        return (
            <div className={"card mb-3 admin-table-wrapper"}>
                <div className="admin-table-wrapper-header">
                    <div className="admin-table-wrapper-header-title">
                        {title}
                    </div>
                </div>
                <div className={"admin-table-wrapper-body"}>
                    <table className={"admin-table"}>
                        <thead>
                            <tr key="filters">
                                {this.renderFilters()}
                                <th>
                                    <DataGridLoading loading={loading} />{" "}
                                </th>
                            </tr>
                            <tr key="headers">
                                {this.renderHeaders()}
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>{this.renderDataRows()}</tbody>
                    </table>
                </div>
                <div className="admin-table-wrapper-footer">
                    <div className="admin-table-pagination">
                        <i
                            onClick={() => this.onPreviousPage()}
                            className="fas fa-caret-left"
                        ></i>
                        <span>{this.state.currentPage}</span>
                        <i
                            onClick={() => this.onNextPage()}
                            className="fas fa-caret-right"
                        ></i>
                    </div>
                </div>
            </div>
        );
    }
}

export default DataGrid;
