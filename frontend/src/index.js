import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter } from 'react-router-dom';
import { Provider } from 'react-redux';
import { createStore } from 'redux';

import App from './App';
import elewantApp from './reducers';
import registerServiceWorker from './registerServiceWorker';

import 'bootstrap/scss/bootstrap.scss';

const store = createStore(elewantApp);

ReactDOM.render(
  <Provider store={store}>
    <BrowserRouter>
      <App />
    </BrowserRouter>
  </Provider>,
  document.getElementById('elewant-root')
);

if (process.env.NODE_ENV !== 'production' && module.hot) {
  module.hot.accept('./reducers', () => {
    store.replaceReducer(elewantApp);
  });

  module.hot.accept('./App', () => {
    ReactDOM.render(
      <Provider store={store}>
        <BrowserRouter>
          <App />
        </BrowserRouter>
      </Provider>,
      document.getElementById('elewant-root')
    );
  });
}

registerServiceWorker();
