<?php
require_once 'helpers.php';
require_once 'functions.php';
require_once 'db_functions.php';

// для просмотра логов ошибок
ini_set('display_errors', 1);
error_reporting(E_ALL);

// создаем переменную подключения, общую на весь проект
$con = db_connect($db_config);
$quantity_hours_in_day = 24;
