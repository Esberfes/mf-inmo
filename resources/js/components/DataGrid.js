import React from "react";
import ReactDOM from "react-dom";
import Axios from "axios";

class DataGrid extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            columns: [],
            data: {}
        };
    }

    componentDidMount() {
        this.setState({
            columns: [
                "id",
                "titulo",
                "telefono",
                "url_amigable",
                "precio",
                "metros",
                "relevante"
            ]
        });

        this.getData();
    }

    async getData(page) {
        const params = new URLSearchParams({
            page: page ? page : 1
        }).toString();

        return Axios.get(`/v1/locales?${params}`).then(res => {
            const data = res.data;
            console.log(data);
            this.setState({ data });
        });
    }

    renderHeaders() {
        return this.state.columns.map((c, i) => {
            const key = "gridTh_" + i;
            return <th key={key}>{c}</th>;
        });
    }

    renderDataRows() {
        const allowed = this.state.columns;

        if (!this.state.data.data) return null;

        const data = this.state.data.data.map(raw =>
            Object.keys(raw)
                .filter(key => allowed.includes(key))
                .reduce((obj, key) => {
                    obj[key] = raw[key];
                    return obj;
                }, {})
        );

        return data.map((row, iRow) => {
            const keyRow = "gridDr_" + iRow;
            const cells = Object.keys(row).map((key, iCell) => {
                const keyCell = "gridDc_" + iCell;
                return <td key={keyCell}>{row[key]}</td>;
            });

            return <tr key={keyRow}>{cells}</tr>;
        });
    }

    render() {
        return (
            <div className={"card mb-3 admin-table-wrapper"}>
                <div className={"admin-table-wrapper-body"}>
                    <table className={"admin-table"}>
                        <thead>
                            <tr key="headers">{this.renderHeaders()}</tr>
                        </thead>
                        <tbody>{this.renderDataRows()}</tbody>
                    </table>
                </div>
                <div className="admin-table-wrapper-footer">

                </div>
            </div>
        );
    }
}

if (document.querySelectorAll(".react-data-grid")) {
    document.querySelectorAll(".react-data-grid").forEach(e => {
        const props = Object.assign({}, e.dataset);
        console.log(props.grid);
        ReactDOM.render(<DataGrid {...props} />, e);
    });
}

export default DataGrid;
