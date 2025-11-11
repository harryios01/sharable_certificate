import React from 'react';
import '../../styles/Verification.css';

const VerificationDisplay = ({ certificateData }) => {
  if (!certificateData) {
    return <div>Loading...</div>;
  }

  const { valid, certificate, message } = certificateData;

  if (!valid) {
    return (
      <div className="verification-container">
        <div className="verification-invalid">
          <div className="invalid-icon">❌</div>
          <h1>Certificate Not Found</h1>
          <p>{message || 'This certificate does not exist or has been revoked.'}</p>
        </div>
      </div>
    );
  }

  const formattedDate = new Date(certificate.issuedDate).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });

  return (
    <div className="verification-container">
      <div className="verification-valid">
        <div className="valid-icon">✓</div>
        <h1>Certificate Verified</h1>
        <p className="verification-subtitle">This is an authentic certificate</p>

        <div className="verification-details">
          {certificate.profilePicture && (
            <div className="verify-profile">
              <img
                src={certificate.profilePicture}
                alt={certificate.userName}
                className="verify-profile-img"
              />
            </div>
          )}

          <div className="verify-info">
            <div className="verify-row">
              <span className="verify-label">Certificate Holder:</span>
              <span className="verify-value">{certificate.userName}</span>
            </div>

            <div className="verify-row">
              <span className="verify-label">Technology:</span>
              <span className="verify-value technology-badge">{certificate.technology}</span>
            </div>

            <div className="verify-row">
              <span className="verify-label">Issued Date:</span>
              <span className="verify-value">{formattedDate}</span>
            </div>

            <div className="verify-row">
              <span className="verify-label">Certificate ID:</span>
              <span className="verify-value verify-id">{certificate.certificateId}</span>
            </div>

            <div className="verify-row">
              <span className="verify-label">Verifications:</span>
              <span className="verify-value">{certificate.verificationCount} times</span>
            </div>
          </div>

          {certificate.certificateUrl && (
            <div className="verify-actions">
              <a
                href={certificate.certificateUrl}
                target="_blank"
                rel="noopener noreferrer"
                className="view-certificate-btn"
              >
                View Full Certificate
              </a>
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default VerificationDisplay;
