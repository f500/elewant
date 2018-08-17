import React from 'react';
import { Route } from 'react-router-dom';

import Navigation from './Navigation';
import Game from '../TicTacToe';
import Todos from '../Todos';
import Footer from './Footer';

class App extends React.Component {
  render() {
    return (
      <div className="elewant-app">
        <Navigation />
        <Route path="/about" component={Game} />
        <Route path="/new-herds" component={Game} />
        <Route path="/contributors" component={Todos} />
        <Footer />
      </div>
    );
  }
}

export default App;
