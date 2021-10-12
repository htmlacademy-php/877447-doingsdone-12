<?php

$config_file = 'config.php';

if (file_exists($config_file)) {
    require_once($config_file);

    function db_connect($db_config)
    {
        // подключение к серверу
        $con = mysqli_connect($db_config['db_host'], $db_config['db_username'], $db_config['db_password'], $db_config['db_name']);
        mysqli_set_charset($con, "utf-8");

        return $con;
    };
} else {
    exit("Файл config.php не найден");
};

// Получаем данные из БД
// $db_connect - данные для подключения к БД
// $sql_query - SQL-запрос
// $sql_result_array - преобразуем результаты SQL-запроса в массив
// return $sql_result_array - возвращаем полученный массив

function sql_query_result($db_connect, $sql_query)
{
    $sql_result = mysqli_query($db_connect, $sql_query);
    $sql_result_array = mysqli_fetch_all($sql_result, MYSQLI_ASSOC);

    return $sql_result_array;
};

// получение записей из БД
// получение списка проектов у текущего пользователя
function get_projects($con, $user_id)
{
    $projects = [];
    $sql_projects = "SELECT id, project_title, (SELECT COUNT(t.id) FROM tasks t WHERE t.from_project = p.id) AS c_tasks FROM projects p WHERE p.user_id = " . $user_id;
    $projects = sql_query_result($con, $sql_projects);
    return $projects;
};

//  проверка на существование параметра запроса с идентификатором проекта. Если параметр присутствует, то показывать только те задачи, что относятся к этому проекту
function get_tasks($con, $user_id, $filter)
{
    if (isset($_GET['project_id'])) {
        $sql_tasks = "SELECT * FROM tasks WHERE from_project = " . $_GET['project_id'];
    } else {
        // устанавливаем t.date_deadline в зависимости от параметра запроса
        $whereSql = "";
        if ($filter == 'today') {
            $whereSql = "t.date_deadline = CURDATE()";
        } else if ($filter == 'tomorrow') {
            $whereSql = "t.date_deadline = ADDDATE(CURDATE(),INTERVAL 1 DAY)";
        } else if ($filter == 'expired') {
            $whereSql = "t.date_deadline < CURDATE()";
        } else if ($filter = '' || $filter = 'all') {
            $whereSql = '1';
        }
        // получение полного списка задач у текущего пользователя
        $sql_tasks = "SELECT DISTINCT t.* FROM tasks t INNER JOIN projects p ON t.from_project = t.from_project WHERE t.user_id = " . $user_id . " AND  " . $whereSql . " ORDER BY t.date_add DESC";
    }

    $tasks = sql_query_result($con, $sql_tasks);
    return $tasks;
};

// добавление новой задачи
function add_task($con, $task_title, $from_project, $date_deadline, $file, $user_id)
{
    if (!$con) {
        $error = mysqli_connect_error();
        print("Ошибка подключения к базе данных " . $error);
    } else {
        $str_deadline = "";
        if (!empty($date_deadline)) $str_deadline = "date_deadline = '" . $date_deadline . "', ";

        $sql_add_task = "INSERT INTO tasks SET task_title = '$task_title', user_id = " . $user_id . ", from_project = '$from_project', " . $str_deadline . " file = '$file'";
        $add_task  = mysqli_query($con, $sql_add_task);
        return $add_task;
    }
}

// добавление нового проекта
function add_project($con, $project_title, $user_id)
{
    if (!$con) {
        $error = mysqli_connect_error();
        print("Ошибка подключения к базе данных " . $error);
    } else {
        $sql_add_project = "INSERT INTO projects SET project_title = '$project_title', user_id = " . $user_id;
        $add_project  = mysqli_query($con, $sql_add_project);
        return $add_project;
    }
}

// добавление нового пользователя
function add_user($con, $user_name, $email, $password)
{
    if (!$con) {
        $error = mysqli_connect_error();
        print("Ошибка подключения к базе данных " . $error);
    } else {
        $password = password_hash($password, PASSWORD_DEFAULT);

        $sql_add_user = "INSERT INTO users SET user_name = '$user_name', user_email = '$email', user_password = '$password'";
        $add_user  = mysqli_query($con, $sql_add_user);
        return $add_user;
    }
}

// Проверяем, существует ли уже такой email в базе. Для этого отправляем запрос
// Если возвращается ноль записей, выводим пустую строку, иначе - сообщение об ошибке
function get_saved_email($con, $email)
{
    $sql_email = "SELECT user_email FROM users WHERE user_email = '" . $email . "'";

    $saved_email = sql_query_result($con, $sql_email);
    return count($saved_email) == 0 ? "" : "Пользователь с таким email уже существует";
}

// Проверяем, существует ли уже такой логин в базе. Для этого отправляем запрос
// Если возвращается ноль записей, выводим пустую строку, иначе - сообщение об ошибке
function get_saved_login($con, $name)
{
    $sql_login = "SELECT user_name FROM users WHERE user_name = '" . $name . "'";

    $saved_login = sql_query_result($con, $sql_login);
    return count($saved_login) == 0 ? "" : "Пользователь с таким логином уже существует";
}

// получаем информацию о пользователе из БД по его емейлу
function search_user($con, $email)
{
    $user_data = [];
    $sql_user_data = "SELECT id, user_name, user_email, user_password FROM users WHERE user_email = '" . $email . "' LIMIT 1";
    $user_data = sql_query_result($con, $sql_user_data);

    return $user_data[0];
}

// получаем задачи через поиск
function search_tasks($con, $user_id)
{
    $tasks = [];
    $search_word = $_GET['search-tasks'] ?? '';
    $sql_search_tasks = "SELECT * FROM tasks WHERE MATCH(task_title) AGAINST('$search_word' IN BOOLEAN MODE) AND user_id = " . $user_id;
    $tasks = sql_query_result($con, $sql_search_tasks);

    return $tasks;
}

function update_task($con, $check)
{
    $tasks = [];
    if (isset($_GET['check'])) {
        $task_status = "";
        if ($check == 1) {
            $task_status = "task_status = 1";
        } else {
            $task_status = "task_status = 0";
        }
        $sql_task_update = "UPDATE tasks SET  " . $task_status . " WHERE id = " . $_GET['task_id'];
        // array_push($tasks, $sql_task_update);
        $update_task = mysqli_query($con, $sql_task_update);
        return $update_task;
    }
    return $tasks;
}
