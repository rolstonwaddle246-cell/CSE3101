# login feature 
you need to run the seed .php files before you can login. you can change the login details in these seed php files to whatever u want. on the LOGIN page, the default password is admin123. then it will take u to change your password. remember that password.
## sms login credentials
these are the logins i created. 
ADMIN:
username: sjuman
password: 1stadmin

TEACHER:
username: teacher1
password: teacher1



the two users above was created with the php files in the /seeds folder. This is only used to start off this login part. 
We'll move on from this and use another implementation once we have the CRUD users on the admin dashboard ui ready, to create other users. 

## database
i only created the tables needed for this section, instead of running the entire database. roles and users tables are the ones i added. 

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



## need to work on for the login section:
- request password reset (see if u can work on this part; i give up on this part for now :/)
- 
