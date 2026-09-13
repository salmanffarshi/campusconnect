-- ============================================================
-- CampusConnect - Database Schema
-- Web Technology Course Project
--
-- Target: MySQL / MariaDB (XAMPP)
-- Tables: exactly 7 (users, clubs, categories, events,
--         event_registrations, attendance, feedback)
--
-- Run this file once from phpMyAdmin (Import) or from the
-- command line:  mysql -u root -p < campusconnect_schema.sql
-- ============================================================

-- WARNING: the line below deletes an existing campusconnect database
-- and everything in it. It is commented out on purpose. Uncomment it
-- only when you deliberately want to rebuild the database from scratch.
-- DROP DATABASE IF EXISTS campusconnect;

CREATE DATABASE IF NOT EXISTS campusconnect
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE campusconnect;


-- ============================================================
-- 1. users
-- Holds every account: students, organizers and admins.
-- There is no separate students/organizers table by design.
-- Account deletion is a SOFT delete -> set status = 'inactive'.
-- ============================================================
CREATE TABLE users (
    user_id     INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    email       VARCHAR(150) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,          -- password_hash() output, never plain text
    role        ENUM('student','organizer','admin') NOT NULL,
    status      ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    -- Forgot-password OTP. Two columns, not a new table, so the
    -- schema is still exactly 7 tables. The OTP is stored hashed
    -- and cleared as soon as it has been used once.
    reset_otp         VARCHAR(255) NULL DEFAULT NULL,
    reset_otp_expires DATETIME NULL DEFAULT NULL,

    INDEX idx_users_role   (role),
    INDEX idx_users_status (status)
) ENGINE=InnoDB;


-- ============================================================
-- 2. clubs
-- One club is managed by exactly one organizer, so organizer_id
-- is UNIQUE. A club owns many events.
-- ============================================================
CREATE TABLE clubs (
    club_id      INT AUTO_INCREMENT PRIMARY KEY,
    club_name    VARCHAR(150) NOT NULL UNIQUE,
    description  TEXT NULL,
    organizer_id INT NOT NULL UNIQUE,           -- 1 club : 1 organizer

    CONSTRAINT fk_clubs_organizer
        FOREIGN KEY (organizer_id) REFERENCES users(user_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;
-- Note: PHP must verify that the referenced user has role = 'organizer'.
-- The database cannot enforce that through a foreign key alone.


-- ============================================================
-- 3. categories
-- Small lookup table, pre-populated below.
-- ============================================================
CREATE TABLE categories (
    category_id   INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;


-- ============================================================
-- 4. events
-- Created by an organizer as PENDING; admin approves or rejects.
-- Only APPROVED events are visible to students.
-- ============================================================
CREATE TABLE events (
    event_id    INT AUTO_INCREMENT PRIMARY KEY,
    club_id     INT NOT NULL,
    category_id INT NOT NULL,
    title       VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    event_date  DATE NOT NULL,
    start_time  TIME NOT NULL,
    end_time    TIME NOT NULL,
    venue       VARCHAR(200) NOT NULL,
    capacity    INT NOT NULL,
    status      ENUM('PENDING','APPROVED','REJECTED','CANCELLED') NOT NULL DEFAULT 'PENDING',
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_events_club
        FOREIGN KEY (club_id) REFERENCES clubs(club_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_events_category
        FOREIGN KEY (category_id) REFERENCES categories(category_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,

    CONSTRAINT chk_events_capacity CHECK (capacity > 0),
    CONSTRAINT chk_events_time     CHECK (end_time > start_time),

    INDEX idx_events_status (status),
    INDEX idx_events_date   (event_date)
) ENGINE=InnoDB;


-- ============================================================
-- 5. event_registrations
-- Resolves the M:N relationship between students and events.
-- UNIQUE(event_id, student_id) stops the same student from
-- registering for the same event twice.
--
-- The "no two overlapping events for one student" rule CANNOT be
-- expressed here - it is checked in PHP before the INSERT, using
-- only that student's REGISTERED rows. See handoff doc, section 29.
-- ============================================================
CREATE TABLE event_registrations (
    registration_id INT AUTO_INCREMENT PRIMARY KEY,
    event_id        INT NOT NULL,
    student_id      INT NOT NULL,
    registered_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status          ENUM('REGISTERED','CANCELLED') NOT NULL DEFAULT 'REGISTERED',

    CONSTRAINT uq_registration_event_student UNIQUE (event_id, student_id),

    CONSTRAINT fk_registrations_event
        FOREIGN KEY (event_id) REFERENCES events(event_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_registrations_student
        FOREIGN KEY (student_id) REFERENCES users(user_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,

    INDEX idx_registrations_student_status (student_id, status)
) ENGINE=InnoDB;
-- Note: PHP must verify that student_id belongs to a user with role = 'student'.


-- ============================================================
-- 6. attendance
-- A registration has zero or one attendance record (1 : 0..1),
-- so registration_id is UNIQUE.
-- ============================================================
CREATE TABLE attendance (
    attendance_id     INT AUTO_INCREMENT PRIMARY KEY,
    registration_id   INT NOT NULL UNIQUE,
    attendance_status ENUM('PRESENT','ABSENT') NOT NULL,
    marked_at         TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_attendance_registration
        FOREIGN KEY (registration_id) REFERENCES event_registrations(registration_id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;


-- ============================================================
-- 7. feedback
-- A registration has zero or one feedback record (1 : 0..1),
-- so registration_id is UNIQUE. Rating is 1-5.
-- ============================================================
CREATE TABLE feedback (
    feedback_id     INT AUTO_INCREMENT PRIMARY KEY,
    registration_id INT NOT NULL UNIQUE,
    rating          INT NOT NULL,
    comment         TEXT NULL,
    submitted_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT chk_feedback_rating CHECK (rating BETWEEN 1 AND 5),

    CONSTRAINT fk_feedback_registration
        FOREIGN KEY (registration_id) REFERENCES event_registrations(registration_id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;
-- Note: also validate the 1-5 range in PHP. Older MySQL versions
-- (before 8.0.16) parse CHECK but do not enforce it.


-- ============================================================
-- Seed data
-- ============================================================

-- Event categories (pre-populated, section 13 of the handoff doc)
INSERT INTO categories (category_name) VALUES
    ('Workshop'),
    ('Seminar'),
    ('Competition'),
    ('Cultural'),
    ('Sports');

-- One bootstrap admin. Admin accounts are created by the system,
-- not through public registration, so one must exist to start with.
--
--   email    : admin@campusconnect.edu
--   password : Admin@123
--
-- CHANGE THIS PASSWORD before the demo. The hash below was produced
-- by PHP password_hash('Admin@123', PASSWORD_DEFAULT).
INSERT INTO users (name, email, password, role, status) VALUES
    ('System Administrator',
     'admin@campusconnect.edu',
     '$2y$10$eImdU/Ht5jHwdXEjZvhOYun0b7inOCigqHSbmEfN4Dh2eMSPvxc9q',
     'admin',
     'active');
