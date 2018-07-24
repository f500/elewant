import React from 'react';
import { NavLink } from 'react-router-dom';
import { Collapse, Container, Nav, Navbar, NavbarBrand, NavbarToggler, NavItem } from 'reactstrap';

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
                <NavLink to="/about" className="nav-link">
                  About
                </NavLink>
              </NavItem>
              <NavItem>
                <NavLink to="/new-herds" className="nav-link">
                  New Herds
                </NavLink>
              </NavItem>
              <NavItem>
                <NavLink to="/contributors" className="nav-link">
                  Contributors
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
