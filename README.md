# TalentAI – Smart Recruitment Platform

## Overview

TalentAI is a web-based recruitment platform developed using PHP and MySQL. The platform connects job seekers with employers through an intelligent skill-matching system that analyzes candidate skills, extracts information from uploaded CVs, and calculates compatibility scores for job opportunities.

## Features

### Authentication & User Management

* Secure user registration and login
* Password hashing and verification
* Role-based access control

  * Job Seekers
  * Employers
  * Administrators

### Job Management

* Create and manage job postings
* Browse available jobs
* Apply for jobs online
* Track applications

### Intelligent Skill Matching

* Automatic skill extraction from CVs
* Weighted skill matching algorithm
* Compatibility score calculation
* Missing skills analysis
* Real-time candidate-job matching

### CV Processing

* Resume upload support
* Skill extraction from uploaded CVs
* Candidate profile enhancement

### Dashboard

* Employer dashboard
* Job seeker dashboard
* Application management interface

## Technologies Used

### Backend

* PHP
* MySQL
* PDO

### Frontend

* HTML5
* CSS3
* JavaScript
* Bootstrap

### Security

* Password Hashing
* Prepared Statements (PDO)
* Session Management

## Smart Matching Algorithm

The platform uses a weighted skill-matching engine that:

1. Extracts skills from candidate profiles and CVs.
2. Compares candidate skills with job requirements.
3. Assigns weights to important skills.
4. Calculates a compatibility percentage.
5. Identifies missing skills and improvement areas.

## Project Structure

```text
TalentAI/
├── admin/
├── employer/
├── jobseeker/
├── assets/
├── includes/
├── ai_matcher.php
├── cv_parser.php
├── realtime_ai.php
├── login.php
├── register.php
└── database/
```

## Installation

1. Clone the repository

```bash
git clone https://github.com/yourusername/TalentAI.git
```

2. Import the database file into MySQL

3. Configure database credentials

```php
$config = [
    'host' => 'localhost',
    'dbname' => 'talentai',
    'user' => 'root',
    'password' => ''
];
```

4. Start Apache and MySQL

5. Open the project in your browser

## Future Enhancements

* AI-powered recommendation engine
* Advanced resume analysis
* Interview scheduling system
* Email notifications
* Machine Learning integration
* Analytics and reporting dashboard

## Author

Sarah Hany

Computer Science Student | PHP Developer | Backend Development Enthusiast
