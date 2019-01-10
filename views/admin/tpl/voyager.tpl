[{assign var="oConfig" value=$oView->getConfig()}]
<head>
    <title>OXID GraphQL Voyager</title>
    <meta http-equiv="Content-Type" content="text/html; charset=[{oxmultilang ident='charset'}]">

    <script crossorigin src="https://unpkg.com/react@16/umd/react.production.min.js"></script>
    <script crossorigin src="https://unpkg.com/react-dom@16/umd/react-dom.production.min.js"></script>
    <script src="https://cdn.jsdelivr.net/es6-promise/4.0.5/es6-promise.auto.min.js"></script>
    <script src="https://cdn.jsdelivr.net/fetch/0.9.0/fetch.min.js"></script>
    <!--
      These two files are served from jsDelivr CDN, however you may wish to
      copy them directly into your environment, or perhaps include them in your
      favored resource bundler.
     -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/graphql-voyager/dist/voyager.css" />
    <script src="https://cdn.jsdelivr.net/npm/graphql-voyager/dist/voyager.min.js"></script>

    <style>
        body {
            height: 100%;
            margin: 0;
            width: 100%;
            overflow: hidden;
        }
        #voyager {
            height: 100vh;
        }
        .doc-navigation > .active {
            color: #d64292;
        }
        .type-name.-input-obj, .type-name.-object {
            color: #d64292;
        }
        .typelist-item.-root a.type-name:after {
            background: #d64292;
        }
        .node .type-title polygon {
            fill: #d64292;
        }
        .node polygon {
            stroke: #d64292;
        }
        .type-link {
            fill: #d64292;
        }
        .jss21.jss18 {
            color: #d64292 !important;
        }
        #svg-pan-zoom-controls path {
            fill: #d64292;
        }
        .edge:hover path:not(.hover-path) {
            stroke: #d64292;
            stroke-width: 3;
        }
        .edge.hovered polygon, .edge:hover polygon {
            stroke: #d64292 !important;
            fill: #d64292 !important;
        }
        .edge.highlighted polygon{
            stroke: #54bad1;
            fill: #54bad1;
        }
        .edge.selected polygon {
            stroke: rgb(234, 51, 35);
            fill: rgb(234, 51, 35);
        }
        .jss47:after {
            border-bottom: 2px solid #d64292 !important;
        }
    </style>
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
                sortByAlphabet: true
            }
        })
    </script>
</body>