CREATE DATABASE IF NOT EXISTS doingsdone
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;

USE doingsdone;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_name VARCHAR(128) NOT NULL UNIQUE,
  user_email VARCHAR(128) NOT NULL UNIQUE,
  user_password VARCHAR(255) NOT NULL,
  registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE projects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  project_title VARCHAR(255) NOT NULL UNIQUE,
  user_id INT NOT NULL,
  FOREIGN KEY(user_id) REFERENCES users(id)
);

CREATE TABLE tasks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  task_title VARCHAR(255) NOT NULL UNIQUE,
  user_id INT NOT NULL,
  date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  date_deadline TIMESTAMP,
  task_status BIT(1) DEFAULT 0 NOT NULL,
  from_project INT NOT NULL,
  file VARCHAR(255),
  FOREIGN KEY(from_project) REFERENCES projects(id)
);

-- -- создаем индекс для полнотекстового поиска по названию задачи
-- CREATE FULLTEXT INDEX search_task ON tasks(task_title);
