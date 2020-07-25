import React from "react";

export default class DataGridColumnSorter extends React.Component {

    constructor(props) {
        super(props);

        const column = props.column;

        this.state = {
            active: false,
            column: column,
            direction: "desc"
        };
    }

    async onClick() {
        const { handler } = this.props;
        const { column, direction, active } = this.state;

        const newDirection = direction === "desc" && active ? "asc": "desc";
        const newActive  = direction === "asc" ? false : true;

        this.setState({
            direction: newDirection,
            active: newActive
        });

        handler(column, newDirection, newActive);
    }

    render() {
        const { column, direction, active } = this.state;
        const opacity = active ? {} : { opacity: "0.2", cursor: "pointer" };
        const key = "gridOrder_" + column;

        return direction === "desc" ? (
            <i
                key={key}
                onClick={e => this.onClick(e)}
                className="fas fa-sort-amount-down"
                style={opacity}
            ></i>
        ) : (
            <i
                key={key}
                onClick={e => this.onClick(e)}
                className="fas fa-sort-amount-up"
                style={opacity}
            ></i>
        );
    }
}

