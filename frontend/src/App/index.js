import Footer from './Footer';
import Game from '../TicTacToe';
import React from 'react';

import './index.scss';

class App extends React.Component {
  render() {
    return (
      <div className="elewant-app">
        <Game />
        <Footer />
      </div>
    );
  }
}

export default App;
