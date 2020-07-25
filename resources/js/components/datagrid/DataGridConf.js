class DataGridColumnConf {
    /**
     *
     * @param string     name               name of the column received from query
     * @param string     title              title to show in grid
     * @param string     query              name of the column to send on query, useful when ambiguous columns
     * @param boolean    sortable           indicates if the column can be ordered
     * @param boolean    filterable         indicates if the column can be filterable
     */
    constructor(name, title, query, sortable, filterable) {
        this.name = name;
        this.title = title;
        this.query = query;
        this.sortable = sortable;
        this.filterable = filterable;
    }
}

class DataGridConf {
    /**
     * @param DataGridColumnConf[] columns
     * @param string endpoint
     * @param React.Component actionComponents
     */
    constructor(
        columns,
        endpoint,
        actionComponents,
        currentPageResolver,
        previousPageResolver,
        nextPageResolver,
        sortResolver,
        filterResolver
    ) {
        this.columns = columns ? columns : [];
        this.endpoint = endpoint;
        this.actionComponents = actionComponents ? actionComponents : [];
        this.currentPageResolver = currentPageResolver;
        this.nextPageResolver = nextPageResolver;
        this.previousPageResolver = previousPageResolver;
        this.sortResolver = sortResolver;
        this.filterResolver = filterResolver;
    }
}

export { DataGridColumnConf, DataGridConf };
