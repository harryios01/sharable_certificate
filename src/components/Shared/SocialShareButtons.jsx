import React from 'react';
import { FaFacebook, FaTwitter, FaLinkedin, FaWhatsapp } from 'react-icons/fa';
import api from '../../services/api';
import '../../styles/SocialShare.css';

const SocialShareButtons = ({ certificate }) => {
  const { technology, verification_url, certificate_id } = certificate;

  const shareText = `I just completed the ${technology} Technology Assessment! 🎓 Verify my certificate: ${verification_url}. Join me and earn your certificate too!`;
  const appUrl = process.env.REACT_APP_BASE_URL || 'http://localhost:3000';

  const handleShare = async (platform) => {
    // Track share
    try {
      await api.post(`/certificate/share?certificateId=${certificate_id}`);
    } catch (error) {
      console.error('Failed to track share:', error);
    }

    let shareUrl = '';

    switch (platform) {
      case 'facebook':
        shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(verification_url)}&quote=${encodeURIComponent(shareText)}`;
        break;
      case 'twitter':
        shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(shareText)}`;
        break;
      case 'linkedin':
        shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(verification_url)}`;
        break;
      case 'whatsapp':
        shareUrl = `https://wa.me/?text=${encodeURIComponent(shareText)}`;
        break;
      default:
        return;
    }

    window.open(shareUrl, '_blank', 'width=600,height=400');
  };

  return (
    <div className="social-share-container">
      <h3>Share Your Achievement</h3>
      <div className="social-buttons">
        <button
          className="social-btn facebook-btn"
          onClick={() => handleShare('facebook')}
          title="Share on Facebook"
        >
          <FaFacebook />
        </button>
        <button
          className="social-btn twitter-btn"
          onClick={() => handleShare('twitter')}
          title="Share on Twitter"
        >
          <FaTwitter />
        </button>
        <button
          className="social-btn linkedin-btn"
          onClick={() => handleShare('linkedin')}
          title="Share on LinkedIn"
        >
          <FaLinkedin />
        </button>
        <button
          className="social-btn whatsapp-btn"
          onClick={() => handleShare('whatsapp')}
          title="Share on WhatsApp"
        >
          <FaWhatsapp />
        </button>
      </div>
    </div>
  );
};

export default SocialShareButtons;
