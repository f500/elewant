import React, { Component } from "react";
import { Card, CardBody, CardText } from "reactstrap";
import logo from "./logo.svg";
import "./App.scss";

class App extends Component {
  render() {
    return (
      <div className="App">
        <header className="App-header">
          <img src={logo} className="App-logo" alt="logo" />
          <h1 className="App-title">Welcome to React</h1>
        </header>

        <div class="container">
          <div class="row justify-content-center">
            <div class="col-xs">
              <Card>
                <CardBody>
                  <CardText>
                    To get started, edit <code>src/App.js</code> and save to
                    reload.
                  </CardText>
                </CardBody>
              </Card>
            </div>
          </div>
        </div>
      </div>
    );
  }
}

export default App;
