import React from 'react';
import { Route } from 'react-router-dom';

import Navigation from './Navigation';
import Experiment from '../Experiment';
import Game from '../TicTacToe';
import Todos from '../Todos';
import Footer from './Footer';

class App extends React.Component {
  render() {
    return (
      <div className="elewant-app">
        <Navigation />
        <Route path="/experiment" component={Experiment} />
        <Route path="/tictactoe" component={Game} />
        <Route path="/todos" component={Todos} />
        <Footer />
      </div>
    );
  }
}

export default App;
