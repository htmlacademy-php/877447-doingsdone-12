<?php
require_once('settings.php');
$id = 3;

// получение записей из БД
// получение списка проектов у текущего пользователя
$sql_projects = "SELECT id, project_title FROM projects WHERE user_id = ".$id;
$projects = sql_query_result($con, $sql_projects);


// получение списка задач у текущего пользователя
$sql_tasks = "SELECT DISTINCT t.* FROM tasks t INNER JOIN projects p ON t.from_project = t.from_project
WHERE p.user_id = ".$id;
$tasks = sql_query_result($con, $sql_tasks);

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

$title = 'Дела в порядке';
$quantity_hours_in_day = 24;

$main_content = include_template('main.php', ['projects' => $projects, 'tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks, 'quantity_hours_in_day' => $quantity_hours_in_day]);
$layout = include_template('layout.php', ['main_content' => $main_content, 'title' => $title]);
print ($layout);
