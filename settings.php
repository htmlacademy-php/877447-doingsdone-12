<?php
require_once 'helpers.php';
require_once 'functions.php';
require_once 'db_functions.php';

// создаем переменную подключения, общую на весь проект
$con = db_connect($db_config);
$config_file = 'config.php';


//подключаем $config_file и устанавливаем соединение с БД
if (file_exists($config_file)) {
    require_once($config_file);
    db_connect($db_config);
} else {
    exit("Файл config.php не найден");
};

// для просмотра логов ошибок
ini_set('display_errors', 1);
error_reporting(E_ALL);

$quantity_hours_in_day = 24;
