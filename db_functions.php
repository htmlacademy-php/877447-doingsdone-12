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
