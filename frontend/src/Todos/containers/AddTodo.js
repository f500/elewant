import React from 'react';
import { connect } from 'react-redux';
import { Button, Form, FormGroup, Input } from 'reactstrap';
import { addTodo } from '../actions';

let AddTodo = ({ dispatch }) => {
  let input;

  return (
    <Form
      inline
      onSubmit={e => {
        e.preventDefault();

        if (!input.value.trim()) {
          return;
        }

        dispatch(addTodo(input.value));
        input.value = '';
        input.focus();
      }}
    >
      <FormGroup className="mb-0 mr-2">
        <Input
          placeholder="New todo..."
          innerRef={node => {
            input = node;
          }}
        />
      </FormGroup>
      <Button>Add</Button>
    </Form>
  );
};

AddTodo = connect()(AddTodo);

export default AddTodo;
