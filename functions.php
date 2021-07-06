<?php
require_once('helpers.php');

/**
 * Подсчитывает количество задач в одном проекте
 * *
 * @param $array массив задач
 * @param $title Название проекта
 *
 * @return number Возвращает количество задач в одном проекте
 */
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

/**
 * Вычисляем разницу между датами
 * *
 * @param $cur_date Текущая дата
 * @param $task_date Дата дедлайна
 *
 * @return number ВВозвращает разницу между датами
 */
function get_date_diff($date)
{
    $cur_date = time();
    $quantity_seconds_in_hour = 3600;

    $task_date = strtotime($date);
    return floor(($task_date - $cur_date) / $quantity_seconds_in_hour);
};

/**
 * Получаем значения из POST-запроса
 */
function getPostVal($name)
{
    return $_POST[$name] ?? "";
}

/**
 * Проверка, является ли поле обязательным для заполнения
 * *
 * @return string Если обязательное поле не заполнено, возвращает сообщение об ошибке
 */
function isRequiredField($field)
{
    if (empty($_POST[$field])) {
        return 'Поле не заполнено';
    };
}

/**
 * Проверка длины поля
 * @param $min Минимальное количество символов
 * @param $max Максимальное количество символов
 *
 * @return string Проверяет поле на пустоту или на допустимую длину, в случае несоответствия возвращает сообщение об ошибке
 */
function isCorrectLength($name, $min, $max)
{
    if (empty($name)) {
        return isRequiredField($name);
    } else {
        $len = mb_strlen($name, 'utf-8');

        if ($len < $min or $len > $max) {
            return "Длина поля должна быть от $min до $max символов";
        }
    }
}

/**
 * Валидация селекта - выбора номера проекта -  на положительность и на целое значение
 *
 * @return string Проверяет корректность выбранного номера проекта, в случае некорректного - возвращает сообщение об ошибке
 */
function isCorrectNumberProject($project)
{
    if (empty($project)) {
        return isRequiredField($project);
    } else {
        $number_project = (int)$project; // приводим к целому числу

        if ($number_project <= 0) {
            return "Выберите проект из списка";
        }
    }
}

/**
 * Валидация поля выбора даты
 *
 * @param $current_date Текущая дата
 *
 * @return string Проверяет корректность выбранной даты, в случае некорректной - возвращает сообщение об ошибке
 */
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

// проверка размера файла
function isCorrectFileSize($arr)
{
    $file_size = $arr['file']['size'];

    if ($file_size > 5000000) {
         return "Максимальный размер файла - 5Мб";
    }
}
