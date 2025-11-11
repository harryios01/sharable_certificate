import React from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import '../styles/HomePage.css';

const HomePage = () => {
  const navigate = useNavigate();
  const { isAuthenticated } = useAuth();

  return (
    <div className="home-page">
      <div className="hero-section">
        <h1 className="hero-title">Technology Assessment & Certification Platform</h1>
        <p className="hero-subtitle">
          Prove your expertise. Earn your certificate. Share your achievement.
        </p>

        <div className="hero-actions">
          <button
            className="primary-btn"
            onClick={() => navigate('/select-technology')}
          >
            Start Assessment
          </button>
          {isAuthenticated ? (
            <button
              className="secondary-btn"
              onClick={() => navigate('/dashboard')}
            >
              My Dashboard
            </button>
          ) : (
            <button
              className="secondary-btn"
              onClick={() => navigate('/login')}
            >
              Sign In
            </button>
          )}
        </div>
      </div>

      <div className="features-section">
        <h2>How It Works</h2>
        <div className="features-grid">
          <div className="feature-card">
            <div className="feature-icon">1</div>
            <h3>Choose Technology</h3>
            <p>Select between Ruby or PHP to begin your assessment journey</p>
          </div>

          <div className="feature-card">
            <div className="feature-icon">2</div>
            <h3>Complete 10 Questions</h3>
            <p>Answer thoughtfully curated questions about your chosen technology</p>
          </div>

          <div className="feature-card">
            <div className="feature-icon">3</div>
            <h3>Earn Certificate</h3>
            <p>Sign in to save your beautiful, shareable certificate with QR verification</p>
          </div>

          <div className="feature-card">
            <div className="feature-icon">4</div>
            <h3>Share Achievement</h3>
            <p>Share your success on social media and invite others to join</p>
          </div>
        </div>
      </div>

      <div className="cta-section">
        <h2>Ready to Get Certified?</h2>
        <p>Join thousands of developers who have earned their certificates</p>
        <button
          className="cta-button"
          onClick={() => navigate('/select-technology')}
        >
          Get Started Now
        </button>
      </div>
    </div>
  );
};

export default HomePage;
