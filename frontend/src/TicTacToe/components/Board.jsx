import React from 'react';
import PropTypes from 'prop-types';
import Square from './Square';

import './Board.scss';

const Board = props => {
  const renderSquare = i => {
    return <Square value={props.squares[i]} onClick={() => props.onClick(i)} />;
  };

  return (
    <div className="tictactoe-board">
      <div className="tictactoe-board-row">
        {renderSquare(0)}
        {renderSquare(1)}
        {renderSquare(2)}
      </div>
      <div className="tictactoe-board-row">
        {renderSquare(3)}
        {renderSquare(4)}
        {renderSquare(5)}
      </div>
      <div className="tictactoe-board-row">
        {renderSquare(6)}
        {renderSquare(7)}
        {renderSquare(8)}
      </div>
    </div>
  );
};

Board.propTypes = {
  onClick: PropTypes.func.isRequired,
  squares: PropTypes.arrayOf(PropTypes.string).isRequired
};

export default Board;
