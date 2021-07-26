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
function get_projects($con, $user_id) {
    $projects = [];
    $sql_projects = "SELECT id, project_title, (SELECT COUNT(t.id) FROM tasks t WHERE t.from_project = p.id) AS c_tasks FROM projects p WHERE p.user_id = ".$user_id;
    $projects = sql_query_result($con, $sql_projects);
    return $projects;
};

//  проверка на существование параметра запроса с идентификатором проекта. Если параметр присутствует, то показывать только те задачи, что относятся к этому проекту
function get_tasks($con, $user_id) {
    $tasks = [];
    if (isset($_GET['project_id'])) {
        $sql_tasks = "SELECT * FROM tasks WHERE from_project = ".$_GET['project_id'];
        }
        else {
        // получение полного списка задач у текущего пользователя
          $sql_tasks = "SELECT DISTINCT t.* FROM tasks t INNER JOIN projects p ON t.from_project = t.from_project WHERE p.user_id = ".$user_id." ORDER BY t.date_add DESC";
        }

      $tasks = sql_query_result($con, $sql_tasks);
      return $tasks;
};

// добавление новой задачи
function add_task($con, $task_title, $from_project, $date_deadline, $file) {
    if(!$con) {
        $error = mysqli_connect_error();
        print("Ошибка подключения к базе данных " . $error);
    } else {
        $str_deadline = "";
        if(!empty($date_deadline)) $str_deadline = "date_deadline = '".$date_deadline."', ";

        $sql_add_task = "INSERT INTO tasks SET task_title = '$task_title', user_id = 3, from_project = '$from_project', ".$str_deadline." file = '$file'";
        $add_task  = mysqli_query($con, $sql_add_task);
        return $add_task;
    }
}

// добавление нового пользователя
function add_user($con, $user_name, $email, $password) {
    if(!$con) {
        $error = mysqli_connect_error();
        print("Ошибка подключения к базе данных " . $error);
    } else {
        $sql_add_user = "INSERT INTO users SET user_name = '$user_name', user_email = '$email', user_password = '$password'";
        $add_user  = mysqli_query($con, $sql_add_user);
        return $add_user;
    }
}
