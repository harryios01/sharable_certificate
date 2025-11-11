import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAssessment } from '../context/AssessmentContext';
import { useAuth } from '../context/AuthContext';
import ProgressBar from '../components/Assessment/ProgressBar';
import QuestionCard from '../components/Assessment/QuestionCard';
import api from '../services/api';
import '../styles/Assessment.css';

const AssessmentPage = () => {
  const navigate = useNavigate();
  const {
    assessmentId,
    currentQuestion,
    answers,
    questions,
    saveAnswer,
    nextQuestion,
    previousQuestion,
    completeAssessment
  } = useAssessment();

  const { isAuthenticated } = useAuth();
  const [submitting, setSubmitting] = useState(false);

  const currentQuestionData = questions[currentQuestion - 1];

  const handleAnswerSelect = async (answer) => {
    saveAnswer(currentQuestion, answer);

    try {
      await api.post('/assessment/answer', {
        assessmentId,
        questionNumber: currentQuestion,
        answer
      });
    } catch (error) {
      console.error('Failed to save answer:', error);
    }
  };

  const handleNext = () => {
    if (!answers[currentQuestion]) {
      alert('Please select an answer before proceeding');
      return;
    }

    if (currentQuestion < 10) {
      nextQuestion();
    }
  };

  const handlePrevious = () => {
    previousQuestion();
  };

  const handleComplete = async () => {
    if (!answers[currentQuestion]) {
      alert('Please select an answer before completing');
      return;
    }

    setSubmitting(true);

    try {
      // Save final answer
      await api.post('/assessment/answer', {
        assessmentId,
        questionNumber: currentQuestion,
        answer: answers[currentQuestion]
      });

      completeAssessment();

      // Check if user is authenticated
      if (!isAuthenticated) {
        sessionStorage.setItem('returnPath', '/certificate');
        navigate('/login');
      } else {
        // Assign assessment to user and generate certificate
        await api.post('/assessment/assign', { assessmentId });
        const certResponse = await api.post('/certificate/generate', { assessmentId });

        navigate('/certificate/' + certResponse.data.data.certificate_id);
      }
    } catch (error) {
      console.error('Failed to complete assessment:', error);
      alert('Failed to complete assessment. Please try again.');
    } finally {
      setSubmitting(false);
    }
  };

  if (!assessmentId || !questions.length) {
    navigate('/select-technology');
    return null;
  }

  return (
    <div className="assessment-page">
      <ProgressBar current={currentQuestion} total={10} />

      <div className="assessment-content">
        <QuestionCard
          question={currentQuestionData}
          onAnswer={handleAnswerSelect}
          currentAnswer={answers[currentQuestion]}
        />

        <div className="assessment-navigation">
          <button
            className="nav-btn prev-btn"
            onClick={handlePrevious}
            disabled={currentQuestion === 1}
          >
            Previous
          </button>

          {currentQuestion < 10 ? (
            <button
              className="nav-btn next-btn"
              onClick={handleNext}
              disabled={!answers[currentQuestion]}
            >
              Next
            </button>
          ) : (
            <button
              className="nav-btn complete-btn"
              onClick={handleComplete}
              disabled={!answers[currentQuestion] || submitting}
            >
              {submitting ? 'Completing...' : 'Complete Assessment'}
            </button>
          )}
        </div>
      </div>
    </div>
  );
};

export default AssessmentPage;
