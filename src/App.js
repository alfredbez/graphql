import React, { Component } from "react";
import GraphiQL from "graphiql";
import fetch from 'isomorphic-fetch';

import 'graphiql/graphiql.css';

function graphQLFetcher(graphQLParams) {
  return fetch(`${window.location.origin}/graphql/`, {
    method: "post",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(graphQLParams)
  }).then(response => response.json());
}

class App extends Component {
  render() {
    return <GraphiQL editorTheme="solarized light" fetcher={graphQLFetcher} />;
  }
}

export default App;
