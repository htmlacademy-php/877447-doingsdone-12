<?php
require_once('settings.php');

$title = 'Добавить задачу';

//определяем список обязательных полей
$required_fields = ['name', 'project'];
$errors = [];

if (isset($_POST['submit'])) {

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Поле не заполнено';
        }
    }
}

// // показать ошибку валидации
// if (count($errors)) {
//     print_r($errors[$field]);
// }

$main_content = include_template('form_task.php', ['error_template' => $error_template, 'projects' => $projects, 'tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks, 'quantity_hours_in_day' => $quantity_hours_in_day]);

$layout = include_template('layout.php', ['main_content' => $main_content, 'title' => $title]);


print($layout);
