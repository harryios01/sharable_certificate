import React from 'react';
import './Certificate.css';

const Certificate = ({
  recipientName = "John Doe",
  awardText = "For Outstanding Achievement",
  description = "This certificate is presented in recognition of exceptional dedication, outstanding performance, and exemplary contribution to excellence.",
  date = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }),
  signature1Name = "Director",
  signature2Name = "President"
}) => {
  return (
    <div className="certificate-container">
      <div className="certificate">
        {/* Decorative Corners */}
        <div className="corner corner-tl"></div>
        <div className="corner corner-tr"></div>
        <div className="corner corner-bl"></div>
        <div className="corner corner-br"></div>

        {/* Border decoration */}
        <div className="certificate-border">
          <div className="certificate-inner-border">

            {/* Top Decorative Element */}
            <div className="decorative-element top">
              <div className="ornament">
                <svg width="80" height="40" viewBox="0 0 80 40">
                  <path d="M 10 20 Q 20 10, 40 20 Q 60 30, 70 20" stroke="#c9a961" fill="none" strokeWidth="2"/>
                  <path d="M 15 25 Q 25 18, 40 25 Q 55 32, 65 25" stroke="#c9a961" fill="none" strokeWidth="1.5"/>
                </svg>
              </div>
            </div>

            {/* Certificate Header */}
            <div className="certificate-header">
              <h1 className="certificate-title">Certificate</h1>
              <div className="title-underline"></div>
              <p className="certificate-subtitle">OF ACHIEVEMENT</p>
            </div>

            {/* Certificate Body */}
            <div className="certificate-body">
              <p className="presentation-text">This Certificate is Proudly Presented to</p>

              <h2 className="recipient-name">{recipientName}</h2>
              <div className="name-underline"></div>

              <p className="award-text">{awardText}</p>

              <p className="description-text">{description}</p>
            </div>

            {/* Certificate Footer */}
            <div className="certificate-footer">
              <div className="signature-section">
                <div className="signature-block">
                  <div className="signature-line"></div>
                  <p className="signature-name">{signature1Name}</p>
                </div>

                <div className="date-seal">
                  <div className="seal">
                    <svg width="100" height="100" viewBox="0 0 100 100">
                      <circle cx="50" cy="50" r="45" fill="none" stroke="#c9a961" strokeWidth="2"/>
                      <circle cx="50" cy="50" r="40" fill="none" stroke="#c9a961" strokeWidth="1"/>
                      <text x="50" y="45" textAnchor="middle" fill="#c9a961" fontSize="14" fontWeight="bold">OFFICIAL</text>
                      <text x="50" y="60" textAnchor="middle" fill="#c9a961" fontSize="12">CERTIFICATE</text>
                    </svg>
                  </div>
                  <p className="date-text">{date}</p>
                </div>

                <div className="signature-block">
                  <div className="signature-line"></div>
                  <p className="signature-name">{signature2Name}</p>
                </div>
              </div>
            </div>

            {/* Bottom Decorative Element */}
            <div className="decorative-element bottom">
              <div className="ornament">
                <svg width="80" height="40" viewBox="0 0 80 40">
                  <path d="M 10 20 Q 20 30, 40 20 Q 60 10, 70 20" stroke="#c9a961" fill="none" strokeWidth="2"/>
                  <path d="M 15 15 Q 25 22, 40 15 Q 55 8, 65 15" stroke="#c9a961" fill="none" strokeWidth="1.5"/>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Certificate;
