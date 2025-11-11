import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAssessment } from '../../context/AssessmentContext';
import api from '../../services/api';
import '../../styles/Assessment.css';

const TechnologySelector = () => {
  const [selectedTech, setSelectedTech] = useState(null);
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();
  const { startAssessment, setQuestions } = useAssessment();

  const handleStart = async () => {
    if (!selectedTech) {
      alert('Please select a technology');
      return;
    }

    setLoading(true);

    try {
      // Start assessment
      const startResponse = await api.post('/assessment/start', {
        technology: selectedTech
      });

      const { assessmentId, sessionToken } = startResponse.data.data;

      // Get questions
      const questionsResponse = await api.get(`/assessment/questions?technology=${selectedTech}`);
      const { questions } = questionsResponse.data.data;

      startAssessment(selectedTech, assessmentId, sessionToken);
      setQuestions(questions);

      navigate('/assessment');
    } catch (error) {
      console.error('Failed to start assessment:', error);
      alert('Failed to start assessment. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="technology-selector">
      <h1>Choose Your Technology Path</h1>
      <p className="subtitle">Select a technology to begin your assessment</p>

      <div className="tech-options">
        <div
          className={`tech-card ${selectedTech === 'Ruby' ? 'selected' : ''}`}
          onClick={() => setSelectedTech('Ruby')}
        >
          <div className="tech-icon ruby-icon">💎</div>
          <h2>Ruby</h2>
          <p>Elegant and powerful programming language</p>
          <ul className="tech-features">
            <li>Object-Oriented Programming</li>
            <li>Ruby on Rails Framework</li>
            <li>Metaprogramming</li>
            <li>Rich Ecosystem</li>
          </ul>
        </div>

        <div
          className={`tech-card ${selectedTech === 'PHP' ? 'selected' : ''}`}
          onClick={() => setSelectedTech('PHP')}
        >
          <div className="tech-icon php-icon">🐘</div>
          <h2>PHP</h2>
          <p>Popular web development language</p>
          <ul className="tech-features">
            <li>Web Development</li>
            <li>Laravel & Symfony</li>
            <li>WordPress & CMS</li>
            <li>Easy Deployment</li>
          </ul>
        </div>
      </div>

      <button
        className="start-button"
        onClick={handleStart}
        disabled={!selectedTech || loading}
      >
        {loading ? 'Starting...' : 'Start Assessment'}
      </button>
    </div>
  );
};

export default TechnologySelector;
