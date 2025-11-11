import React from 'react';
import GoogleLoginButton from '../components/Auth/GoogleLoginButton';
import InstagramLoginButton from '../components/Auth/InstagramLoginButton';
import '../styles/Auth.css';

const LoginPage = () => {
  return (
    <div className="login-page">
      <div className="login-container">
        <h1>Sign In to Continue</h1>
        <p className="login-subtitle">
          Sign in to save your certificate and access your dashboard
        </p>

        <div className="login-methods">
          <GoogleLoginButton />
          <InstagramLoginButton />
        </div>

        <p className="login-footer">
          By signing in, you agree to our Terms of Service and Privacy Policy
        </p>
      </div>
    </div>
  );
};

export default LoginPage;
