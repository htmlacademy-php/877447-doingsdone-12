<?php

$config_file = 'config.php';

if (file_exists($config_file)) {
    require_once($config_file);

    function db_connect($db_config) {
        // подключение к серверу
        $con = mysqli_connect($db_config['db_host'], $db_config['db_username'], $db_config['db_password'], $db_config['db_name']);
        mysqli_set_charset($con, "utf-8");

        return $con;
    };
} else {
    exit ("Файл config.php не найден");
};

// Получаем данные из БД
// $db_connect - данные для подключения к БД
// $sql_query - SQL-запрос
// $sql_result_array - преобразуем результаты SQL-запроса в массив
// return $sql_result_array - возвращаем полученный массив

function sql_query_result($db_connect, $sql_query) {
    $sql_result = mysqli_query($db_connect, $sql_query);
    $sql_result_array = mysqli_fetch_all($sql_result, MYSQLI_ASSOC);

    return $sql_result_array;
};

// получение записей из БД
// получение списка проектов у текущего пользователя
function getProjects($con, $id) {
    $projects = [];
    $sql_projects = "SELECT id, project_title, (SELECT COUNT(t.id) FROM tasks t WHERE t.from_project = p.id) AS c_tasks FROM projects p WHERE p.user_id = ".$id;
    $projects = sql_query_result($con, $sql_projects);
    return $projects;
};

//  проверка на существование параметра запроса с идентификатором проекта. Если параметр присутствует, то показывать только те задачи, что относятся к этому проекту
function getTasks($con, $id) {
    $tasks = [];
    if (isset($_GET['project_id'])) {
        $sql_tasks = "SELECT * FROM tasks WHERE from_project = ".$_GET['project_id'];
        }
        else {
        // получение полного списка задач у текущего пользователя
          $sql_tasks = "SELECT DISTINCT t.* FROM tasks t INNER JOIN projects p ON t.from_project = t.from_project WHERE p.user_id = ".$id;
        }

      $tasks = sql_query_result($con, $sql_tasks);
      return $tasks;
};
