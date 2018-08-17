import React from 'react';
import PropTypes from 'prop-types';
import { Button, Container } from 'reactstrap';
import Board from './Board';

import './Game.scss';

const Game = props => {
  const status = props.winner ? 'Winner: ' + props.winner : 'Next player: ' + (props.xIsNext ? 'X' : 'O');

  const moves = props.history.map((step, move) => {
    const desc = move ? 'Go to move #' + move : 'Go to game start';

    return (
      <li key={move}>
        <Button outline size="sm" onClick={() => props.onHistoryClick(move)}>
          {desc}
        </Button>
      </li>
    );
  });

  return (
    <Container>
      <div className="tictactoe-game">
        <Board squares={props.current.squares} onClick={i => props.onBoardClick(i)} />
        <div className="tictactoe-game-info">
          <div>{status}</div>
          <ol>{moves}</ol>
        </div>
      </div>
    </Container>
  );
};

const historyItemShape = PropTypes.shape({
  squares: PropTypes.arrayOf(PropTypes.string).isRequired
});

Game.propTypes = {
  history: PropTypes.arrayOf(historyItemShape).isRequired,
  current: historyItemShape.isRequired,
  xIsNext: PropTypes.bool.isRequired,
  winner: PropTypes.string,
  onHistoryClick: PropTypes.func.isRequired,
  onBoardClick: PropTypes.func.isRequired
};

export default Game;
