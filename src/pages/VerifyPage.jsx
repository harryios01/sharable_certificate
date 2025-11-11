import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import VerificationDisplay from '../components/Verification/VerificationDisplay';
import axios from 'axios';
import '../styles/Verification.css';

const VerifyPage = () => {
  const { certificateId } = useParams();
  const [certificateData, setCertificateData] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (certificateId) {
      verifyCertificate();
    }
  }, [certificateId]);

  const verifyCertificate = async () => {
    try {
      const apiUrl = process.env.REACT_APP_API_URL || 'http://localhost:8000/api';
      const response = await axios.get(`${apiUrl}/verify?certificateId=${certificateId}`);

      setCertificateData(response.data);
    } catch (error) {
      console.error('Verification failed:', error);
      setCertificateData({
        valid: false,
        message: 'Failed to verify certificate. Please try again.'
      });
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return <div className="loading">Verifying certificate...</div>;
  }

  return (
    <div className="verify-page">
      <VerificationDisplay certificateData={certificateData} />
    </div>
  );
};

export default VerifyPage;
