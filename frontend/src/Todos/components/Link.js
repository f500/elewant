import React from 'react';
import PropTypes from 'prop-types';
import { NavLink } from 'reactstrap';

const Link = ({ active, children, onClick }) => {
  return (
    <NavLink
      active={active}
      href="#"
      onClick={e => {
        e.preventDefault();
        onClick();
      }}
    >
      {children}
    </NavLink>
  );
};

Link.propTypes = {
  active: PropTypes.bool.isRequired,
  children: PropTypes.node.isRequired,
  onClick: PropTypes.func.isRequired
};

export default Link;
