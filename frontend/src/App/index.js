import Footer from './Footer';
import Game from '../TicTacToe';
import React from 'react';
import { Route } from 'react-router-dom';
import Navigation from './Navigation';

import './index.scss';

class App extends React.Component {
  render() {
    return (
      <div className="elewant-app">
        <Navigation />
        <Route path="/about" component={Game} />
        <Route path="/new-herds" component={Game} />
        <Route path="/contributors" component={Game} />
        <Footer />
      </div>
    );
  }
}

export default App;
