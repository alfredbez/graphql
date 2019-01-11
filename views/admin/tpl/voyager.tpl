[{assign var="oConfig" value=$oView->getConfig()}]
<head>
    <title>OXID GraphQL Voyager</title>
    <meta http-equiv="Content-Type" content="text/html; charset=[{oxmultilang ident='charset'}]">

    <script crossorigin src="https://unpkg.com/react@16/umd/react.production.min.js"></script>
    <script crossorigin src="https://unpkg.com/react-dom@16/umd/react-dom.production.min.js"></script>
    <script src="https://cdn.jsdelivr.net/es6-promise/4.0.5/es6-promise.auto.min.js"></script>
    <script src="https://cdn.jsdelivr.net/fetch/0.9.0/fetch.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/graphql-voyager/dist/voyager.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/graphql-voyager/dist/voyager.css" />
    <link rel="stylesheet" type="text/css" href="[{$oViewConf->getModuleUrl('oxps/graphql','out/src/css/voyager.css')}]">
</head>
<body>
    <noscript>
        You need to enable JavaScript to run this app.
    </noscript>

    <div id="voyager">Loading...</div>

    <script>
       // Defines a GraphQL introspection fetcher using the fetch API. You're not required to
        // use fetch, and could instead implement introspectionProvider however you like,
        // as long as it returns a Promise
        // Voyager passes introspectionQuery as an argument for this function
        function introspectionProvider(introspectionQuery) {
            // This expects a GraphQL server at the path /graphql.
            return fetch(`${window.location.origin}/graphql/`, {
                method: 'post',
                headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': 'Bearer [{$sBearer}]'
                },
                body: JSON.stringify({query: introspectionQuery}),
                credentials: 'include'
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

        // Render <Voyager />
        GraphQLVoyager.init(document.getElementById('voyager'), {
            introspection: introspectionProvider,
            hideSettings: false,
            displayOptions: {
                sortByAlphabet: false
            }
        })
    </script>
</body>