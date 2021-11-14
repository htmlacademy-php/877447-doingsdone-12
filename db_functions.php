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


/**
 *  Получает данные из БД
 *
 * @param $db_connect       - данные для подключения к БД
 * @param $sql_query        - SQL-запрос
 * @param $sql_result_array - преобразуем результаты SQL-запроса в массив

 * @return $sql_result_array - возвращаем полученный массив
 */
function sql_query_result($db_connect, $sql_query)
{
    $sql_result = mysqli_query($db_connect, $sql_query);
    $sql_result_array = mysqli_fetch_all($sql_result, MYSQLI_ASSOC);

    return $sql_result_array;
};


/**
 * Получает список проектов текущего пользователя
 *
 * @param $con     - ресурс соединения
 * @param $user_id - id пользователя
 * Если отсутствует подключение, формируем сообщение об ошибке.
 * Иначе создаем пустой массив проектов, формируем SQL-запрос, получаем список проектов для данного пользователя, преобразуем этот список в массив и записываем в $projects
 *
 * @return $projects - результат SQL-запроса, преобразованный в массив проектов
 */
function get_projects($con, $user_id)
{
    if (!$con) {
        $error = mysqli_connect_error();
        print("Ошибка подключения к базе данных " . $error);
    } else {
        $projects = [];
        $user_id = mysqli_real_escape_string($con, $user_id);

        $sql_projects = "SELECT id, project_title, (SELECT COUNT(t.id) FROM tasks t WHERE t.from_project = p.id) AS c_tasks FROM projects p WHERE p.user_id = " . $user_id;
        $projects = sql_query_result($con, $sql_projects);
        return $projects;
    }
}


/**
 * Получает список задач текущего пользователя
 *
 * @param $con     - ресурс соединения
 * @param $user_id - id пользователя
 * @param $filter  - параметр запроса
 *
 * Если отсутствует подключение, формируем сообщение об ошибке.
 * Иначе проверяем на существование параметра запроса с идентификатором проекта.
 * Если параметр присутствует, то показывать только те задачи, которые относятся к этому проекту.
 * Иначе - устанавливаем t.date_deadline в зависимости от параметра запроса с датой выполнения задачи.
 * Затем получаем список задач у текущего пользователя и преобразуем его в массив.
 *
 * @return $tasks - результат SQL-запроса, преобразованный в массив задач
 */
function get_tasks($con, $user_id, $filter)
{
    if (!$con) {
        $error = mysqli_connect_error();
        print("Ошибка подключения к базе данных " . $error);
    } else {
        if (isset($_GET['project_id'])) {
            $_GET['project_id'] = mysqli_real_escape_string($con, $_GET['project_id']);

            $sql_tasks = "SELECT * FROM tasks WHERE from_project = " . $_GET['project_id'];
        } else {
            $whereSql = "";
            if ($filter == 'today') {
                $whereSql = "t.date_deadline = CURDATE()";
            } elseif ($filter == 'tomorrow') {
                $whereSql = "t.date_deadline = ADDDATE(CURDATE(),INTERVAL 1 DAY)";
            } elseif ($filter == 'expired') {
                $whereSql = "t.date_deadline < CURDATE()";
            } elseif ($filter = '' || $filter = 'all') {
                $whereSql = '1';
            }

            $user_id = mysqli_real_escape_string($con, $user_id);
            $whereSql = mysqli_real_escape_string($con, $whereSql);
            // получение полного списка задач у текущего пользователя
            $sql_tasks = "SELECT DISTINCT t.* FROM tasks t INNER JOIN projects p ON t.from_project = t.from_project WHERE t.user_id = " . $user_id . " AND  " . $whereSql . " ORDER BY t.date_add DESC";
        }

        $tasks = sql_query_result($con, $sql_tasks);
        return $tasks;
    }
}


/**
 * Добавляет новую задачу
 *
 * @param $con           - ресурс соединения
 * @param $task_title    - название задачи
 * @param $from_project  - к какому проекту относится
 * @param $date_deadline - дата дедлайна
 * @param $file          - прикрепляемый файл
 * @param $user_id       - id пользователя
 *
 * Если отсутствует подключение, формируем сообщение об ошибке.
 * Иначе - если дата дедлайна не указана, формируем пустую строку для SQL-запроса.
 * Формируем SQL-запрос на добавление задачи, получаем объект результата на основе данного запроса.
 *
 * @return $add_task - объект результата
 */
function add_task($con, $task_title, $from_project, $date_deadline, $file, $user_id)
{
    if (!$con) {
        $error = mysqli_connect_error();
        print("Ошибка подключения к базе данных " . $error);
    } else {
        $str_deadline = "";
        if (!empty($date_deadline)) {
            $date_deadline = mysqli_real_escape_string($con, $date_deadline);

            $str_deadline = "date_deadline = '" . $date_deadline . "', ";
        }

        $task_title = mysqli_real_escape_string($con, $task_title);
        $user_id = mysqli_real_escape_string($con, $user_id);
        $from_project = mysqli_real_escape_string($con, $from_project);

        $sql_add_task = "INSERT INTO tasks SET task_title = '$task_title', user_id = " . $user_id . ", from_project = '$from_project', " . $str_deadline . " file = '$file'";
        $add_task  = mysqli_query($con, $sql_add_task);
        return $add_task;
    }
}

/**
 * Добавляет новый проект
 *
 * @param $con           - ресурс соединения
 * @param $project_title - название проекта
 * @param $user_id       - id пользователя
 *
 *  Если отсутствует подключение, формируем сообщение об ошибке.
 *  Иначе - формируем SQL-запрос на добавление проекта, получаем объект результата на основе данного запроса.
 *
 * @return $add_project - объект результата
 */
function add_project($con, $project_title, $user_id)
{
    if (!$con) {
        $error = mysqli_connect_error();
        print("Ошибка подключения к базе данных " . $error);
    } else {
        $project_title = mysqli_real_escape_string($con, $project_title);
        $user_id = mysqli_real_escape_string($con, $user_id);

        $sql_add_project = "INSERT INTO projects SET project_title = '$project_title', user_id = " . $user_id;
        $add_project  = mysqli_query($con, $sql_add_project);
        return $add_project;
    }
}

/**
 * Добавляет нового пользователя
 *
 * @param $con       - ресурс соединения
 * @param $user_name - имя пользователя
 * @param $email     - email пользователя
 * @param $password  - пароль пользователя
 *
 * Если отсутствует подключение, формируем сообщение об ошибке.
 * Иначе - хэшируем пароль, формируем SQL-запрос на добавление пользователя, получаем объект результата на основе данного запроса.
 *
 * @return $add_user - объект результата
 */
function add_user($con, $user_name, $email, $password)
{
    if (!$con) {
        $error = mysqli_connect_error();
        print("Ошибка подключения к базе данных " . $error);
    } else {
        $user_name = mysqli_real_escape_string($con, $user_name);
        $email = mysqli_real_escape_string($con, $email);

        $password = mysqli_real_escape_string($con, $password);
        $password = password_hash($password, PASSWORD_DEFAULT);

        $sql_add_user = "INSERT INTO users SET user_name = '$user_name', user_email = '$email', user_password = '$password'";
        $add_user  = mysqli_query($con, $sql_add_user);
        return $add_user;
    }
}


/**
 * Проверяет на существование email в базе
 *
 * @param  $con   - ресурс соединения
 * @param  $email - email пользователя
 *
 * Если отсутствует подключение, формируем сообщение об ошибке.
 * Иначе отправляем запрос на поиск в базе данного емейла, преобразуем полученный результат в массив.
 *
 * @return string если полученный массив содержит ноль записей, т.е. такого емейла нет, выводим пустую строку, иначе - соответствующее сообщение
 */
function get_saved_email($con, $email)
{
    if (!$con) {
        $error = mysqli_connect_error();
        print("Ошибка подключения к базе данных " . $error);
    } else {
        $email = mysqli_real_escape_string($con, $email);
        $sql_email = "SELECT user_email FROM users WHERE user_email = '" . $email . "'";

        $saved_email = sql_query_result($con, $sql_email);
        return count($saved_email) == 0 ? "" : "Пользователь с таким email уже существует";
    }
}


/**
 * Проверяет на существование логина в базе
 *
 * @param  $con  - ресурс соединения
 * @param  $name - логин пользователя
 *
 * Если отсутствует подключение, формируем сообщение об ошибке.
 * Иначе отправляем запрос на поиск в базе данного логина, преобразуем полученный результат в массив.
 *
 * @return string если полученный массив содержит ноль записей, т.е. такого логина неи, выводим пустую строку, иначе - соответствующее сообщение
 */
function get_saved_login($con, $name)
{
    if (!$con) {
        $error = mysqli_connect_error();
        print("Ошибка подключения к базе данных " . $error);
    } else {
        $name = mysqli_real_escape_string($con, $name);
        $sql_login = "SELECT user_name FROM users WHERE user_name = '" . $name . "'";

        $saved_login = sql_query_result($con, $sql_login);
        return count($saved_login) == 0 ? "" : "Пользователь с таким логином уже существует";
    }
}

/**
 * Получает информацию о пользователе из БД по его емейлу (при авторизации)
 *
 * @param  $con   - ресурс соединения
 * @param  $email - емейл пользователя
 *
 * Если отсутствует подключение, формируем сообщение об ошибке.
 * Иначе отправляем запрос на поиск в базе данных пользователя по его емейлу, преобразуем полученный результат в массив.
 *
 * @return array возвращает первый элемент этого массива, который также является массивом данных о пользователе.
 */
function search_user($con, $email)
{
    if (!$con) {
        $error = mysqli_connect_error();
        print("Ошибка подключения к базе данных " . $error);
    } else {
        $user_data = [];
        $email = mysqli_real_escape_string($con, $email);
        $sql_user_data = "SELECT id, user_name, user_email, user_password FROM users WHERE user_email = '" . $email . "' LIMIT 1";
        $user_data = sql_query_result($con, $sql_user_data);

        return $user_data[0];
    }
}


/**
 * Ищет задачи по их названию
 *
 * @param  $con     - ресурс соединения
 * @param  $user_id - id пользователя
 *
 * Если отсутствует подключение, формируем сообщение об ошибке.
 * Иначе создаем пустой массив задач, определяем слово, по которому ищем (пользователь вводит его в строке поиска).
 * Формируем SQL запрос на поиск по данному слову, преобразуем полученный результат в массив.
 *
 * @return array возвращает полученный массив задач.
 */
function search_tasks($con, $user_id)
{
    if (!$con) {
        $error = mysqli_connect_error();
        print("Ошибка подключения к базе данных " . $error);
    } else {
        $tasks = [];
        $search_word = $_GET['search-tasks'] ?? '';
        $search_word = mysqli_real_escape_string($con, $search_word);
        $user_id = mysqli_real_escape_string($con, $user_id);

        $sql_search_tasks = "SELECT * FROM tasks WHERE MATCH(task_title) AGAINST('$search_word' IN BOOLEAN MODE) AND user_id = " . $user_id;
        $tasks = sql_query_result($con, $sql_search_tasks);

        return $tasks;
    }
}


/**
 * Обновляет статус задач при клике по чекбоксу "Выполненные"
 *
 * @param  $con   - ресурс соединения
 * @param  $check - параметр запроса
 *
 * Если отсутствует подключение, формируем сообщение об ошибке.
 * Иначе создаем пустой массив задач, определяем статус задачи в зависимости от значения параметра запроса.
 * Формируем SQL запрос на обновление статуса задачи в таблице tasks по id, преобразуем полученный результат в массив.
 *
 * @return array возвращает массив задач.
 */
function update_task($con, $check)
{
    if (!$con) {
        $error = mysqli_connect_error();
        print("Ошибка подключения к базе данных " . $error);
    } else {
        $tasks = [];
        if (isset($_GET['check'])) {
            $task_status = "";
            if ($check == 1) {
                $task_status = "task_status = 1";
            } else {
                $task_status = "task_status = 0";
            }
            $_GET['task_id'] = mysqli_real_escape_string($con, $_GET['task_id']);
            $sql_task_update = "UPDATE tasks SET  " . $task_status . " WHERE id = " . $_GET['task_id'];

            $update_task = mysqli_query($con, $sql_task_update);
            return $update_task;
        }
        return $tasks;
    }
}


/**
 * Получает список пользователей, у которых запланированы задачи на сегодня
 *
 * @param  $con - ресурс соединения
 *
 * Если отсутствует подключение, формируем сообщение об ошибке.
 * Иначе создаем пустой массив пользователей.
 * Формируем SQL запрос на получение данных пользователей и их задач, которые запланированы на текущую дату и не выполнены, преобразуем полученный результат в массив.
 *              *
 * @return array возвращает массив пользоателей.
 */
function get_users_list_with_tasks_today($con)
{
    if (!$con) {
        $error = mysqli_connect_error();
        print("Ошибка подключения к базе данных " . $error);
    } else {
        $users_list = [];
        $sql_users_list = "SELECT u.id, u.user_name, u.user_email, t.task_title, t.date_deadline FROM users u JOIN tasks t ON t.user_id = u.id WHERE t.date_deadline = CURDATE() AND t.task_status = 0";

        $users_list = sql_query_result($con, $sql_users_list);
        return $users_list;
    }
}
