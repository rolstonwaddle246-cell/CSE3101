-- User and User Role Management

CREATE DATABASE sms;
USE sms;

-- creating tables
CREATE TABLE `roles` (
    `role_id` INT AUTO_INCREMENT PRIMARY KEY,
    `role_name` VARCHAR(50) NOT NULL UNIQUE,  -- teacher/admin
    `is_active` TINYINT(1) DEFAULT 1  -- 1 for active, 0 for inactive. disable/reassign role instead of deleting them directly.
);

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

-- inserting data
INSERT INTO roles (role_name) VALUES ('teacher'), ('admin');


-- POST ANNOUNCEMENTS (on dashboard)
CREATE TABLE announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    text VARCHAR(500) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

