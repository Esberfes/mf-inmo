import React from "react";
import './loading.css';

export default class DataGridLoading extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        const { loading } = this.props;

        return loading ?<div className="d-flex justify-content-center align-content-center"><div className="esb-data-grid-loading"><div></div><div></div></div> </div> : null;
    }
}
