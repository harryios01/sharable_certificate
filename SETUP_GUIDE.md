# Setup Guide - Tech Assessment Platform

## Quick Start

### 1. Backend Setup

```bash
cd backend
composer install
cp .env.example .env
# Edit .env with your database and OAuth credentials
```

### 2. Database Setup

```bash
mysql -u root -p
source ../database/schema.sql
source ../database/questions.sql
```

### 3. Frontend Setup

```bash
cd ..
npm install
cp .env.example .env
# Edit .env with your API URL and Google Client ID
```

### 4. Run Application

**Backend (Terminal 1):**
```bash
cd backend
php -S localhost:8000 -t public
```

**Frontend (Terminal 2):**
```bash
npm start
```

Visit http://localhost:3000

## Configuration Details

### Backend .env
```
DB_HOST=localhost
DB_NAME=assessment_platform
DB_USER=root
DB_PASSWORD=your_password

JWT_SECRET=your_secret_here
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_secret
INSTAGRAM_CLIENT_ID=your_instagram_id
INSTAGRAM_CLIENT_SECRET=your_instagram_secret
```

### Frontend .env
```
REACT_APP_API_URL=http://localhost:8000/api
REACT_APP_GOOGLE_CLIENT_ID=your_google_client_id
```

## OAuth Setup

### Google OAuth
1. Go to https://console.cloud.google.com/
2. Create project → APIs & Services → Credentials
3. Create OAuth 2.0 Client ID
4. Add redirect URI: `http://localhost:8000/api/auth/google/callback`

### Instagram OAuth
1. Go to https://developers.facebook.com/
2. Create App → Add Instagram Basic Display
3. Add redirect URI: `http://localhost:8000/api/auth/instagram/callback`
4. Add test users

## File Permissions

```bash
chmod -R 755 backend/storage
chmod -R 755 backend/storage/certificates
chmod -R 755 backend/storage/qrcodes
```

## Troubleshooting

**Database connection failed:**
- Check MySQL is running: `sudo systemctl status mysql`
- Verify credentials in backend/.env

**CORS errors:**
- Ensure CORS_ALLOWED_ORIGINS includes frontend URL
- Check both servers are running

**OAuth redirect errors:**
- Verify redirect URIs match exactly
- Use http:// for development (not https://)

## Production Deployment

See main README.md for production deployment instructions.
