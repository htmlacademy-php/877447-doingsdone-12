<?php
require_once('helpers.php');

function get_tasks_summ($array, $title)
{
    $summ_tasks = 0;
    foreach ($array as $item) {
        if ($item['from_project'] == $title) {
            $summ_tasks++;
        }
    }
    return $summ_tasks;
};

function get_date_diff($date)
{
    $cur_date = time();
    $quantity_seconds_in_hour = 3600;

    $task_date = strtotime($date);
    return floor(($task_date - $cur_date) / $quantity_seconds_in_hour);
};

//  получаем значения из POST-запроса.
function getPostVal($name)
{
    return $_POST[$name] ?? "";
}

// проверка, является ли поле обязательным для заполнения
function isRequiredField($arr_fields)
{
    foreach ($arr_fields as $field) {
        if (empty($_POST[$field])) {
            return 'Поле не заполнено';
        }
    };
}

// Проверка длины поля
function isCorrectLength($name, $min, $max)
{
    $len = mb_strlen($name, 'utf-8');

    if ($len < $min or $len > $max) {
        return "Длина поля должна быть от $min до $max символов";
    }
}

// валидация селекта - выбора номера проекта на положительность и на целое значение
function isCorrectNumberProject($project)
{
    $number_project = (int)$project; // приводим к целому числу

    if ($number_project <= 0) {
        return "Выберите проект из списка";
    }
}

// валидация поля даты
function isCorrectDate($date)
{
    $current_date = date('Y-m-d');

    if (empty($date)) {
        return '';
    } else if (!(is_date_valid($date))) {
        return 'Неверный формат даты';
    } else if (strtotime($date) < strtotime($current_date)) {
        return 'Дата выполнения задачи должна быть больше или равна текущей.';
    } else {
        return date_create_from_format('Y-M-j', $date);
    }
}
