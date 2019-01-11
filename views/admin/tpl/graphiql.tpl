[{assign var="oConfig" value=$oView->getConfig()}]
<head>
    <title>OXID GraphQL</title>
    <meta http-equiv="Content-Type" content="text/html; charset=[{oxmultilang ident='charset'}]">

    <script crossorigin src="https://unpkg.com/react@16/umd/react.production.min.js"></script>
    <script crossorigin src="https://unpkg.com/react-dom@16/umd/react-dom.production.min.js"></script>
    <script src="https://cdn.jsdelivr.net/es6-promise/4.0.5/es6-promise.auto.min.js"></script>
    <script src="https://cdn.jsdelivr.net/fetch/0.9.0/fetch.min.js"></script>
    <script src="[{$oViewConf->getModuleUrl('oxps/graphql','out/src/js/graphiql.min.js')}]"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.23.0/theme/solarized.css" />
    <link rel="stylesheet" type="text/css" href="[{$oViewConf->getModuleUrl('oxps/graphql','out/src/css/graphiql.css')}]">
</head>
<body>
    <noscript>
        You need to enable JavaScript to run this app.
    </noscript>

    <div id="graphiql">Loading...</div>

    <script>
        // Parse the search string to get url parameters.
        var search = window.location.search;
        var parameters = {};
        search.substr(1).split('&').forEach(function (entry) {
        var eq = entry.indexOf('=');
        if (eq >= 0) {
            parameters[decodeURIComponent(entry.slice(0, eq))] =
            decodeURIComponent(entry.slice(eq + 1));
        }
        });

        // if variables was provided, try to format it.
        if (parameters.variables) {
        try {
            parameters.variables =
            JSON.stringify(JSON.parse(parameters.variables), null, 2);
        } catch (e) {
            // Do nothing, we want to display the invalid JSON as a string, rather
            // than present an error.
        }
        }

        // When the query and variables string is edited, update the URL bar so
        // that it can be easily shared
        function onEditQuery(newQuery) {
            parameters.query = newQuery;
        }

        function onEditVariables(newVariables) {
            parameters.variables = newVariables;
        }

        function onEditOperationName(newOperationName) {
            parameters.operationName = newOperationName;
        }

        // Defines a GraphQL fetcher using the fetch API. You're not required to
        // use fetch, and could instead implement graphQLFetcher however you like,
        // as long as it returns a Promise or Observable.
        function graphQLFetcher(graphQLParams) {
            // This expects a GraphQL server at the path /graphql.
            return fetch(`${window.location.origin}/graphql/`, {
                method: 'post',
                headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': 'Bearer [{$sBearer}]'
                },
                body: JSON.stringify(graphQLParams),
                credentials: 'include',
            }).then(function (response) {
                return response.text();
            }).then(function (responseBody) {
                try {
                return JSON.parse(responseBody);
                } catch (error) {
                return responseBody;
                }
            });
        }

        ReactDOM.render(
            React.createElement(GraphiQL, {
                fetcher: graphQLFetcher,
                query: parameters.query,
                variables: parameters.variables,
                operationName: parameters.operationName,
                onEditQuery: onEditQuery,
                onEditVariables: onEditVariables,
                onEditOperationName: onEditOperationName
            }),
            document.getElementById('graphiql')
        );
    </script>
</body>