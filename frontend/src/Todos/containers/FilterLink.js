import { connect } from 'react-redux';
import { setTodoVisibilityFilter } from '../actions';
import Link from '../components/Link';

const mapStateToProps = (state, ownProps) => {
  return {
    active: ownProps.filter === state.todoApp.visibilityFilter
  };
};

const mapDispatchToProps = (dispatch, ownProps) => {
  return {
    onClick: () => {
      dispatch(setTodoVisibilityFilter(ownProps.filter));
    }
  };
};

const FilterLink = connect(
  mapStateToProps,
  mapDispatchToProps
)(Link);

export default FilterLink;
