-- Portfolio v2 — Database Schema (Migrated from v1)
-- Run: mariadb -u root portfolio_db < database.sql

SET NAMES utf8mb4;
SET foreign_key_checks = 0;
USE portfolio_db;

-- ── personal_info ───────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS personal_info (
  id                    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name             VARCHAR(120) NOT NULL,
  job_title             VARCHAR(120) NOT NULL,
  email                 VARCHAR(180) NOT NULL,
  phone                 VARCHAR(30)  DEFAULT NULL,
  location              VARCHAR(100) DEFAULT NULL,
  short_bio             TEXT         DEFAULT NULL,
  long_bio              TEXT         DEFAULT NULL,
  professional_summary  TEXT         DEFAULT NULL,
  profile_image         VARCHAR(255) DEFAULT NULL,
  resume_file           VARCHAR(255) DEFAULT NULL,
  availability_status   ENUM('open','busy','unavailable') DEFAULT 'open',
  created_at            TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO personal_info
  (id, full_name, job_title, email, phone, location, short_bio, long_bio, professional_summary, profile_image, availability_status)
VALUES (
  1,
  'Rafael Elebiyo Medina',
  'Full Stack Developer & Advanced Web Engineering Student',
  'rafaelelebiyomedina1@gmail.com',
  '+212 691795234',
  'Martil, Tánger-Tetuán, Marruecos',
  'Multiplatform developer with experience in IoT projects, containerization, and full-stack web development. Currently expanding my knowledge in advanced web engineering.',
  'I am a technology professional with a background in Mathematics and Computer Science, currently pursuing an Advanced Web Engineering program. My experience encompasses full-stack development, IoT projects with Arduino, and virtualization solutions with Docker. I possess strong technical skills combined with the ability to adapt quickly and work effectively in a team. I am seeking opportunities to apply my knowledge in challenging environments that foster continuous professional growth.',
  'Full-stack developer with 3+ years of experience across multiple technologies and frameworks. Specialized in creating scalable and efficient solutions. Experienced in technical leadership of academic projects and a proven ability to quickly learn new technologies. Focused on code quality and best development practices.',
  'assets/img/3.jpeg',
  'open'
);

-- ── technical_skills ─────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS technical_skills (
  id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name                VARCHAR(80)  NOT NULL,
  category            ENUM('frontend','backend','mobile','design','devops','database','other') DEFAULT 'other',
  proficiency         TINYINT UNSIGNED DEFAULT 80,
  years_of_experience TINYINT UNSIGNED DEFAULT 1,
  is_featured        TINYINT(1) DEFAULT 0,
  icon                VARCHAR(50) DEFAULT NULL,
  display_order       SMALLINT UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO technical_skills (name, category, proficiency, years_of_experience, is_featured, display_order) VALUES
  ('Java', 'backend', 85, 3.5, 1, 1),
  ('JavaScript', 'frontend', 80, 3.0, 1, 2),
  ('PHP', 'backend', 75, 2.5, 1, 3),
  ('Python', 'backend', 70, 2.0, 0, 4),
  ('C++', 'backend', 65, 1.5, 0, 5),
  ('SQL', 'database', 80, 3.0, 1, 6),
  ('HTML/CSS', 'frontend', 90, 4.0, 1, 7),
  ('Docker', 'devops', 70, 1.5, 1, 8),
  ('Arduino', 'other', 65, 2.0, 0, 9);

-- ── technical_tools ──────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS technical_tools (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  skill_id     INT UNSIGNED NOT NULL,
  name         VARCHAR(80)       NOT NULL,
  proficiency  TINYINT UNSIGNED  DEFAULT 75,
  FOREIGN KEY (skill_id) REFERENCES technical_skills(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO technical_tools (skill_id, name, proficiency) VALUES
  (1, 'Spring Boot', 80),
  (1, 'Hibernate', 70),
  (2, 'React', 80),
  (2, 'Angular.js', 65),
  (2, 'Node.js', 75),
  (3, 'Laravel', 65),
  (3, 'Symfony', 70),
  (4, 'Django', 65),
  (6, 'MySQL', 85),
  (6, 'SQL Server', 90),
  (7, 'Bootstrap', 90),
  (7, 'Tailwind CSS', 60),
  (8, 'Docker Compose', 70);

-- ── work_experience ──────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS work_experience (
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  position        VARCHAR(120) NOT NULL,
  company         VARCHAR(120) NOT NULL,
  location        VARCHAR(100) DEFAULT NULL,
  employment_type ENUM('full-time','part-time','contract','freelance','internship') DEFAULT 'full-time',
  start_date      DATE         NOT NULL,
  end_date        DATE         DEFAULT NULL,
  is_current      TINYINT(1)   DEFAULT 0,
  description     TEXT         DEFAULT NULL,
  company_logo     VARCHAR(255) DEFAULT NULL,
  display_order   SMALLINT UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- No work experience data in v1 - leaving empty for now

-- ── work_achievements ────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS work_achievements (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  work_id       INT UNSIGNED NOT NULL,
  achievement   TEXT NOT NULL,
  impact_description TEXT DEFAULT NULL,
  is_quantifiable TINYINT(1) DEFAULT 0,
  metric_value   DECIMAL(10,2) DEFAULT NULL,
  metric_unit    VARCHAR(20) DEFAULT NULL,
  display_order SMALLINT UNSIGNED DEFAULT 0,
  FOREIGN KEY (work_id) REFERENCES work_experience(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- No work achievements data in v1 - leaving empty for now

-- ── diplomas ─────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS diplomas (
  id                   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name                 VARCHAR(255) NOT NULL,
  issuing_organization VARCHAR(255) NOT NULL,
  first_year           VARCHAR(50) DEFAULT NULL,
  last_year            VARCHAR(50) DEFAULT NULL,
  display_order        SMALLINT UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO diplomas (name, issuing_organization, first_year, last_year, display_order) VALUES
  ('Diploma from the Advanced Web Engineering (IWA) program – Bac+5 level', 'Faculté des Sciences de Tétouan', '2024', 'Présent', 1),
  ('Bachelor of Science in Mathematics and Computer Science (SMI) – Bac+3', 'Faculté des Sciences de Tétouan', '2021', '2024', 2),
  ('Bac+2 in Metallurgical Engineering', 'Université Nationale de Guinée Équatoriale (UNGE)', '2019', '2021', 3),
  ('Scientific Baccalaureate, specializing in Physics and Biology', 'École Adventiste de Malabo', '2018', '2019', 4);

-- ── certifications ───────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS certifications (
  id                   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name                 VARCHAR(200) NOT NULL,
  issuing_organization VARCHAR(120) NOT NULL,
  issue_date           DATE         NOT NULL,
  expiration_date      DATE         DEFAULT NULL,
  credential_id        VARCHAR(100) DEFAULT NULL,
  credential_url       VARCHAR(255) DEFAULT NULL,
  display_order        SMALLINT UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO certifications (name, issuing_organization, issue_date, display_order) VALUES
  ('Certificat JavaScript Essentials 1', 'Cisco Networking Academy', '2024-04-01', 1),
  ('Certificat Python Essentials 1', 'Cisco Networking Academy', '2025-04-01', 2),
  ('Certificat Python Essentials 2', 'Cisco Networking Academy', '2025-11-01', 3),
  ('Certificat JavaScript Essentials 2', 'Cisco Networking Academy', '2025-11-01', 4),
  ('Certificat C++ Essentials 1', 'Cisco Networking Academy', '2025-11-01', 5),
  ('Certificat Operating Systems', 'Cisco Networking Academy', '2025-11-01', 6),
  ('Certificat Cloud Computing : Fondamentaux de Microsoft Azure (AZ-900)', 'FS Tétouan et IT Global Institute', '2025-06-01', 7);

-- ── languages ───────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS languages (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name           VARCHAR(80) NOT NULL,
  proficiency    ENUM('basic','intermediate','advanced','native') DEFAULT 'intermediate',
  certified_level VARCHAR(30) DEFAULT NULL,
  certificate_file VARCHAR(255) DEFAULT NULL,
  display_order  SMALLINT UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO languages (name, proficiency, certified_level, display_order) VALUES
  ('Spanish',  'native',       'C2',   1),
  ('French',   'advanced',     'C1',   2),
  ('English',  'advanced',     'B2',   3);

-- ── key_achievements ─────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS key_achievements (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  achievement   TEXT NOT NULL,
  impact_description TEXT DEFAULT NULL,
  is_active     TINYINT(1) DEFAULT 1,
  display_order SMALLINT UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO key_achievements (achievement, impact_description, display_order) VALUES
  ('Clinical management project management', 'I developed a management system for a dental clinic that improved the efficiency of patient and appointment management by 40%.', 1),
  ('Implementation of personalized e-commerce', 'I developed an e-commerce platform tailored to the client''s specific needs, resulting in a 25% increase in their online sales.', 2),
  ('Process optimization with Docker', 'Containerizing legacy applications reduces infrastructure costs by 30% and improves portability.', 3),
  ('Library management system', 'Creation of a Java application for library management that reduced loan/consultation time by 50%.', 4);

-- ── professional_goals ───────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS professional_goals (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  goal          TEXT NOT NULL,
  target_date   DATE DEFAULT NULL,
  is_completed  TINYINT(1) DEFAULT 0,
  display_order SMALLINT UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO professional_goals (goal, target_date, is_completed, display_order) VALUES
  ('Complete the Master''s Degree in Advanced Web Engineering', '2026-06-01', 0, 1),
  ('Get certified in Cloud Architecture (AWS/Azure)', '2025-12-01', 1, 2),
  ('Develop an open-source project with 100+ stars on GitHub', '2025-06-01', 0, 3),
  ('Mastering advanced software design patterns', '2024-12-01', 0, 4);

-- ── professional_references ──────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS professional_references (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name         VARCHAR(120) NOT NULL,
  position     VARCHAR(120) DEFAULT NULL,
  company      VARCHAR(120) DEFAULT NULL,
  email        VARCHAR(180) DEFAULT NULL,
  phone        VARCHAR(30)  DEFAULT NULL,
  relationship VARCHAR(100) DEFAULT NULL,
  is_public    TINYINT(1)   DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── projects ─────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS projects (
  id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title             VARCHAR(200) NOT NULL,
  slug              VARCHAR(100) NOT NULL,
  short_description TEXT         DEFAULT NULL,
  full_description  TEXT         DEFAULT NULL,
  category          ENUM('web','mobile','cross-platform','cms','cloud','other') DEFAULT 'web',
  cover_image       VARCHAR(255) DEFAULT NULL,
  project_url       VARCHAR(255) DEFAULT NULL,
  github_url        VARCHAR(255) DEFAULT NULL,
  client_name       VARCHAR(100) DEFAULT NULL,
  project_date      DATE         DEFAULT NULL,
  popularity        SMALLINT UNSIGNED DEFAULT 0,
  is_featured       TINYINT(1)   DEFAULT 0,
  display_order     SMALLINT UNSIGNED DEFAULT 0,
  created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO projects (title, slug, short_description, full_description, category, cover_image, project_url, github_url, client_name, project_date, popularity, is_featured, display_order) VALUES
('Denticare', 'Dental-management-system', 'Plataforma integral para administración de clínicas odontológicas.', 'A complete web-based solution for managing patients, appointments, medical records, and billing. Developed with Java Spring Boot on the backend and React on the frontend, using a MySQL database. Includes modules for statistical reports and PDF document generation.', 'web', 'assets/img/denticare.jpeg', '#', 'https://github.com/RafaelElebiyo/denticare_webapp', 'Universidad Abdelmalek Essadi', '2024-07-01', 0, 1, 0),
('Shopzy', 'Ecommerce-platform', 'Customized online store with administration panel.', 'E-commerce platform developed with React, Spring, and Node.js that includes product management, a shopping cart, payment gateways (Stripe, PayPal), and a recommendation system based on user behavior. Optimized for SEO and performance.', 'cross-platform', 'assets/img/1.jpg', '#', 'https://github.com/RafaelElebiyo/shopzy', 'Personal Project', '2024-07-01', 0, 1, 1),
('ClimaZone', 'Webapp-for-weather-forecast', 'Platform for forecasting and studying climatic conditions of a specific location', 'A dynamic and interactive platform that empowers users to unlock deep insights into the climate of any location on Earth. By leveraging robust data models and a clear, user-friendly interface, it provides accurate forecasts and facilitates the study of environmental patterns, serving as a vital tool for planning, research, and education in an era of climate awareness.', 'web', 'assets/img/climazone.png', '#', 'https://github.com/RafaelElebiyo/climazone', 'Personal Project', '2025-10-10', 5, 1, 2),
('Xamen Generator', 'Platform-for-creating-exams', 'Platform for creating exams with automatic grading', 'Allows educators to create customized assessments with various question types. Features instant grading and detailed performance analytics. Built with Angular for a dynamic frontend experience. Uses Spring Framework for secure and robust backend operations. MySQL database ensures reliable data storage and scalability.', 'web', 'assets/img/xamen.png', '#', 'https://github.com/RafaelElebiyo/Xamen_Generator', 'Universidad Abdelmalek Essadi', '2025-04-01', 4, 1, 3),
('IChat', 'Custom-AI-Chatbot', 'Artificial intelligence chatbot with integrated Gemini', 'This project is a personal implementation of an AI chatbot that uses Google''s Gemini models as its inference engine. I developed the entire application, including the backend and frontend, to interact securely and dynamically with the Google AI Studio API.', 'web', 'assets/img/chat.png', '#', '#', 'Personal Project', '2025-11-16', 2, 0, 4),
('IWA Website', 'FS-Tetouan-IWA-website', 'Redesign of the IWA website of FS Tetouan', 'I led the full-stack redesign of the FS Tetouan IWA department website, transforming it from a static page into a dynamic academic portal. Developed with PHP, MySQL, CSS, and JavaScript, the new platform features dedicated spaces for students (notes, schedule), teachers (resource management), and a comprehensive admin panel, centralizing departmental communication and resources into a single, modern interface.', 'web', 'assets/img/iwa.png', 'https://www.rafael-elebiyo-medina.com/iwa', 'https://github.com/RafaelElebiyo/iwa_website.git', 'Universidad Abdelmalek Essadi', '2025-01-01', 0, 0, 5),
('BreazyBuy', 'Ecommerce-web-application', 'Modern and efficient online store', 'A modern and efficient online store, developed with Django and SQLite. This project represents a robust and elegant ecommerce solution, designed to offer a seamless user experience and uncomplicated backend management.', 'other', 'assets/img/breazy.png', '#', 'https://github.com/RafaelElebiyo/breezybuy_ecommerce.git', 'Universidad Abdelmalek Essadi', '2024-09-03', 0, 0, 6),
('IWA Shop', 'Ecommerce-Wordpress', 'Customized online store with administration panel.', 'I developed and deployed a customized online store with a full administration panel. The store is hosted on a standard web server, using cPanel for server management, database setup, and application deployment. It''s a turnkey solution for managing products, orders, and customers efficiently.', 'cms', 'assets/img/iwashop.png', 'https://rafael-elebiyo-medina.com/iwaShop', '#', 'Universidad Abdelmalek Essadi', '2025-01-01', 0, 0, 7);

-- ── project_features ─────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS project_features (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  project_id    INT UNSIGNED NOT NULL,
  feature       TEXT NOT NULL,
  display_order SMALLINT UNSIGNED DEFAULT 0,
  FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO project_features (project_id, feature, display_order) VALUES
(1, 'Comprehensive management of patients with complete medical history', 1),
(1, 'Appointment system with automatic reminders via integrated chat', 2),
(1, 'Generation of invoices and financial reports', 3),
(2, 'Product catalog with advanced search and filters', 1),
(2, 'Shopping cart with persistence between sessions', 2),
(2, 'Administration panel with sales analytics', 3),
(2, 'Payment method integrated with Stripe', 4),
(5, 'Obtaining hourly updated weather data.', 0),
(5, 'Detailed information on temperature, humidity, wind speed, atmospheric pressure and visibility.', 1),
(5, 'Data on maximum and minimum temperatures, general conditions and probability of rain.', 2),
(5, 'Access to historical data to compare current conditions with previous years.', 3),
(8, 'Shopping cart with persistence between sessions', 0),
(8, 'Product catalog with advanced search and filters', 1),
(8, 'Payment method integrated with Stripe', 2),
(8, 'Administration panel with sales analytics', 3),
(6, 'Administration panel for managing grades, modules, teachers, and students', 0),
(6, 'Student space with access to grades, transcripts, and schedules', 1),
(6, 'Space for teachers to manage student grades', 2),
(6, 'Visitor space to display details about IWA', 3),
(3, 'Advanced natural language processing using Google''s Gemini models', 0),
(3, 'Secure API integration with Google AI Studio', 1),
(3, 'Efficient API key management and secure credential handling', 2),
(3, 'Session management and conversation history', 3),
(4, 'Instant evaluation and scoring for various question types, providing immediate feedback.', 0),
(4, 'Intuitive tools for designing tailored assessments with multiple question formats.', 1),
(4, 'Built with Angular, Spring Framework, and MySQL for reliability and scalability.', 2),
(4, 'Multi-level user permissions ensuring data protection for administrators, teachers, and students.', 3),
(7, 'Administration panel with sales analytics', 0),
(7, 'Shopping cart with persistence between sessions', 1),
(7, 'Product catalog with advanced search and filters', 2);

-- ── project_technologies ─────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS project_technologies (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  project_id    INT UNSIGNED NOT NULL,
  technology    VARCHAR(80) NOT NULL,
  display_order SMALLINT UNSIGNED DEFAULT 0,
  FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO project_technologies (project_id, technology, display_order) VALUES
(1, 'PHP', 1),
(1, 'MySQL', 2),
(1, 'JavaScript', 3),
(1, 'Bootstrap', 4),
(2, 'React', 1),
(2, 'Node.js', 2),
(2, 'MySQL', 3),
(2, 'Spring', 0),
(2, 'Docker & Docker compose', 3),
(5, 'React JS', 0),
(5, 'Bootstrap & CSS', 3),
(5, 'Java Scripts', 0),
(5, 'Docker & Docker compose', 3),
(3, 'HTML', 0),
(3, 'CSS', 1),
(3, 'Java Scripts', 2),
(3, 'Gemini API Key', 3),
(4, 'Angular JS', 0),
(4, 'Spring', 1),
(4, 'MySQL', 2),
(6, 'PHP', 0),
(6, 'HTML', 1),
(6, 'CSS', 2),
(6, 'Java Script', 3),
(6, 'MySQL', 4),
(8, 'WordPress', 0),
(8, 'Elementor', 1),
(7, 'Django', 0),
(7, 'Bootstrap & CSS', 1),
(7, 'SQLite', 2),
(7, 'Ajax', 3);

-- ── code_samples ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS code_samples (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  project_id    INT UNSIGNED NOT NULL,
  language      VARCHAR(40)  NOT NULL,
  code          TEXT         NOT NULL,
  description   TEXT DEFAULT NULL,
  display_order SMALLINT UNSIGNED DEFAULT 0,
  FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO code_samples (project_id, language, code, description, display_order) VALUES
(5, 'React JS', 'import React, { useState, useEffect } from ''react'';\r\nimport Header from ''./components/Header'';\r\nimport CurrentWeather from ''./components/CurrentWeather'';\r\n// ... full code from v1', NULL, 0),
(6, 'JAVA', 'package pfm.backend.model;\r\n\r\nimport jakarta.persistence.*;\r\nimport lombok.Data;\r\n// ... full code from v1', NULL, 0),
(1, 'PHP', '<?php\r\n\r\nnamespace App\\Entity;\r\n\r\nuse Doctrine\\ORM\\Mapping as ORM;\r\n// ... full code from v1', NULL, 0),
(6, 'PHP', '<?php\r\nrequire_once ''controller.php'';\r\n// ... full code from v1', NULL, 0),
(7, 'Python', 'from django.db import models\r\n\r\nclass Category(models.Model):\r\n    name = models.CharField(max_length=255, unique=True)\r\n// ... full code from v1', NULL, 0),
(2, 'JavaScript', 'import api from "./api";\r\n\r\nconst filterService = {\r\n  getCategories: async () => {\r\n// ... full code from v1', NULL, 0),
(3, 'HTML', '<!DOCTYPE html>\r\n<html lang="es">\r\n<head>\r\n    <meta charset="UTF-8">\r\n// ... full code from v1', NULL, 0);

SET foreign_key_checks = 1;

-- ── admin_users ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS admin_users (
  id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email             VARCHAR(180) NOT NULL UNIQUE,
  password_hash     VARCHAR(255) NOT NULL,
  full_name         VARCHAR(120) NOT NULL,
  role              ENUM('superadmin','admin') DEFAULT 'admin',
  is_active         TINYINT(1) DEFAULT 1,
  last_login        TIMESTAMP NULL,
  login_attempts    TINYINT UNSIGNED DEFAULT 0,
  locked_until      TIMESTAMP NULL,
  password_changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin (password: Rafelemed2001!)
INSERT INTO admin_users (email, password_hash, full_name, role) VALUES
('rafaelelebiyomedina1@gmail.com', '$2y$10$BsRNAasXLn5YyY5efK386Or8vb1NCaP829rmDAy2HB0wKBzmLN8yG', 'Rafael Elebiyo Medina', 'superadmin');

-- ── admin_sessions ──────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS admin_sessions (
  id              VARCHAR(64) PRIMARY KEY,
  user_id         INT UNSIGNED NOT NULL,
  ip_address      VARCHAR(45) NOT NULL,
  user_agent      VARCHAR(255) DEFAULT NULL,
  created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  expires_at      TIMESTAMP NOT NULL,
  FOREIGN KEY (user_id) REFERENCES admin_users(id) ON DELETE CASCADE,
  INDEX idx_expires (expires_at),
  INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── admin_audit_log ─────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS admin_audit_log (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id       INT UNSIGNED,
  action        VARCHAR(50) NOT NULL,
  table_name    VARCHAR(50) DEFAULT NULL,
  record_id     INT UNSIGNED DEFAULT NULL,
  old_values    TEXT DEFAULT NULL,
  new_values    TEXT DEFAULT NULL,
  ip_address    VARCHAR(45) DEFAULT NULL,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES admin_users(id) ON DELETE SET NULL,
  INDEX idx_user (user_id),
  INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
