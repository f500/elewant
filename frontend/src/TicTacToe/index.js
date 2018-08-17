import React from 'react';
import Game from './components/Game';

class TicTacToeContainer extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      history: [{ squares: Array(9).fill(null) }],
      stepNumber: 0,
      xIsNext: true
    };
  }

  handleBoardClick(i) {
    const history = this.state.history.slice(0, this.state.stepNumber + 1);
    const current = history[history.length - 1];

    let squares = current.squares.slice();
    let xIsNext = this.state.xIsNext;

    if (squares[i] || this.calculateWinner(squares)) {
      return;
    }

    squares[i] = xIsNext ? 'X' : 'O';
    xIsNext = !xIsNext;

    this.setState({
      history: history.concat([{ squares }]),
      xIsNext,
      stepNumber: history.length
    });
  }

  handleHistoryClick(step) {
    this.setState({
      stepNumber: step,
      xIsNext: step % 2 === 0
    });
  }

  calculateWinner(squares) {
    const lines = [[0, 1, 2], [3, 4, 5], [6, 7, 8], [0, 3, 6], [1, 4, 7], [2, 5, 8], [0, 4, 8], [2, 4, 6]];

    for (let i = 0; i < lines.length; i++) {
      const [a, b, c] = lines[i];

      if (squares[a] && squares[a] === squares[b] && squares[a] === squares[c]) {
        return squares[a];
      }
    }

    return null;
  }

  render() {
    const history = this.state.history;
    const current = history[this.state.stepNumber];
    const winner = this.calculateWinner(current.squares);

    return (
      <Game
        history={history}
        current={current}
        xIsNext={this.state.xIsNext}
        winner={winner}
        onHistoryClick={move => this.handleHistoryClick(move)}
        onBoardClick={i => this.handleBoardClick(i)}
      />
    );
  }
}

export default TicTacToeContainer;
