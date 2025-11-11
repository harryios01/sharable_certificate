import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import { AuthProvider } from './context/AuthContext';
import { AssessmentProvider } from './context/AssessmentContext';
import ProtectedRoute from './components/Auth/ProtectedRoute';

import HomePage from './pages/HomePage';
import LoginPage from './pages/LoginPage';
import TechnologySelectorPage from './pages/TechnologySelectorPage';
import AssessmentPage from './pages/AssessmentPage';
import CertificatePage from './pages/CertificatePage';
import DashboardPage from './pages/DashboardPage';
import VerifyPage from './pages/VerifyPage';
import AuthCallback from './components/Auth/AuthCallback';

import './App.css';

function App() {
  return (
    <Router>
      <AuthProvider>
        <AssessmentProvider>
          <div className="App">
            <Routes>
              <Route path="/" element={<HomePage />} />
              <Route path="/login" element={<LoginPage />} />
              <Route path="/auth/callback" element={<AuthCallback />} />
              <Route path="/select-technology" element={<TechnologySelectorPage />} />
              <Route path="/assessment" element={<AssessmentPage />} />
              <Route path="/verify/:certificateId" element={<VerifyPage />} />

              <Route
                path="/certificate/:certificateId"
                element={
                  <ProtectedRoute>
                    <CertificatePage />
                  </ProtectedRoute>
                }
              />
              <Route
                path="/dashboard"
                element={
                  <ProtectedRoute>
                    <DashboardPage />
                  </ProtectedRoute>
                }
              />
            </Routes>
          </div>
        </AssessmentProvider>
      </AuthProvider>
    </Router>
  );
}

export default App;
