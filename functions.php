<?php
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

// Проверка длины поля
function isCorrectLength($name, $min, $max)
{
    $len = mb_strlen($name, 'utf-8');

    if ($len < $min or $len > $max) {
        return $errors['name'] = "Длина поля должна быть от $min до $max символов";
    }
}

// валидация поля даты
function isCorrectDate($date)
{
    $current_date = date('Y-m-d');

    if (!(is_date_valid($date))) {
        return $error['date'] = 'Неверный формат даты';
    } else if (strtotime($date) < strtotime($current_date)) {
        return $errors['date'] = 'Дата выполнения задачи должна быть больше или равна текущей.';
    } else {
        return date_create_from_format('Y-M-j', $date);
    }
}

// функция проверки на целое число
function isIntNumber($number)
{
    // проверяем, не является ли переменная целым числом и строкой (числа могут передаваться и в строке)
    if (!is_int($number) && !is_string($number)) return false;
    // далее - регулярное выражение, в котором в начале строки либо минус (с количеством повторений 0 или 1), либо ничего, для проверки на положительное и отрицательное число со знаком минус.
    // затем проверки на сами числа, где первые числа от 1 до 9, а вторые и последующие от 0 до 9 (для исключения "05" и т.д.) или вместо всего этого у нас 0 (|0 - оператор альтернативы).
    if (!preg_match("/^-?/(([1-9][0-9]*|0/))$/", $number)) return false;
    return true;
}

