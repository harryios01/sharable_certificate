import React from 'react';
import QRCode from 'react-qr-code';
import '../../styles/CertificateDisplay.css';

const CertificateDisplay = ({ certificate }) => {
  if (!certificate) {
    return <div>Loading certificate...</div>;
  }

  const {
    user_name,
    profile_picture,
    technology,
    issued_date,
    certificate_id,
    verification_url
  } = certificate;

  const formattedDate = new Date(issued_date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });

  return (
    <div className="certificate-display-container">
      <div className="certificate-wrapper">
        <div className="certificate-border">
          <div className="certificate-inner">
            {/* Header */}
            <div className="cert-header">
              <h1 className="cert-title">Certificate</h1>
              <div className="title-underline"></div>
              <p className="cert-subtitle">OF COMPLETION</p>
            </div>

            {/* Body */}
            <div className="cert-body">
              <p className="cert-presentation">This Certificate is Proudly Presented to</p>

              {profile_picture && (
                <div className="cert-profile">
                  <img src={profile_picture} alt={user_name} className="cert-profile-img" />
                </div>
              )}

              <h2 className="cert-recipient-name">{user_name}</h2>
              <div className="name-underline"></div>

              <p className="cert-award-text">
                Has successfully completed the <span className="cert-technology">{technology}</span> Technology Assessment
              </p>

              <p className="cert-description">
                This certificate is presented in recognition of exceptional dedication and outstanding performance
                in completing the technology evaluation program.
              </p>
            </div>

            {/* Footer */}
            <div className="cert-footer">
              <div className="cert-footer-left"></div>

              <div className="cert-footer-center">
                <div className="cert-qr-section">
                  <QRCode
                    value={verification_url}
                    size={80}
                    level="H"
                    className="cert-qr-code"
                  />
                  <p className="cert-qr-text">Scan to verify</p>
                </div>
              </div>

              <div className="cert-footer-right">
                <p className="cert-date">{formattedDate}</p>
                <p className="cert-id">ID: {certificate_id.substring(0, 8)}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default CertificateDisplay;
