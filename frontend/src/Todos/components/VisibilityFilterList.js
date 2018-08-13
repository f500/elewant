import React from 'react';
import { Nav, NavItem } from 'reactstrap';
import FilterLink from '../containers/FilterLink';
import { VisibilityFilters } from '../actions';

const VisibilityFilterList = () => (
  <Nav pills>
    <NavItem>
      <FilterLink filter={VisibilityFilters.SHOW_ALL}>All</FilterLink>
    </NavItem>
    <NavItem>
      <FilterLink filter={VisibilityFilters.SHOW_ACTIVE}>Active</FilterLink>
    </NavItem>
    <NavItem>
      <FilterLink filter={VisibilityFilters.SHOW_COMPLETED}>Completed</FilterLink>
    </NavItem>
  </Nav>
);

export default VisibilityFilterList;
