import React, { useState, useEffect } from 'react';
import '../../styles/Assessment.css';

const QuestionCard = ({ question, onAnswer, currentAnswer }) => {
  const [selectedAnswer, setSelectedAnswer] = useState(currentAnswer || '');

  useEffect(() => {
    setSelectedAnswer(currentAnswer || '');
  }, [question, currentAnswer]);

  const handleSelect = (option) => {
    setSelectedAnswer(option);
    onAnswer(option);
  };

  if (!question) {
    return <div>Loading question...</div>;
  }

  return (
    <div className="question-card">
      <h2 className="question-text">{question.question_text}</h2>

      <div className="options-container">
        {question.options && question.options.map((option, index) => (
          <div
            key={index}
            className={`option-card ${selectedAnswer === option ? 'selected' : ''}`}
            onClick={() => handleSelect(option)}
          >
            <div className="option-radio">
              {selectedAnswer === option && <div className="option-radio-selected"></div>}
            </div>
            <span className="option-text">{option}</span>
          </div>
        ))}
      </div>
    </div>
  );
};

export default QuestionCard;
