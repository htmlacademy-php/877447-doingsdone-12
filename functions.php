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
  if (empty($field)) {
    return "Поле не заполнено";
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
 * Валидация селекта - выбора номера проекта -  на положительность и на целое значение
 *
 * @return string Проверяет корректность выбранного номера проекта, в случае некорректного - возвращает сообщение об ошибке
 */
function isCorrectNumberProject($project)
{
  $result = isRequiredField($project);

  if (empty($result)) {
    $number_project = (int)$project; // приводим к целому числу
    if ($number_project <= 0) {
      $result =  "Выберите проект из списка";
    }
  }
  return $result;
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

  if (!empty($date)) {
    if (!(is_date_valid($date))) {
      return "Неверный формат даты";
    } else if (strtotime($date) < strtotime($current_date)) {
      return "Дата выполнения задачи должна быть больше или равна текущей";
    }
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

/**
 * Проверка email, который ввел пользователь
 * @param $email
 *
 * @return string Проверяет корректность веденного email, в случае несоответствия возвращает сообщение об ошибке
 */
function isCorrectEmail($email)
{
  $result = isRequiredField($email);

  if (empty($result)) {
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = "Некорректный email";
    }
  }
  return $result;
}

/**
 * Проверка password, который ввел пользователь
 * @param $password
 *
 * @return string Проверяет корректность веденного пароля, в случае несоответствия возвращает сообщение об ошибке
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
