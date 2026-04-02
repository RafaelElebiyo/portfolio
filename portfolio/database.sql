-- Portfolio v2 — Database Schema
-- Run: mysql -u root portfolio_db < database.sql

SET NAMES utf8mb4;
SET foreign_key_checks = 0;

CREATE DATABASE IF NOT EXISTS portfolio_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
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
  availability_status   ENUM('open','busy','unavailable') DEFAULT 'open',
  created_at            TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO personal_info
  (full_name, job_title, email, phone, location, short_bio, long_bio, professional_summary, profile_image, availability_status)
VALUES (
  'Rafael Elebiyo Medina',
  'FullStack Developer',
  'rafaelelebiyomedina1@gmail.com',
  '+34 600 000 000',
  'Madrid, Spain',
  'Specialized in web & mobile development for iOS and Android.',
  'Passionate fullstack developer with 5+ years of experience building scalable web and mobile applications. I enjoy turning complex problems into simple, beautiful solutions.',
  'FullStack Developer specialized in web and mobile solutions with focus on quality and user experience.',
  'assets/img/profile.jpg',
  'open'
);

-- ── technical_skills ─────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS technical_skills (
  id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name                VARCHAR(80)  NOT NULL,
  category            ENUM('frontend','backend','mobile','design','devops','database','other') DEFAULT 'other',
  proficiency         TINYINT UNSIGNED DEFAULT 80,
  years_of_experience TINYINT UNSIGNED DEFAULT 1,
  display_order       SMALLINT UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO technical_skills (name, category, proficiency, years_of_experience, display_order) VALUES
  ('React / Next.js', 'frontend', 92, 4, 1),
  ('TypeScript',      'frontend', 88, 3, 2),
  ('Tailwind CSS',    'frontend', 90, 3, 3),
  ('PHP 8',           'backend',  87, 5, 4),
  ('MySQL / MariaDB', 'database', 85, 5, 5),
  ('Node.js',         'backend',  80, 3, 6),
  ('Docker',          'devops',   75, 2, 7),
  ('React Native',    'mobile',   78, 2, 8);

-- ── technical_tools ──────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS technical_tools (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  skill_id     INT UNSIGNED NOT NULL,
  name         VARCHAR(80)       NOT NULL,
  proficiency  TINYINT UNSIGNED  DEFAULT 75,
  FOREIGN KEY (skill_id) REFERENCES technical_skills(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  display_order   SMALLINT UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO work_experience
  (position, company, location, employment_type, start_date, is_current, description, display_order)
VALUES
  ('FullStack Developer','Tech Company XYZ','Madrid, Spain','full-time','2022-01-01',1,'Development and maintenance of web and mobile applications using modern technologies.',1),
  ('Mobile Developer','Startup ABC','Remote','contract','2020-06-01','2021-12-31','Native iOS and Android development with Swift and Kotlin.',2),
  ('Junior Web Developer','Agency 123','Madrid, Spain','full-time','2019-01-01','2020-05-31','User interface implementation and frontend development.',3);

-- ── work_achievements ────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS work_achievements (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  work_id       INT UNSIGNED NOT NULL,
  achievement   TEXT NOT NULL,
  display_order SMALLINT UNSIGNED DEFAULT 0,
  FOREIGN KEY (work_id) REFERENCES work_experience(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO work_achievements (work_id, achievement, display_order) VALUES
  (1,'Reduced page load time by 40% through bundle optimization and lazy loading.',1),
  (1,'Led migration from legacy PHP to a modern React + REST API architecture.',2),
  (2,'Shipped iOS and Android apps with 4.8-star average rating.',1),
  (3,'Built 15+ client landing pages with >90 PageSpeed scores.',1);

-- ── certifications ───────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS certifications (
  id                   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name                 VARCHAR(200) NOT NULL,
  issuing_organization VARCHAR(120) NOT NULL,
  issue_date           DATE         NOT NULL,
  credential_id        VARCHAR(100) DEFAULT NULL,
  display_order        SMALLINT UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO certifications (name, issuing_organization, issue_date, credential_id, display_order) VALUES
  ('Bachelor in Computer Engineering','Universidad Complutense de Madrid','2019-06-01',NULL,1),
  ('AWS Certified Cloud Practitioner','Amazon Web Services','2023-03-01','AWS-12345',2),
  ('Meta React Developer Certificate','Meta / Coursera','2022-10-01','META-67890',3);

-- ── languages ────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS languages (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name           VARCHAR(80) NOT NULL,
  proficiency    ENUM('basic','intermediate','advanced','native') DEFAULT 'intermediate',
  certified_level VARCHAR(30) DEFAULT NULL,
  display_order  SMALLINT UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO languages (name, proficiency, certified_level, display_order) VALUES
  ('Spanish',  'native',       NULL,   1),
  ('English',  'advanced',     'B2',   2),
  ('French',   'intermediate', NULL,   3);

-- ── key_achievements ─────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS key_achievements (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  achievement   TEXT NOT NULL,
  is_active     TINYINT(1) DEFAULT 1,
  display_order SMALLINT UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO key_achievements (achievement, display_order) VALUES
  ('Delivered 20+ production applications with zero critical downtime.',1),
  ('Reduced infrastructure costs 35% by migrating to containerised deployments.',2),
  ('Mentored 3 junior developers who are now mid-level engineers.',3);

-- ── professional_goals ───────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS professional_goals (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  goal          TEXT NOT NULL,
  is_completed  TINYINT(1) DEFAULT 0,
  display_order SMALLINT UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO professional_goals (goal, display_order) VALUES
  ('Obtain AWS Solutions Architect certification by end of year.',1),
  ('Contribute to 3 open-source projects in the next 6 months.',2),
  ('Lead a cross-functional team of 5+ engineers on a major product.',3);

-- ── professional_references ──────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS professional_references (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name         VARCHAR(120) NOT NULL,
  position     VARCHAR(120) NOT NULL,
  company      VARCHAR(120) NOT NULL,
  email        VARCHAR(180) NOT NULL,
  phone        VARCHAR(30)  DEFAULT NULL,
  relationship VARCHAR(100) DEFAULT NULL,
  is_public    TINYINT(1)   DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── projects ─────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS projects (
  id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title             VARCHAR(200) NOT NULL,
  short_description TEXT         DEFAULT NULL,
  full_description  TEXT         DEFAULT NULL,
  category          ENUM('web','mobile','cross-platform','cms','cloud') DEFAULT 'web',
  cover_image       VARCHAR(255) DEFAULT NULL,
  project_url       VARCHAR(255) DEFAULT NULL,
  github_url        VARCHAR(255) DEFAULT NULL,
  project_date      DATE         DEFAULT NULL,
  popularity        SMALLINT UNSIGNED DEFAULT 0,
  is_featured       TINYINT(1)   DEFAULT 0,
  display_order     SMALLINT UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── project_features ─────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS project_features (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  project_id    INT UNSIGNED NOT NULL,
  feature       TEXT NOT NULL,
  display_order SMALLINT UNSIGNED DEFAULT 0,
  FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── project_technologies ─────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS project_technologies (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  project_id    INT UNSIGNED NOT NULL,
  technology    VARCHAR(80) NOT NULL,
  display_order SMALLINT UNSIGNED DEFAULT 0,
  FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── code_samples ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS code_samples (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  project_id    INT UNSIGNED NOT NULL,
  language      VARCHAR(40)  NOT NULL,
  code          TEXT         NOT NULL,
  display_order SMALLINT UNSIGNED DEFAULT 0,
  FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Sample project ────────────────────────────────────────────────────────────
INSERT INTO projects
  (title, short_description, full_description, category, project_url, github_url, project_date, popularity, is_featured, display_order)
VALUES
  ('Portfolio v2','Modern PHP portfolio with dark/light theme, i18n, security hardening and lazy loading.','A professional portfolio built with clean PHP 8, PDO, CSRF protection, rate limiting, and a polished dark/light design system.','web','#','https://github.com/RafaelElebiyo/portfolio','2024-01-01',100,1,1);

SET @pid = LAST_INSERT_ID();

INSERT INTO project_features (project_id, feature, display_order) VALUES
  (@pid,'Dark/light theme with system preference detection',1),
  (@pid,'Multi-language support: ES, EN, FR',2),
  (@pid,'CSRF protection and rate limiting on contact form',3),
  (@pid,'Client-side pagination with filter and sort',4);

INSERT INTO project_technologies (project_id, technology, display_order) VALUES
  (@pid,'PHP 8',1),(@pid,'MySQL',2),(@pid,'Bootstrap 5',3),(@pid,'Vanilla JS',4);

INSERT INTO code_samples (project_id, language, code, display_order) VALUES
  (@pid,'php',"<?php\n// Parameterized query — safe from SQL injection\n\$stmt = \$pdo->prepare(\n    'SELECT * FROM projects WHERE category = :cat'\n);\n\$stmt->execute([':cat' => \$category]);\n\$rows = \$stmt->fetchAll();",1);

SET foreign_key_checks = 1;
