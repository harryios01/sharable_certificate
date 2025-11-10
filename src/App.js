import React from 'react';
import Certificate from './Certificate';
import './App.css';

function App() {
  return (
    <div className="App">
      <Certificate
        recipientName="John Doe"
        awardText="For Outstanding Achievement"
        description="This certificate is presented in recognition of exceptional dedication, outstanding performance, and exemplary contribution to excellence."
        date="November 10, 2025"
        signature1Name="Director"
        signature2Name="President"
      />
    </div>
  );
}

export default App;
