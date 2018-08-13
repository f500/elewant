import React from 'react';
import AddTodo from './containers/AddTodo';
import VisibilityFilterList from './components/VisibilityFilterList';
import VisibleTodoList from './containers/VisibleTodoList';
import { Container, Row, Col } from 'reactstrap';

const Todos = () => (
  <Container>
    <Row>
      <Col>
        <VisibilityFilterList />
      </Col>
    </Row>
    <Row>
      <Col>
        <VisibleTodoList />
      </Col>
    </Row>
    <Row>
      <Col>
        <AddTodo />
      </Col>
    </Row>
  </Container>
);

export default Todos;
