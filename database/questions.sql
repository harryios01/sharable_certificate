-- Assessment Questions Seed Data
USE assessment_platform;

-- Questions Table
CREATE TABLE IF NOT EXISTS questions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    technology ENUM('Ruby', 'PHP') NOT NULL,
    question_number TINYINT NOT NULL,
    question_text TEXT NOT NULL,
    question_type ENUM('multiple_choice', 'single_choice', 'text') DEFAULT 'single_choice',
    options JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY unique_question (technology, question_number),
    INDEX idx_technology (technology)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Ruby Questions
INSERT INTO questions (technology, question_number, question_text, question_type, options) VALUES
('Ruby', 1, 'What is your primary reason for learning Ruby?', 'single_choice', '["Web Development", "Data Science", "Automation", "Game Development", "Other"]'),
('Ruby', 2, 'How familiar are you with object-oriented programming?', 'single_choice', '["Beginner", "Intermediate", "Advanced", "Expert"]'),
('Ruby', 3, 'Have you worked with Ruby on Rails before?', 'single_choice', '["Yes, extensively", "Yes, a little", "No, but interested", "No"]'),
('Ruby', 4, 'What aspect of Ruby excites you the most?', 'single_choice', '["Syntax simplicity", "Rails framework", "Gem ecosystem", "Metaprogramming", "Testing tools"]'),
('Ruby', 5, 'How do you prefer to learn new programming concepts?', 'single_choice', '["Video tutorials", "Reading documentation", "Building projects", "Interactive courses", "Mentorship"]'),
('Ruby', 6, 'What type of applications do you want to build with Ruby?', 'single_choice', '["Web applications", "APIs", "Command-line tools", "Background jobs", "All of the above"]'),
('Ruby', 7, 'Are you familiar with test-driven development (TDD)?', 'single_choice', '["Yes, I practice it regularly", "Heard of it", "No, but want to learn", "Not interested"]'),
('Ruby', 8, 'What is your experience level with databases?', 'single_choice', '["Beginner", "Intermediate", "Advanced", "Expert"]'),
('Ruby', 9, 'How important is community support to you when learning a language?', 'single_choice', '["Very important", "Somewhat important", "Not very important", "Not important at all"]'),
('Ruby', 10, 'What is your primary goal after completing this assessment?', 'single_choice', '["Start building projects", "Learn Rails framework", "Contribute to open source", "Get a job", "Personal knowledge"]');

-- PHP Questions
INSERT INTO questions (technology, question_number, question_text, question_type, options) VALUES
('PHP', 1, 'What is your primary reason for learning PHP?', 'single_choice', '["Web Development", "CMS Development", "E-commerce", "API Development", "Other"]'),
('PHP', 2, 'How familiar are you with web development concepts?', 'single_choice', '["Beginner", "Intermediate", "Advanced", "Expert"]'),
('PHP', 3, 'Have you worked with any PHP frameworks before?', 'single_choice', '["Laravel", "Symfony", "CodeIgniter", "WordPress", "None"]'),
('PHP', 4, 'What aspect of PHP interests you the most?', 'single_choice', '["Easy deployment", "Large community", "Framework ecosystem", "Legacy systems", "WordPress development"]'),
('PHP', 5, 'How do you prefer to learn new programming concepts?', 'single_choice', '["Video tutorials", "Reading documentation", "Building projects", "Interactive courses", "Mentorship"]'),
('PHP', 6, 'What type of applications do you want to build with PHP?', 'single_choice', '["Dynamic websites", "REST APIs", "WordPress plugins", "E-commerce sites", "All of the above"]'),
('PHP', 7, 'Are you familiar with modern PHP practices (PSR, Composer)?', 'single_choice', '["Yes, very familiar", "Heard of them", "No, but want to learn", "Not interested"]'),
('PHP', 8, 'What is your experience level with MySQL/databases?', 'single_choice', '["Beginner", "Intermediate", "Advanced", "Expert"]'),
('PHP', 9, 'How important is backward compatibility to you?', 'single_choice', '["Very important", "Somewhat important", "Not very important", "Not important at all"]'),
('PHP', 10, 'What is your primary goal after completing this assessment?', 'single_choice', '["Build a complete website", "Learn Laravel", "WordPress development", "Get a job", "Personal knowledge"]');
