-- Create the database
CREATE DATABASE IF NOT EXISTS lms_system;
USE lms_system;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create courses table
CREATE TABLE IF NOT EXISTS courses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    link VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create enrollments table
CREATE TABLE IF NOT EXISTS enrollments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    enrollment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (user_id, course_id)
);

-- Insert an admin user (password: admin123)
INSERT INTO users (username, email, password, role) 
VALUES ('admin', 'admin@example.com', '$2y$10$8KyHYx5J5xYh.g5mFH0N6OJ3TT1wGYx8TEOz6Q6WDjwC0Q8ghxzDi', 'admin');

-- Insert some sample courses
INSERT INTO courses (title, description, link) VALUES
('Introduction to Web Development', 'Learn the basics of HTML, CSS, and JavaScript', 'https://www.geeksforgeeks.org/javascript/learn-web-development-basics-with-html-css-and-javascript/'),
('Python Programming', 'Master Python programming from scratch', 'https://www.geeksforgeeks.org/courses/master-python-complete-beginner-to-advanced'),
('Database Design', 'Learn how to design and optimize databases', 'https://www.geeksforgeeks.org/dbms/database-design-ultimate-guide/');
