--  заполняем данными таблицу users

INSERT INTO users SET user_name = 'vasya', user_email = 'vasya@mail.ru', user_password = 'secret', registration_date = '2019-03-15';

INSERT INTO users SET user_name = 'katya', user_email = 'katya@mail.ru', user_password = '12345', registration_date = '2020-01-10';

INSERT INTO users SET user_name = 'kostya', user_email = 'kostya@mail.ru', user_password = 'qwerty', registration_date = '2020-12-31';

--  заполняем данными таблицу projects

INSERT INTO projects SET project_title = 'Входящие', user_id = (SELECT u.id FROM users u WHERE u.user_name = "kostya");

INSERT INTO projects SET project_title = 'Учеба', user_id = (SELECT u.id FROM users u WHERE u.user_name = "kostya");

INSERT INTO projects SET project_title = 'Работа', user_id = (SELECT u.id FROM users u WHERE u.user_name = "kostya");

INSERT INTO projects SET project_title = 'Домашние дела', user_id = (SELECT u.id FROM users u WHERE u.user_name = "kostya");

INSERT INTO projects SET project_title = 'Авто', user_id = (SELECT u.id FROM users u WHERE u.user_name = "kostya");

-- заполняем данными таблицу tasks

INSERT INTO tasks SET task_title = 'Собеседование в IT компании', user_id = (SELECT u.id FROM users u WHERE u.user_name = "kostya"), date_add = '2021-01-20', date_deadline = '2021-03-15', task_status = false, from_project = (SELECT p.id FROM projects p WHERE p.project_title = "Работа");

INSERT INTO tasks SET task_title = 'Выполнить тестовое задание', user_id = (SELECT u.id FROM users u WHERE u.user_name = "kostya"), date_add = '2021-01-10', date_deadline = '2021-02-20', task_status = false, from_project = (SELECT p.id FROM projects p WHERE p.project_title = "Работа");

INSERT INTO tasks SET task_title = 'Сделать задание первого раздела', user_id = (SELECT u.id FROM users u WHERE u.user_name = "kostya"), date_add = '2020-12-10', date_deadline = '2020-12-21', task_status = true, from_project = (SELECT p.id FROM projects p WHERE p.project_title = "Учеба");

INSERT INTO tasks SET task_title = 'Встреча с другом', user_id = (SELECT u.id FROM users u WHERE u.user_name = "kostya"), date_add = '2021-01-10', date_deadline = '2021-01-22', task_status = false, from_project = (SELECT p.id FROM projects p WHERE p.project_title = "Входящие");

INSERT INTO tasks SET task_title = 'Купить корм для кота', user_id = (SELECT u.id FROM users u WHERE u.user_name = "kostya"), date_add = null, date_deadline = null, task_status = false, from_project = (SELECT p.id FROM projects p WHERE p.project_title = "Домашние дела");

INSERT INTO tasks SET task_title = 'Заказать пиццу', user_id = (SELECT u.id FROM users u WHERE u.user_name = "kostya"), date_add = null, date_deadline = null, task_status = false, from_project = (SELECT p.id FROM projects p WHERE p.project_title = "Домашние дела");


 -- получить список из всех проектов для одного пользователя;
SELECT * FROM projects WHERE user_id = (SELECT u.id FROM users u WHERE u.user_name = "kostya");

-- получить список из всех задач для одного проекта;
SELECT * FROM tasks WHERE from_project = 2;

-- пометить задачу как выполненную;
UPDATE tasks SET task_status = true WHERE id = 4;

-- обновить название задачи по её идентификатору
UPDATE tasks SET task_title = 'Сходить в кино' WHERE id = 6;
