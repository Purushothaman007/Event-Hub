🎓 EventHub – College Event Management System

EventHub is a modern web-based platform built with PHP and MySQL to streamline college event management. It offers a seamless experience for both students and administrators—from event creation to registration and analytics.

✨ Features
For Students

Modern Homepage with upcoming event highlights

Event Discovery: Browse by posters, categories & details

1-Click Registration with email confirmation (via PHPMailer)

Dashboard: View & manage your registered events

Past Events Archive

Profile Editing

Secure Logout with confirmation modal

For Admins

Admin Dashboard: Full control over the system

Event Management (CRUD) + Poster uploads

User Management: View/delete student accounts

Analytics: View total users, events, and registration stats

Secure Admin Login

🚀 Getting Started
Requirements

Local server with Apache, MySQL, PHP (Use XAMPP, WAMP, or MAMP)

1. Database Setup

Start Apache & MySQL → Go to http://localhost/phpmyadmin

Create DB: college_events → Import db_schema.sql

2. Project Setup

Place files in htdocs/your-project-name

Edit db_config.php:

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Add your DB password if set
define('DB_NAME', 'college_events');

3. (Optional) Email Setup via Mailtrap

Sign up at mailtrap.io

Paste SMTP credentials into mailer_config.php

🔐 Access

Admin Login
Email: admin@college.edu
Password: admin

Student Login
Any email + password → Auto account creation
