-- Collaborative Story Writing Platform Database
-- Create database
CREATE DATABASE IF NOT EXISTS collaborative_story_db;
USE collaborative_story_db;

-- Create stories table
CREATE TABLE IF NOT EXISTS stories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create contributions table
CREATE TABLE IF NOT EXISTS contributions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    story_id INT NOT NULL,
    author_name VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    contributed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (story_id) REFERENCES stories(id) ON DELETE CASCADE
);

-- Insert some sample data
INSERT INTO stories (title) VALUES 
('The Mysterious Forest'),
('Space Adventure'),
('The Lost Treasure');

INSERT INTO contributions (story_id, author_name, content) VALUES 
(1, 'Alice', 'Once upon a time, there was a mysterious forest that no one dared to enter.'),
(1, 'Bob', 'The trees whispered ancient secrets to those who listened carefully.'),
(2, 'Charlie', 'Captain Sarah stared at the vast expanse of space through her ship\'s viewport.'),
(2, 'Diana', 'The stars seemed to pulse with an otherworldly rhythm.'),
(3, 'Eve', 'Deep in the jungle, rumors spoke of a treasure beyond imagination.');
