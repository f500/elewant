import { connect } from 'react-redux';
import { toggleTodo, VisibilityFilters } from '../actions';
import TodoList from '../components/TodoList';

const getVisibleTodos = (todos, filter) => {
  switch (filter) {
    case VisibilityFilters.SHOW_COMPLETED:
      return todos.filter(t => t.completed);
    case VisibilityFilters.SHOW_ACTIVE:
      return todos.filter(t => !t.completed);
    case VisibilityFilters.SHOW_ALL:
    default:
      return todos;
  }
};

const mapStateToProps = state => {
  return {
    todos: getVisibleTodos(state.todoApp.todos, state.todoApp.visibilityFilter)
  };
};

const mapDispatchToProps = dispatch => {
  return {
    onTodoClick: id => {
      dispatch(toggleTodo(id));
    }
  };
};

const VisibleTodoList = connect(
  mapStateToProps,
  mapDispatchToProps
)(TodoList);

export default VisibleTodoList;
