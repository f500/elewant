import uuidv4 from 'uuid/v4';

export const ADD_TODO = 'ADD_TODO';
export const TOGGLE_TODO = 'TOGGLE_TODO';
export const SET_TODO_VISIBILITY_FILTER = 'SET_TODO_VISIBILITY_FILTER';

export const VisibilityFilters = {
  SHOW_ALL: 'SHOW_ALL',
  SHOW_COMPLETED: 'SHOW_COMPLETED',
  SHOW_ACTIVE: 'SHOW_ACTIVE'
};

export const addTodo = text => ({
  type: ADD_TODO,
  id: uuidv4(),
  text
});

export const toggleTodo = id => ({
  type: TOGGLE_TODO,
  id
});

export const setTodoVisibilityFilter = filter => ({
  type: SET_TODO_VISIBILITY_FILTER,
  filter
});
