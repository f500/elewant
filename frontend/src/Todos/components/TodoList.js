import React from 'react';
import PropTypes from 'prop-types';
import { ListGroup, ListGroupItem } from 'reactstrap';
import Todo from './Todo';

const TodoList = ({ todos, onTodoClick }) => {
  if (todos.length === 0) {
    return (
      <ListGroup className="my-3">
        <ListGroupItem color="light">Nothing to show.</ListGroupItem>
      </ListGroup>
    );
  }

  return (
    <ListGroup className="my-3">
      {todos.map(todo => (
        <Todo key={todo.id} {...todo} onClick={() => onTodoClick(todo.id)} />
      ))}
    </ListGroup>
  );
};

TodoList.propTypes = {
  todos: PropTypes.arrayOf(
    PropTypes.shape({
      id: PropTypes.string.isRequired,
      completed: PropTypes.bool.isRequired,
      text: PropTypes.string.isRequired
    }).isRequired
  ).isRequired,
  onTodoClick: PropTypes.func.isRequired
};

export default TodoList;
