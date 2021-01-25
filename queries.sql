--  заполняем данными таблицу users

INSERT INTO users SET user_name = 'vasya', user_email = 'vasya@mail.ru', user_password = 'secret', registration_date = '15.03.2019';

INSERT INTO users SET user_name = 'katya', user_email = 'katya@mail.ru', user_password = '12345', registration_date = '10.01.2020';

INSERT INTO users SET user_name = 'kostya', user_email = 'kostya@mail.ru', user_password = 'qwerty', registration_date = '31.12.2020';

--  заполняем данными таблицу projects

INSERT INTO projects SET project_title = 'Входящие', user_id = 3;

INSERT INTO projects SET project_title = 'Учеба', user_id = 3;

INSERT INTO projects SET project_title = 'Работа', user_id = 3;

INSERT INTO projects SET project_title = 'Домашние дела', user_id = 3;

INSERT INTO projects SET project_title = 'Авто', user_id = 3;

-- заполняем данными таблицу tasks

INSERT INTO tasks SET task_title = 'Собеседование в IT компании', task_user = 3, date_add ='20.01.2021', date_deadline = '30.03.2021', task_status = false, from_project = 3;

INSERT INTO tasks SET task_title = 'Выполнить тестовое задание', task_user = 3, date_add = '10.01.2021', date_deadline = '20.02.2021', task_status = false, from_project = 3;

INSERT INTO tasks SET task_title = 'Сделать задание первого раздела', task_user = 3, date_add = '10.12.2020', date_deadline = '21.12.2020', task_status = true, from_project = 2;

INSERT INTO tasks SET task_title = 'Встреча с другом', task_user = 3, date_add = '10.01.2021', date_deadline = '22.01.2021', task_status = false, from_project = 1;

INSERT INTO tasks SET task_title = 'Купить корм для кота', task_user = 3, date_add = null, date_deadline = null, task_status = false, from_project = 4;

INSERT INTO tasks SET task_title = 'Заказать пиццу', task_user = 3, date_add = null, date_deadline = null, task_status = false, from_project = 4;


 -- получить список из всех проектов для одного пользователя;
SELECT * FROM projects WHERE user_id = 3;

-- получить список из всех задач для одного проекта;
SELECT * FROM tasks WHERE from_project = 2;

-- пометить задачу как выполненную; update tasks
UPDATE tasks SET task_status = true WHERE task_title = 'Встреча с другом';

-- обновить название задачи по её идентификатору update tasks
UPDATE tasks SET task_title = 'Сходить в кино' WHERE id = 6;
