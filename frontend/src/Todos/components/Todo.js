import React from 'react';
import PropTypes from 'prop-types';
import { ListGroupItem } from 'reactstrap';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCheck } from '@fortawesome/free-solid-svg-icons';

import './Todo.scss';

const Todo = ({ onClick, completed, text }) => (
  <ListGroupItem
    onClick={onClick}
    style={{
      textDecoration: completed ? 'line-through' : 'none'
    }}
  >
    {completed && <FontAwesomeIcon icon={faCheck} className="float-right todo-completed" />}
    {text}
  </ListGroupItem>
);

Todo.propTypes = {
  onClick: PropTypes.func.isRequired,
  completed: PropTypes.bool.isRequired,
  text: PropTypes.string.isRequired
};

export default Todo;
