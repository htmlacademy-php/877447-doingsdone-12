<?php
require_once 'helpers.php';
require_once 'db_functions.php';

/**
 * Вычисляет разницу между датами
 * @param string $date  дата дедлайна
 *
 * @return integer возвращает разницу между датами
 */
function get_date_diff($date)
{
    $cur_date = time();
    $quantity_seconds_in_hour = 3600;

    $task_date = strtotime($date);
    return floor(($task_date - $cur_date) / $quantity_seconds_in_hour);
};

/**
 * Получает значения из POST-запроса
 * @param string $name
 */
function getPostVal($name)
{
    return $_POST[$name] ?? "";
}

/**
 * Проверяет на заполненность обязательного поля
 * @param  string $field проверяемое поле
 * @return string|null Возвращает сообщение об ошибке, если обязательное поле не заполнено, или null, если заполнено (в условие не заходит)
 */
function isRequiredField($field)
{
    if (!isset($field) || empty($field)) {
        return "Поле не заполнено";
    }
}

/**
 * Проверяет поле на пустоту и на допустимую длину
 * @param string $name проверяемое поле
 * @param integer $min Минимальное количество символов
 * @param integer $max Максимальное количество символов
 *
 * @return string|null Возвращает сообщение об ошибке, если обязательное поле не заполнено или его длина не соответствует допустимой, или null, если поле заполнено и его длина соответствует допустимой (в условие не заходит)
 */
function isCorrectLength($name, $min, $max)
{
    $result = isRequiredField($name);

    if (empty($result)) {
        $len = mb_strlen($name, 'utf-8');
        if ($len < $min or $len > $max) {
            $result = "Длина поля должна быть от $min до $max символов";
        }
    }
    return $result;
}


/**
 * Проверяет селект - выбор номера проекта -  на положительность и на целое значение
 * @param string $project номер проекта в селекте, изначально строка, затем приводим к целому числу
 *
 * @return string|null Возвращает сообщение об ошибке, если обязательное поле не заполнено или номер проекта некорректен, или null, если заполнено и номер проекта корректен (в условие не заходит)
 */
function isCorrectNumberProject($project)
{
    $result = isRequiredField($project);

    if (empty($result)) {
        $number_project = intval($project);
        if (!is_numeric($number_project) || $number_project <= 0) {
            $result =  "Выберите проект из списка";
        }
    }
    return $result;
}

/**
 * Валидирует поле выбора даты
 * @param string $date выбранная дата
 *
 * @return string|null Возвращает сообщение об ошибке, если выбранная дата невалидна или меньше текущей, или null, если дата валидна и больше или равна текущей
 */
function isCorrectDate($date)
{
    $current_date = date('Y-m-d');

    if (!empty($date)) {
        if (!(is_date_valid($date))) {
            return "Неверный формат даты";
        } elseif (strtotime($date) < strtotime($current_date)) {
            return "Дата выполнения задачи должна быть больше или равна текущей";
        }
    }
}

/**
 * Проверяет размер файла
 * @param array $arr массив файлов
 *
 * @return string|null Возвращает сообщение об ошибке, если размер файла превышает 5Мб, или null, если не превышает (в условие не заходит)
 */
function isCorrectFileSize($arr)
{
    $file_size = $arr['file']['size'];

    if ($file_size > 5000000) {
        return "Максимальный размер файла - 5Мб";
    }
}

/**
 * Проверяет поле email на заполненность и на корректность введенного значения
 * @param string $email - значение поля email
 *
 * @return string|null  Возвращает сообщение об ошибке, если поле email не заполнено или заполнено некорректно,  или null, если заполнено и заполнено корректно (в условие не заходит)
 */
function isCorrectEmail($email)
{
    $result = isRequiredField($email);

    if (empty($result)) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $result = "Некорректный email";
        }
    }
    return $result;
}

/**
 * Проверяет password, который ввел пользователь
 * @param string $password значение поля password
 *
 * @return string|null  Возвращает сообщение об ошибке, если поле password не заполнено или заполнено некорректно,  или null, если заполнено и заполнено корректно (в условие не заходит)
 */
function isCorrectPassword($password)
{
    $result = isRequiredField($password);

    if (empty($result)) {
        $pattern_password = '/^[a-z0-9_]+$/i';
        if (!preg_match($pattern_password, $password)) {
            $result = "Пароль может содержать только цифры и буквы английского алфавита, а также знак подчеркивания";
        }
    }
    return $result;
}

/**
 * Рекурсивная функция поиска по массиву, проходим по каждому элементу, если элемент является массивом, то углубляемся в него и продолжаем поиск, если нет, то сравниваем с искомым значением
 * @param $needle  искомое значение
 * @param array   $haystack массив
 * @param boolean $strict
 *
 * @return boolean
 */
function in_array_r($needle, $haystack, $strict = false)
{
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }
    return false;
}

/**
 * Проверяет, авторизован ли пользователь
 * @param array $data- данные пользователя
 *
 * Проверяет существование данных пользователя (ключа 'user' в массиве $_SESSION) и, в случае отсутствия, перенаправляет пользователя на главную страниццу
 * @return boolean|null //?
 */

function isAuthorizedUser($data)
{
    if (!isset($data)) {
        header('Location: index.php');
        exit;
    }
}
