
class DataGridColumnConf {

    /**
     *
     * @param string name       name of the column received from query
     * @param string title      title to show in grid
     * @param string query      name of the column to send on query, useful when ambiguous columns
     */
    constructor(name, title, query) {
        this.name = name;
        this.title = title;
        this.query = query;
    }
}

class DataGridEndpointConf {

    constructor(get) {
        this.get = get;
    }
}

class DataGridConf {

    /**
     *
     * @param DataGridColumnConf[] columns
     */
    constructor(columns, endpoints) {
        this.columns = columns ? columns : [];
        this.endpoints = endpoints;
    }
}

export {
    DataGridColumnConf,
    DataGridEndpointConf,
    DataGridConf
}
