import React from "react";
import ReactDOM from "react-dom";
import Axios from "axios";
import * as Qs from "query-string";
import './loading.css';

import {
    DataGridColumnConf,
    DataGridConf,
    DataGridEndpointConf
} from "./DataGridConf";
import DataGridColumnSorter from "./DataGridColumnSorter";

class DataGridLoading extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        return <div className="lds-ring"><div></div><div></div><div></div><div></div></div>;
    }
}

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
        const { endpoints } = this.props.conf;
        const getEndpoint = endpoints.get.endsWith("/")
            ? endpoints.get
            : endpoints.get + "/";

        const filter = Object.keys(filters)
            .map(key => `${key}:${filters[key]}`)
            .join(";");

        const order = orders
            .filter(o => o.active)
            .map(o => `${o.column}:${o.direction}`)
            .join(";");

        const params = new URLSearchParams({
            page: page ? page : 1,
            filter,
            order
        }).toString();

        this.setState({ loading: true }, () => {
            Axios.get(`${getEndpoint}?${params}`).then(res => {
                const data = res.data;
                this.setState({
                    data,
                    currentPage: page ? page : 1
                });
                setTimeout(() => this.setState({loading: false}), 500);
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

    renderHeaders() {
        const { columns } = this.state;

        return columns.map((c, i) => {
            const query = c.query ? c.query : c.name;
            const key = "gridTh_" + i;
            return (
                <th key={key}>
                    <div className="d-flex align-items-center justify-content-between">
                        <span>{c.title}</span>
                        <DataGridColumnSorter
                            column={query}
                            handler={(column, direction, active) =>
                                this.orderHandler(column, direction, active)
                            }
                        />
                    </div>
                </th>
            );
        });
    }

    renderFilters() {
        return this.state.columns.map((c, i) => {
            const key = "gridThf_" + i;
            const query = c.query ? c.query : c.name;
            return (
                <th key={key}>
                    <div className="admin-table-wrapper-filters-group pt-0 ml-0">
                        <input
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

    renderLoding() {
        const { loading } = this.state;

        return loading ? <DataGridLoading /> : null;
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
            const cells = Object.keys(row).map((key, iCell) => {
                const keyCell = "gridDc_" + iCell;
                return <td key={keyCell}>{row[key]}</td>;
            });
            return (
                <tr key={keyRow}>
                    {cells}
                    <td>
                        <div className="admin-table-actions-col-wrapper">
                            <button className="btn btn-sm btn-outline-primary">
                                Editar
                            </button>
                            <button className="btn btn-sm btn-outline-danger">
                                Eliminar
                            </button>
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
        if (this.state.data && this.state.data.next) {
            const urlParams = Qs.parse(this.state.data.next.split("?")[1]);
            const page = urlParams.page ? urlParams.page : 1;
            this.getData(page);
        } else {
            this.getData(null);
        }
    }

    async onPreviousPage() {
        if (this.state.data && this.state.data.previous) {
            const urlParams = Qs.parse(this.state.data.previous.split("?")[1]);
            const page = urlParams.page ? urlParams.page : 1;
            this.getData(page);
        } else {
            this.getData(null);
        }
    }

    render() {
        const { title } = this.props;

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
                                <th>{this.renderLoding()}</th>
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

const conf = new DataGridConf(
    [
        new DataGridColumnConf("id", "#"),
        new DataGridColumnConf("titulo", "Titulo", "locales.titulo"),
        new DataGridColumnConf("sectores.titulo", "Sector"),
        new DataGridColumnConf("telefono", "Teléfono"),
        new DataGridColumnConf("poblaciones.nombre", "Población"),
        new DataGridColumnConf("precio", "Precio"),
        new DataGridColumnConf("metros", "Metros"),
        new DataGridColumnConf("relevante", "Relevante")
    ],
    new DataGridEndpointConf("/v1/locales")
);

if (document.querySelectorAll(".react-data-grid")) {
    document.querySelectorAll(".react-data-grid").forEach(e => {
        const props = Object.assign({}, e.dataset);
        ReactDOM.render(<DataGrid conf={conf} {...props} />, e);
    });
}

export default DataGrid;
