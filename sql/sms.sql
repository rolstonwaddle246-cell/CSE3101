-- User and User Role Management

CREATE DATABASE sms;
USE sms;

-- creating tables
CREATE TABLE `roles` (
    `role_id` INT AUTO_INCREMENT PRIMARY KEY,
    `role_name` VARCHAR(50) NOT NULL UNIQUE,  -- teacher/admin
    `is_active` TINYINT(1) DEFAULT 1  -- 1 for active, 0 for inactive. disable/reassign role instead of deleting them directly.
);
INSERT INTO roles (role_name) VALUES ('teacher'), ('admin');

CREATE TABLE `users` (
    `user_id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `first_name` VARCHAR(50) NOT NULL,
    `last_name` VARCHAR(50) NOT NULL,
    `role_id` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `must_reset_password` TINYINT(1) DEFAULT 1, -- 1 if user must reset password on first login, 0 otherwise
    FOREIGN KEY (role_id) REFERENCES roles(role_id) ON DELETE RESTRICT ON UPDATE CASCADE    -- can't delete a role if a user is assigned to it
);


-- POST ANNOUNCEMENTS (on dashboard)
CREATE TABLE announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    text VARCHAR(500) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- SCHOOL YEARS 
CREATE TABLE school_years (
    id INT AUTO_INCREMENT PRIMARY KEY,
    school_year VARCHAR(20) NOT NULL,
    status ENUM('Active','Inactive') NOT NULL DEFAULT 'Inactive',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- TERMS
CREATE TABLE terms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    school_year_id INT NOT NULL,
    term_name VARCHAR(100) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status ENUM('Active','Inactive') NOT NULL DEFAULT 'Inactive',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_terms_school_year
        FOREIGN KEY (school_year_id)
        REFERENCES school_years(id)
        ON DELETE CASCADE
);

-- REPORTS
CREATE TABLE grades (       -- Grade 1, Grade 2...
    grade_id INT AUTO_INCREMENT PRIMARY KEY,
    grade_name VARCHAR(20) NOT NULL UNIQUE, 
    level_order INT NOT NULL,              -- 1,2,3… for sorting
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO grades (grade_name, level_order) VALUES
('Grade 1', 1),
('Grade 2', 2),
('Grade 3', 3),
('Grade 4', 4);

CREATE TABLE teachers (
    teacher_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO teachers (first_name, last_name) VALUES
('Test', 'Teacher'),
('Anna', 'Thomas'),
('Mark', 'James');

CREATE TABLE classes (
    class_id INT AUTO_INCREMENT PRIMARY KEY,
    class_name VARCHAR(20) NOT NULL,      -- e.g. Grade 4A, 4B
    grade_id INT NOT NULL,
    teacher_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_classes_grade
        FOREIGN KEY (grade_id) REFERENCES grades(grade_id),
    CONSTRAINT fk_classes_teacher
        FOREIGN KEY (teacher_id) REFERENCES users(teacher_id)
);
INSERT INTO classes (class_name, grade_id, teacher_id) VALUES
('Grade 1A', 1, 2),
('Grade 1B', 1, 3),
('Grade 2A', 2, 2),
('Grade 3A', 3, 4),
('Grade 4A', 4, 2);

CREATE TABLE students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    student_number VARCHAR(20) UNIQUE NOT NULL, -- used for searching, etc
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    grade_id INT NOT NULL,
    class_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_students_grade
        FOREIGN KEY (grade_id)
        REFERENCES grades(grade_id)
        ON DELETE RESTRICT,
    CONSTRAINT fk_students_class
        FOREIGN KEY (class_id)
        REFERENCES classes(class_id)
        ON DELETE RESTRICT
);
INSERT INTO students (student_number, first_name, last_name, grade_id, class_id) VALUES
('S001', 'John', 'Doe', 4, 5),
('S002', 'Jane', 'Smith', 4, 5),
('S003', 'Liam', 'Brown', 2, 3);

CREATE TABLE subjects (
    subject_id INT AUTO_INCREMENT PRIMARY KEY,
    subject_name VARCHAR(100) NOT NULL,
    total_marks INT NOT NULL DEFAULT 50
);
INSERT INTO subjects (subject_name, total_marks) VALUES
('Mathematics', 50),
('English', 50),
('Science', 50),
('Social Studies', 50);

CREATE TABLE report_cards (
    report_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    school_year_id INT NOT NULL,
    term_id INT NOT NULL,
    teacher_id INT DEFAULT NULL,  -- who prepared the report
    grade_id INT NULL, -- student grade at the time
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (school_year_id) REFERENCES school_years(id),
    FOREIGN KEY (term_id) REFERENCES terms(id),
    FOREIGN KEY (teacher_id) REFERENCES teachers(teacher_id),
    FOREIGN KEY (grade_id) REFERENCES grades(grade_id)
);
INSERT INTO report_cards (student_id, school_year_id, term_id, teacher_id, grade_id)
VALUES (6, 31, 26, 6, 3);

CREATE TABLE report_card_details (
    detail_id INT AUTO_INCREMENT PRIMARY KEY,
    report_id INT NOT NULL,
    subject_id INT NOT NULL,
    marks_obtained INT NOT NULL,
    subject_grade VARCHAR(5),
    remarks VARCHAR(255),
    FOREIGN KEY (report_id) REFERENCES report_cards(report_id),
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id)
);
INSERT INTO report_card_details (report_id, subject_id, marks_obtained) VALUES
(1, 1, 40),
(1, 2, 45),
(1, 3, 38),
(1, 4, 42);

CREATE TABLE grading_system (
    grade_id INT AUTO_INCREMENT PRIMARY KEY,
    grade VARCHAR(5) NOT NULL,
    min_score INT NOT NULL,
    max_score INT NOT NULL,
    remarks VARCHAR(255)
);
INSERT INTO grading_system (grade, min_score, max_score, remarks) VALUES
('A', 85, 100, 'Excellent'),
('B', 75, 84, 'Very Good'),
('C', 65, 74, 'Good'),
('D', 50, 64, 'Fair'),
('E', 0, 49, 'Unsatisfactory');
