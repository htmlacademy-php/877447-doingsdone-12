<?php
require_once('settings.php');
$id = 3;

$title = 'Дела в порядке';
$quantity_hours_in_day = 24;

// получение записей из БД
// получение списка проектов у текущего пользователя
$sql_projects = "SELECT id, project_title, (SELECT COUNT(t.id) FROM tasks t WHERE t.from_project = p.id) AS c_tasks FROM projects p WHERE p.user_id = ".$id;
$projects = sql_query_result($con, $sql_projects);

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);


//  проверка на существование параметра запроса с идентификатором проекта. Если параметр присутствует, то показывать только те задачи, что относятся к этому проекту
if (isset($_GET['project_id'])) {
  $sql_tasks = "SELECT * FROM tasks WHERE from_project = ".$_GET['project_id'];
  }
  else { // получение полного списка задач у текущего пользователя
    $sql_tasks = "SELECT DISTINCT t.* FROM tasks t INNER JOIN projects p ON t.from_project = t.from_project WHERE p.user_id = ".$id;
  }

$tasks = sql_query_result($con, $sql_tasks);

// Если массив с задачами не пустой, показываем содержимое страницы
// Если значение параметра запроса не существует, либо по этому id проекта не нашлось ни одной записи, то вместо содержимого страницы возвращать код ответа 404.
// if (count($tasks) > 0) {
//     $main_content = include_template('main.php', ['projects' => $projects, 'tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks, 'quantity_hours_in_day' => $quantity_hours_in_day]);
// } else {
//     http_response_code(404);
//     $main_content = include_template('error.php');
// }

$error_template = include_template('error.php');

$main_content = include_template('main.php', ['error_template' => $error_template, 'projects' => $projects, 'tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks, 'quantity_hours_in_day' => $quantity_hours_in_day]);

$layout = include_template('layout.php', ['main_content' => $main_content, 'title' => $title]);

print ($layout);
