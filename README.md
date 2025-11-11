# Interactive Technology Assessment & Certification Platform

A full-stack web application that guides users through a technology assessment, tracks their progress, and awards downloadable certificates with QR code verification and social sharing capabilities.

## 🚀 Features

- **Technology Selection**: Choose between Ruby or PHP assessment tracks
- **Progressive Assessment**: 10-question evaluation with real-time progress tracking
- **OAuth Authentication**: Sign in with Google or Instagram
- **Certificate Generation**: Beautiful, professional certificates with QR codes
- **QR Code Verification**: Public certificate verification via QR scanning
- **User Dashboard**: Manage and download all earned certificates
- **Social Sharing**: Share achievements on Facebook, Twitter, LinkedIn, and WhatsApp
- **Responsive Design**: Works seamlessly on desktop and mobile devices

## 🛠️ Tech Stack

### Frontend
- React 18
- React Router v6
- Axios
- React QR Code
- React Icons
- JS Cookie

### Backend
- PHP 8.0+
- Composer (dependency management)
- PDO (database access)
- JWT authentication
- OAuth 2.0 (Google & Instagram)

### Libraries
- **Firebase PHP-JWT**: JWT token generation/verification
- **League OAuth2 Client**: Google & Instagram OAuth
- **Endroid QR Code**: QR code generation
- **mPDF**: PDF certificate generation
- **Intervention Image**: Image processing
- **Ramsey UUID**: Unique certificate IDs

### Database
- MySQL/MariaDB 5.7+

## 📋 Prerequisites

Before you begin, ensure you have the following installed:

- **PHP 8.0 or higher** - [Download PHP](https://www.php.net/downloads)
- **Composer** - [Install Composer](https://getcomposer.org/download/)
- **MySQL/MariaDB 5.7+** - [Download MySQL](https://dev.mysql.com/downloads/)
- **Node.js 16+ and npm** - [Download Node.js](https://nodejs.org/)
- **Google OAuth Credentials** - [Get from Google Cloud Console](https://console.cloud.google.com/)
- **Instagram App Credentials** (optional) - [Get from Facebook Developers](https://developers.facebook.com/)

## 🔧 Installation & Setup

### Step 1: Clone the Repository

```bash
git clone https://github.com/harryios01/sharable_certificate.git
cd sharable_certificate
```

### Step 2: Backend Setup

#### 2.1 Install PHP Dependencies

```bash
cd backend
composer install
```

#### 2.2 Configure Environment Variables

```bash
# Copy the example environment file
cp .env.example .env

# Open .env in your text editor
nano .env  # or use your preferred editor
```

**Edit the `.env` file with your configuration:**

```env
# Server Configuration
APP_ENV=development
APP_URL=http://localhost:3000
API_URL=http://localhost:8000

# Database Configuration
DB_HOST=localhost
DB_PORT=3306
DB_NAME=assessment_platform
DB_USER=root
DB_PASSWORD=your_mysql_password

# JWT Configuration (Generate random strings for production)
JWT_SECRET=your_super_secret_jwt_key_min_32_chars_change_this_in_production
JWT_EXPIRE=900
JWT_REFRESH_SECRET=your_refresh_token_secret_change_this_in_production
JWT_REFRESH_EXPIRE=604800

# Google OAuth (Get from https://console.cloud.google.com/)
GOOGLE_CLIENT_ID=your_google_client_id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/api/auth/google/callback

# Instagram OAuth (Get from https://developers.facebook.com/)
INSTAGRAM_CLIENT_ID=your_instagram_app_id
INSTAGRAM_CLIENT_SECRET=your_instagram_app_secret
INSTAGRAM_REDIRECT_URI=http://localhost:8000/api/auth/instagram/callback

# File Storage
STORAGE_TYPE=local
STORAGE_PATH=storage

# CORS Settings
CORS_ALLOWED_ORIGINS=http://localhost:3000

# Rate Limiting
RATE_LIMIT_ENABLED=true
RATE_LIMIT_MAX_REQUESTS=100
RATE_LIMIT_WINDOW=3600
```

#### 2.3 Set Up File Permissions

```bash
# Make storage directories writable
chmod -R 755 storage
chmod -R 755 storage/certificates
chmod -R 755 storage/qrcodes
chmod -R 755 storage/uploads
```

### Step 3: Database Setup

#### 3.1 Create Database and Import Schema

```bash
# Login to MySQL (from project root directory)
mysql -u root -p

# Enter your MySQL password when prompted
```

**In the MySQL shell, run:**

```sql
-- Create the database
CREATE DATABASE assessment_platform CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Exit MySQL
exit;
```

#### 3.2 Import Database Schema and Questions

```bash
# From the project root directory
mysql -u root -p assessment_platform < database/schema.sql
mysql -u root -p assessment_platform < database/questions.sql
```

**Or import in one command:**

```bash
mysql -u root -p assessment_platform < database/schema.sql && mysql -u root -p assessment_platform < database/questions.sql
```

**Verify the database:**

```bash
mysql -u root -p

USE assessment_platform;
SHOW TABLES;
SELECT * FROM questions LIMIT 5;
exit;
```

You should see tables: `users`, `assessments`, `assessment_answers`, `certificates`, `questions`, `refresh_tokens`, `rate_limits`

### Step 4: Frontend Setup

```bash
# Return to project root
cd ..

# Install Node.js dependencies
npm install
```

#### 4.1 Configure Frontend Environment

```bash
# Copy the example environment file
cp .env.example .env

# Edit the .env file
nano .env  # or use your preferred editor
```

**Edit the `.env` file:**

```env
REACT_APP_API_URL=http://localhost:8000/api
REACT_APP_BASE_URL=http://localhost:3000
REACT_APP_GOOGLE_CLIENT_ID=your_google_client_id.apps.googleusercontent.com
```

**Note:** Use the same Google Client ID from your backend configuration.

### Step 5: OAuth Configuration

#### 5.1 Google OAuth Setup

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Navigate to **APIs & Services** → **Credentials**
4. Click **Create Credentials** → **OAuth 2.0 Client ID**
5. Configure OAuth consent screen if prompted
6. Set Application type to **Web application**
7. Add **Authorized redirect URIs**:
   - `http://localhost:8000/api/auth/google/callback`
8. Copy the **Client ID** and **Client Secret**
9. Add them to your backend `.env` file

#### 5.2 Instagram OAuth Setup (Optional)

1. Go to [Facebook Developers](https://developers.facebook.com/)
2. Create a new app
3. Add **Instagram Basic Display** product
4. Configure **OAuth Redirect URIs**:
   - `http://localhost:8000/api/auth/instagram/callback`
5. Add test users for development
6. Copy **App ID** and **App Secret**
7. Add them to your backend `.env` file

## 🚀 Running the Application

You need to run **both** the backend and frontend servers simultaneously. Open **two terminal windows**.

### Terminal 1: Start Backend Server

```bash
# Navigate to backend directory
cd backend

# Start PHP development server
php -S localhost:8000 -t public
```

**You should see:**
```
PHP 8.x.x Development Server (http://localhost:8000) started
```

**Backend will be running at:** `http://localhost:8000`

**Keep this terminal running!**

### Terminal 2: Start Frontend Server

```bash
# From project root directory
npm start
```

**You should see:**
```
Compiled successfully!

You can now view tech-assessment-platform in the browser.

  Local:            http://localhost:3000
  On Your Network:  http://192.168.x.x:3000
```

**Frontend will automatically open at:** `http://localhost:3000`

**Keep this terminal running!**

### ✅ Verify Everything is Working

1. **Frontend:** Open http://localhost:3000 - You should see the home page
2. **Backend API:** Open http://localhost:8000/api/verify?certificateId=test - You should see JSON response
3. **Database:** Check that MySQL service is running

## 🎮 Using the Application

### Complete User Flow

1. **Home Page** → Click "Start Assessment"
2. **Technology Selection** → Choose Ruby or PHP
3. **Assessment** → Answer all 10 questions
4. **Login** → Sign in with Google or Instagram (after completing questions)
5. **Certificate** → View your generated certificate with QR code
6. **Download** → Download certificate as PDF
7. **Share** → Share on social media
8. **Dashboard** → View all your certificates

### Testing Without OAuth (Development)

If you haven't set up OAuth yet, you can:
1. Complete the assessment as a guest
2. The system will prompt you to login after question 10
3. Set up Google OAuth to save certificates

## 🐛 Troubleshooting

### Backend Issues

#### "Database connection failed"
```bash
# Check MySQL is running
sudo systemctl status mysql

# Or on macOS
brew services list

# Verify credentials in backend/.env
# Make sure DB_NAME, DB_USER, DB_PASSWORD are correct
```

#### "Class not found" errors
```bash
cd backend
composer dump-autoload
```

#### "Permission denied" on storage
```bash
cd backend
chmod -R 755 storage
```

#### Port 8000 already in use
```bash
# Use a different port
php -S localhost:8080 -t public

# Update API_URL in backend/.env and frontend .env
```

### Frontend Issues

#### "Module not found" errors
```bash
# Reinstall dependencies
rm -rf node_modules package-lock.json
npm install
```

#### CORS errors in browser console
```bash
# Check backend/.env has correct CORS_ALLOWED_ORIGINS
CORS_ALLOWED_ORIGINS=http://localhost:3000

# Restart backend server
```

#### Port 3000 already in use
```bash
# React will prompt to use another port (3001)
# Or specify manually:
PORT=3001 npm start
```

### Database Issues

#### "Table doesn't exist"
```bash
# Re-import schema
mysql -u root -p assessment_platform < database/schema.sql
mysql -u root -p assessment_platform < database/questions.sql
```

#### "Access denied for user"
```bash
# Reset MySQL password or create new user
mysql -u root -p

CREATE USER 'assessment_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON assessment_platform.* TO 'assessment_user'@'localhost';
FLUSH PRIVILEGES;
exit;

# Update DB_USER and DB_PASSWORD in backend/.env
```

### OAuth Issues

#### "Redirect URI mismatch"
- Ensure redirect URI in Google Cloud Console **exactly** matches:
  - `http://localhost:8000/api/auth/google/callback`
- No trailing slashes
- Use `http://` not `https://` for development

#### "Invalid client" error
- Verify GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET in backend/.env
- Check credentials are enabled in Google Cloud Console

## 📁 Project Structure

```
sharable_certificate/
├── backend/                     # PHP Backend
│   ├── config/
│   │   └── Database.php        # Database connection
│   ├── src/
│   │   ├── controllers/        # API Controllers
│   │   │   ├── AuthController.php
│   │   │   ├── AssessmentController.php
│   │   │   ├── CertificateController.php
│   │   │   ├── UserController.php
│   │   │   └── VerificationController.php
│   │   ├── models/             # Database Models
│   │   │   ├── User.php
│   │   │   ├── Assessment.php
│   │   │   ├── Certificate.php
│   │   │   └── Question.php
│   │   ├── middleware/         # Middleware
│   │   │   ├── AuthMiddleware.php
│   │   │   ├── CorsMiddleware.php
│   │   │   └── RateLimitMiddleware.php
│   │   ├── services/           # Services
│   │   │   ├── QRCodeService.php
│   │   │   └── PDFService.php
│   │   ├── routes/
│   │   │   └── api.php         # API Routes
│   │   └── utils/              # Utilities
│   │       ├── JWTHelper.php
│   │       ├── Response.php
│   │       └── UUIDGenerator.php
│   ├── public/
│   │   └── index.php           # Entry Point
│   ├── storage/                # File Storage
│   │   ├── certificates/       # Generated PDFs
│   │   └── qrcodes/           # QR Code images
│   ├── composer.json           # PHP Dependencies
│   └── .env                    # Backend Configuration
├── database/
│   ├── schema.sql              # Database Schema
│   └── questions.sql           # Seed Questions
├── src/                        # React Frontend
│   ├── components/
│   │   ├── Auth/              # Authentication Components
│   │   ├── Assessment/        # Assessment Components
│   │   ├── Certificate/       # Certificate Components
│   │   ├── Dashboard/         # Dashboard Components
│   │   ├── Verification/      # Verification Components
│   │   └── Shared/            # Shared Components
│   ├── pages/                 # Page Components
│   │   ├── HomePage.jsx
│   │   ├── LoginPage.jsx
│   │   ├── AssessmentPage.jsx
│   │   ├── CertificatePage.jsx
│   │   ├── DashboardPage.jsx
│   │   └── VerifyPage.jsx
│   ├── context/               # React Context
│   │   ├── AuthContext.js
│   │   └── AssessmentContext.js
│   ├── services/
│   │   └── api.js            # Axios API Client
│   ├── styles/               # CSS Styles
│   └── App.js                # Main App Component
├── public/
│   └── index.html
├── package.json               # Node Dependencies
├── .env                       # Frontend Configuration
├── README.md                  # This file
└── SETUP_GUIDE.md            # Quick setup guide
```

## 🌐 API Endpoints

### Authentication
- `GET /api/auth/google` - Initiate Google OAuth
- `GET /api/auth/google/callback` - Google OAuth callback
- `GET /api/auth/instagram` - Initiate Instagram OAuth
- `GET /api/auth/instagram/callback` - Instagram OAuth callback
- `POST /api/auth/logout` - Logout user
- `GET /api/auth/me` - Get current user
- `POST /api/auth/refresh` - Refresh JWT token

### Assessment
- `POST /api/assessment/start` - Start new assessment
- `GET /api/assessment/questions?technology=Ruby` - Get questions
- `POST /api/assessment/answer` - Submit answer
- `POST /api/assessment/assign` - Assign assessment to user

### Certificates
- `POST /api/certificate/generate` - Generate certificate
- `GET /api/certificate/download?certificateId=xxx` - Download PDF
- `GET /api/certificate?technology=Ruby` - Get user certificates
- `DELETE /api/certificate?certificateId=xxx` - Delete certificate
- `POST /api/certificate/share?certificateId=xxx` - Track share

### User
- `GET /api/user/profile` - Get user profile
- `PUT /api/user/profile` - Update user profile
- `GET /api/user/certificates` - Get user's certificates

### Verification (Public)
- `GET /api/verify?certificateId=xxx` - Verify certificate

## 🔒 Security Features

- JWT-based authentication with refresh tokens
- HTTPS required for OAuth in production
- Rate limiting on critical endpoints
- CSRF protection via OAuth state parameter
- SQL injection prevention (parameterized queries)
- XSS protection
- Secure cookie settings with httpOnly flag
- Input validation and sanitization

## 🚧 Production Deployment

### Backend Deployment

1. **Set up web server** (Apache/Nginx)
2. **Configure document root** to `/backend/public`
3. **Enable URL rewriting** (.htaccess for Apache)
4. **Set environment** to production in `.env`
5. **Configure HTTPS/SSL**
6. **Update OAuth redirect URIs** to production URLs
7. **Set up production database**
8. **Configure file storage** (AWS S3 recommended)
9. **Generate strong JWT secrets**
10. **Enable error logging**

### Frontend Deployment

```bash
# Build production bundle
npm run build

# Deploy 'build' folder to hosting (Netlify, Vercel, etc.)
```

### Environment Variables for Production

Update all URLs in `.env` files to production domains:
- Remove `localhost` references
- Use HTTPS URLs
- Update OAuth redirect URIs in provider settings

## 📊 Testing

### Manual Testing Checklist

- [ ] User can select Ruby or PHP technology
- [ ] All 10 questions load correctly
- [ ] Progress bar updates with each answer
- [ ] Google OAuth login works
- [ ] Instagram OAuth login works (if configured)
- [ ] Certificate generates after completion
- [ ] QR code displays on certificate
- [ ] Certificate downloads as PDF
- [ ] QR code verification works
- [ ] Social sharing buttons work
- [ ] Dashboard displays all certificates
- [ ] User can logout successfully

## 📝 License

MIT License - feel free to use this in your projects!

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📧 Support

For issues and questions, please open an issue on GitHub.

---

**Built with ❤️ using React and PHP**

## 📚 Additional Resources

- [React Documentation](https://react.dev/)
- [PHP Documentation](https://www.php.net/docs.php)
- [Composer Documentation](https://getcomposer.org/doc/)
- [Google OAuth 2.0 Guide](https://developers.google.com/identity/protocols/oauth2)
- [Instagram Basic Display API](https://developers.facebook.com/docs/instagram-basic-display-api)
