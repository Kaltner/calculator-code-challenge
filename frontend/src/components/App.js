import React, { Component } from 'react';
import Calculator from './Calculator/Calculator';
import '../App.css';

class App extends Component {
  render() {
    return (
      <div className="App">
        <div className="container">
          <Calculator />
        </div>
      </div>
    );
  }
}

export default App;
