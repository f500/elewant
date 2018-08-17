import React from 'react';
import { NavLink } from 'react-router-dom';
import { Collapse, Container, Nav, Navbar, NavbarBrand, NavbarToggler, NavItem } from 'reactstrap';

import './Navigation.scss';

class Navigation extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      isOpen: false
    };
  }

  toggle() {
    this.setState({
      isOpen: !this.state.isOpen
    });
  }

  render() {
    return (
      <Navbar color="dark" dark expand="md">
        <Container>
          <NavbarBrand href="/">Elewant</NavbarBrand>
          <NavbarToggler onClick={() => this.toggle()} />
          <Collapse isOpen={this.state.isOpen} navbar>
            <Nav className="ml-auto" navbar>
              <NavItem>
                <NavLink to="/experiment" className="nav-link">
                  The Experiment
                </NavLink>
              </NavItem>
              <NavItem>
                <NavLink to="/tictactoe" className="nav-link">
                  Tic Tac Toe
                </NavLink>
              </NavItem>
              <NavItem>
                <NavLink to="/todos" className="nav-link">
                  Todo's
                </NavLink>
              </NavItem>
            </Nav>
          </Collapse>
        </Container>
      </Navbar>
    );
  }
}

export default Navigation;
