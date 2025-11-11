import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import CertificateDisplay from '../components/Certificate/CertificateDisplay';
import SocialShareButtons from '../components/Shared/SocialShareButtons';
import api from '../services/api';
import '../styles/CertificateDisplay.css';

const CertificatePage = () => {
  const { certificateId } = useParams();
  const navigate = useNavigate();
  const [certificate, setCertificate] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (certificateId) {
      loadCertificate();
    }
  }, [certificateId]);

  const loadCertificate = async () => {
    try {
      const response = await api.get(`/certificate?certificateId=${certificateId}`);
      // Assuming the API returns a single certificate or array with one item
      const certData = Array.isArray(response.data.data)
        ? response.data.data[0]
        : response.data.data;

      setCertificate(certData);
    } catch (error) {
      console.error('Failed to load certificate:', error);
      alert('Failed to load certificate');
    } finally {
      setLoading(false);
    }
  };

  const handleDownload = () => {
    const apiUrl = process.env.REACT_APP_API_URL || 'http://localhost:8000/api';
    window.open(`${apiUrl}/certificate/download?certificateId=${certificateId}`, '_blank');
  };

  if (loading) {
    return <div className="loading">Loading certificate...</div>;
  }

  if (!certificate) {
    return (
      <div className="error-page">
        <h2>Certificate not found</h2>
        <button onClick={() => navigate('/dashboard')}>Go to Dashboard</button>
      </div>
    );
  }

  return (
    <div className="certificate-page">
      <div className="certificate-actions-top">
        <button onClick={() => navigate('/dashboard')} className="back-btn">
          ← Back to Dashboard
        </button>
        <button onClick={handleDownload} className="download-btn-primary">
          Download PDF
        </button>
      </div>

      <CertificateDisplay certificate={certificate} />

      <SocialShareButtons certificate={certificate} />
    </div>
  );
};

export default CertificatePage;
