import React, { createContext, useState, useContext } from 'react';

const AssessmentContext = createContext();

export const useAssessment = () => {
  const context = useContext(AssessmentContext);
  if (!context) {
    throw new Error('useAssessment must be used within an AssessmentProvider');
  }
  return context;
};

export const AssessmentProvider = ({ children }) => {
  const [technology, setTechnology] = useState(null);
  const [assessmentId, setAssessmentId] = useState(null);
  const [sessionToken, setSessionToken] = useState(null);
  const [currentQuestion, setCurrentQuestion] = useState(1);
  const [answers, setAnswers] = useState({});
  const [questions, setQuestions] = useState([]);
  const [isCompleted, setIsCompleted] = useState(false);

  const startAssessment = (tech, id, session) => {
    setTechnology(tech);
    setAssessmentId(id);
    setSessionToken(session);
    setCurrentQuestion(1);
    setAnswers({});
    setIsCompleted(false);
  };

  const saveAnswer = (questionNumber, answer) => {
    setAnswers(prev => ({
      ...prev,
      [questionNumber]: answer
    }));
  };

  const nextQuestion = () => {
    if (currentQuestion < 10) {
      setCurrentQuestion(prev => prev + 1);
    }
  };

  const previousQuestion = () => {
    if (currentQuestion > 1) {
      setCurrentQuestion(prev => prev - 1);
    }
  };

  const completeAssessment = () => {
    setIsCompleted(true);
  };

  const resetAssessment = () => {
    setTechnology(null);
    setAssessmentId(null);
    setSessionToken(null);
    setCurrentQuestion(1);
    setAnswers({});
    setQuestions([]);
    setIsCompleted(false);
  };

  const value = {
    technology,
    assessmentId,
    sessionToken,
    currentQuestion,
    answers,
    questions,
    isCompleted,
    startAssessment,
    saveAnswer,
    nextQuestion,
    previousQuestion,
    completeAssessment,
    resetAssessment,
    setQuestions
  };

  return <AssessmentContext.Provider value={value}>{children}</AssessmentContext.Provider>;
};
