import React from 'react';
import { useAuth } from '../../context/AuthContext';
import '../../styles/Dashboard.css';

const UserProfile = ({ stats }) => {
  const { user, logout } = useAuth();

  return (
    <div className="user-profile-section">
      <div className="profile-header">
        {user?.profile_picture && (
          <img
            src={user.profile_picture}
            alt={user.name}
            className="profile-avatar"
          />
        )}
        <div className="profile-info">
          <h2>{user?.name}</h2>
          <p className="profile-email">{user?.email}</p>
        </div>
      </div>

      <div className="profile-stats">
        <div className="stat-card">
          <div className="stat-value">{stats?.total_certificates || 0}</div>
          <div className="stat-label">Total Certificates</div>
        </div>
        <div className="stat-card">
          <div className="stat-value">{user?.total_assessments || 0}</div>
          <div className="stat-label">Assessments Completed</div>
        </div>
      </div>

      <button onClick={logout} className="logout-btn">
        Logout
      </button>
    </div>
  );
};

export default UserProfile;
