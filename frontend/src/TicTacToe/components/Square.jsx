import React from 'react';
import PropTypes from 'prop-types';

import './Square.scss';

const Square = props => (
  <button className="tictactoe-square" onClick={props.onClick}>
    {props.value}
  </button>
);

Square.propTypes = {
  onClick: PropTypes.func.isRequired,
  value: PropTypes.string
};

export default Square;
