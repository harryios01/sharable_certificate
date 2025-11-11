import React, { createContext, useState, useContext, useEffect } from 'react';
import Cookies from 'js-cookie';
import api from '../services/api';

const AuthContext = createContext();

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);
  const [isAuthenticated, setIsAuthenticated] = useState(false);

  useEffect(() => {
    checkAuth();
  }, []);

  const checkAuth = async () => {
    const token = Cookies.get('authToken');

    if (token) {
      try {
        const response = await api.get('/auth/me');
        setUser(response.data.data);
        setIsAuthenticated(true);
      } catch (error) {
        console.error('Auth check failed:', error);
        logout();
      }
    }

    setLoading(false);
  };

  const login = (token, refreshToken, userData = null) => {
    Cookies.set('authToken', token, { expires: 1/96 }); // 15 minutes
    Cookies.set('refreshToken', refreshToken, { expires: 7 }); // 7 days
    setIsAuthenticated(true);
    if (userData) {
      setUser(userData);
    } else {
      checkAuth();
    }
  };

  const logout = () => {
    Cookies.remove('authToken');
    Cookies.remove('refreshToken');
    setUser(null);
    setIsAuthenticated(false);
  };

  const value = {
    user,
    loading,
    isAuthenticated,
    login,
    logout,
    checkAuth
  };

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
};
