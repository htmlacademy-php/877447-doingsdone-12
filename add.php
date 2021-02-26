<?php
require_once('settings.php');

$id = 3;
$title = 'Добавить задачу';
$projects = getProjects($con, $id);


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

$main_content = include_template('form_task.php', ['projects' => $projects]);

$layout = include_template('layout.php', ['main_content' => $main_content, 'title' => $title]);


print($layout);
