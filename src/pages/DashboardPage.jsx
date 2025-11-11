import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import UserProfile from '../components/Dashboard/UserProfile';
import CertificateGallery from '../components/Dashboard/CertificateGallery';
import api from '../services/api';
import '../styles/Dashboard.css';

const DashboardPage = () => {
  const navigate = useNavigate();
  const [certificates, setCertificates] = useState([]);
  const [stats, setStats] = useState(null);
  const [loading, setLoading] = useState(true);
  const [filter, setFilter] = useState('all');

  useEffect(() => {
    loadDashboardData();
  }, []);

  const loadDashboardData = async () => {
    try {
      const [profileResponse, certificatesResponse] = await Promise.all([
        api.get('/user/profile'),
        api.get('/user/certificates')
      ]);

      setStats(profileResponse.data.data);
      setCertificates(certificatesResponse.data.data.certificates || []);
    } catch (error) {
      console.error('Failed to load dashboard data:', error);
    } finally {
      setLoading(false);
    }
  };

  const filteredCertificates = filter === 'all'
    ? certificates
    : certificates.filter(cert => cert.technology === filter);

  if (loading) {
    return <div className="loading">Loading dashboard...</div>;
  }

  return (
    <div className="dashboard-page">
      <div className="dashboard-header">
        <h1>My Dashboard</h1>
        <button
          className="new-assessment-btn"
          onClick={() => navigate('/select-technology')}
        >
          + New Assessment
        </button>
      </div>

      <div className="dashboard-content">
        <aside className="dashboard-sidebar">
          <UserProfile stats={stats} />
        </aside>

        <main className="dashboard-main">
          <div className="certificates-header">
            <h2>My Certificates</h2>
            <div className="filter-buttons">
              <button
                className={filter === 'all' ? 'active' : ''}
                onClick={() => setFilter('all')}
              >
                All
              </button>
              <button
                className={filter === 'Ruby' ? 'active' : ''}
                onClick={() => setFilter('Ruby')}
              >
                Ruby
              </button>
              <button
                className={filter === 'PHP' ? 'active' : ''}
                onClick={() => setFilter('PHP')}
              >
                PHP
              </button>
            </div>
          </div>

          <CertificateGallery certificates={filteredCertificates} />
        </main>
      </div>
    </div>
  );
};

export default DashboardPage;
