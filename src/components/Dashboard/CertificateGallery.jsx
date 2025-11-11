import React from 'react';
import { useNavigate } from 'react-router-dom';
import { FaDownload, FaEye, FaShare } from 'react-icons/fa';
import '../../styles/Dashboard.css';

const CertificateGallery = ({ certificates }) => {
  const navigate = useNavigate();

  const handleView = (cert) => {
    navigate(`/certificate/${cert.certificate_id}`);
  };

  const handleDownload = async (cert) => {
    const apiUrl = process.env.REACT_APP_API_URL || 'http://localhost:8000/api';
    window.open(`${apiUrl}/certificate/download?certificateId=${cert.certificate_id}`, '_blank');
  };

  if (!certificates || certificates.length === 0) {
    return (
      <div className="empty-state">
        <p>You haven't earned any certificates yet.</p>
        <button onClick={() => navigate('/')} className="start-new-btn">
          Start Assessment
        </button>
      </div>
    );
  }

  return (
    <div className="certificate-gallery">
      {certificates.map((cert) => (
        <div key={cert.id} className="cert-card">
          <div className="cert-card-header">
            <span className="cert-tech-badge">{cert.technology}</span>
            <span className="cert-date">
              {new Date(cert.issued_date).toLocaleDateString()}
            </span>
          </div>

          <div className="cert-card-body">
            <h3>{cert.user_name}</h3>
            <p className="cert-card-description">
              {cert.technology} Technology Assessment
            </p>
            <p className="cert-card-id">ID: {cert.certificate_id.substring(0, 12)}...</p>
          </div>

          <div className="cert-card-actions">
            <button
              onClick={() => handleView(cert)}
              className="cert-action-btn view-btn"
              title="View Certificate"
            >
              <FaEye /> View
            </button>
            <button
              onClick={() => handleDownload(cert)}
              className="cert-action-btn download-btn"
              title="Download Certificate"
            >
              <FaDownload /> Download
            </button>
          </div>
        </div>
      ))}
    </div>
  );
};

export default CertificateGallery;
