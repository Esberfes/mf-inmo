import React from "react";
import ReactDOM from "react-dom";
import Axios from "axios";
import * as Qs from "query-string";

import DataGrid from "./datagrid/DataGrid";
import { DataGridColumnConf, DataGridConf } from "./datagrid/DataGridConf";

/*
 <button onClick={() => this.onEditClick(primaryValue)} className="btn btn-sm btn-outline-primary">
                                Editar
                            </button>
                            <button onClick={() => this.onRemoveClick(primaryValue)} className="btn btn-sm btn-outline-danger">
                                Eliminar
                            </button>
*/

/*
    onRemoveClick(primary) {
        const { endpoint } = this.props.conf;

        const end = endpoint.endsWith("/")
            ? endpoint
            : endpoint + "/";

        this.setState({ loading: true }, () => {
            Axios.delete(`${end}${primary}`).then(res => {
                this.getData(null);
                setTimeout(() => this.setState({ loading: false }), 300);
            });
        });
    }

    onEditClick(primary) {
        const { edit } = this.props.conf;
        edit(primary);
    }
*/
class LocalesDataGridAction extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        const { handler, row } = this.props;

        return (
            <button
                onClick={() => handler(row)}
                className="btn btn-sm btn-outline-primary"
            >
                Editar
            </button>
        );
    }
}

class LocalesDataGrid extends React.Component {
    constructor(props) {
        super(props);
        this.conf = new DataGridConf(
            [
                new DataGridColumnConf("id", "#", null, true, true),
                new DataGridColumnConf(
                    "titulo",
                    "Titulo",
                    "locales.titulo",
                    false,
                    true
                ),
                new DataGridColumnConf(
                    "sectores.titulo",
                    "Sector",
                    null,
                    true,
                    false
                ),
                new DataGridColumnConf(
                    "telefono",
                    "Teléfono",
                    null,
                    true,
                    true
                ),
                new DataGridColumnConf(
                    "poblaciones.nombre",
                    "Población",
                    null,
                    true,
                    true
                ),
                new DataGridColumnConf("precio", "Precio", null, true, true),
                new DataGridColumnConf("metros", "Metros", null, true, true),
                new DataGridColumnConf(
                    "relevante",
                    "Relevante",
                    null,
                    true,
                    true
                )
            ],
            "/v1/locales",
            [<LocalesDataGridAction handler={this.onEditClick} />],
            this.currentPageResolver,
            this.previousPageResolver,
            this.nextPageResolver,
            this.sortResolver,
            this.filterResolver
        );
    }

    onEditClick(row) {
        console.log(row);
    }

    filterResolver(filters) {
        return {
            filter: Object.keys(filters)
                .map(key => `${key}:${filters[key]}`)
                .join(";")
        };
    }

    sortResolver(fields) {
        return {
            order: fields
                ? fields.map(o => `${o.column}:${o.direction}`).join(";")
                : null
        };
    }

    currentPageResolver(data) {
        return data && data.page ? data.page : 1;
    }

    previousPageResolver(data) {
        if (!data || !data.previous) return null;

        const urlParams = Qs.parse(data.previous.split("?")[1]);
        const page = urlParams.page ? urlParams.page : 1;

        return {
            page
        };
    }

    nextPageResolver(data) {
        if (!data || !data.next) return null;

        const urlParams = Qs.parse(data.next.split("?")[1]);
        const page = urlParams.page ? urlParams.page : 1;

        return {
            page
        };
    }

    render() {
        return <DataGrid conf={this.conf} {...this.props} />;
    }
}

if (document.getElementById("localesGrid")) {
    const element = document.getElementById("localesGrid");
    const props = Object.assign({}, element.dataset);
    ReactDOM.render(<LocalesDataGrid {...props} />, element);
}

export default LocalesDataGrid;
