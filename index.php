<?php
require_once('settings.php');
$id = 3;

$title = 'Дела в порядке';
$quantity_hours_in_day = 24;

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

//  проверка на существования параметра запроса с идентификатором проекта. Если параметр присутствует, то показывать только те задачи, что относятся к этому проекту
if (isset($_GET['project_id'])) {
    $project_id = $_GET['project_id'];
  }
  else {
    $project_id = '';
  }

$sql_tasks_from_project = "SELECT * FROM tasks WHERE from_project = $project_id";

$result = mysqli_query($con, ($project_id) ? $sql_tasks_from_project : $sql_tasks);

if($result) {
    $query_tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $error = mysqli_error($con);
    print ('Ошибка '. $error);
}

// Если массив с задачами не пустой, показываем содержимое страницы
// Если значение параметра запроса не существует, либо по этому id проекта не нашлось ни одной записи, то вместо содержимого страницы возвращать код ответа 404.
if ($query_tasks) {
    $main_content = include_template('main.php', ['projects' => $projects, 'tasks' => $tasks, 'query_tasks' =>  $query_tasks, 'show_complete_tasks' => $show_complete_tasks, 'quantity_hours_in_day' => $quantity_hours_in_day]);
} else {
    http_response_code(404);
    $main_content = include_template('error.php',);
}

$layout = include_template('layout.php', ['main_content' => $main_content, 'title' => $title]);


print ($layout);
