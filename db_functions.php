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
