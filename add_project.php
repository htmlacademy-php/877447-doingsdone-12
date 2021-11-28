<?php
require_once 'settings.php';

$title = 'Добавить проект';
session_start();

isAuthorizedUser($_SESSION['user']);

$user_id = $_SESSION['user']['id'];
$projects = get_projects($con, $user_id);
$required_fields = ['name']; // обязательные для заполнения поля
$errors = [];

$rules_projects = [
    'name' => function () {
        return isRequiredField($_POST['name']);
    },
];

if (isset($_POST['submit'])) {
    //применяем функции валидации полей формы к каждому элементу формы внутри цикла
    foreach ($_POST as $key => $value) {
        if (isset($rules_projects[$key])) {
            $rule = $rules_projects[$key];
            $errors[$key] = $rule();
        }
    }

    if (in_array_r($_POST['name'], $projects, $strict = false)) {
        $errors['name'] = 'Такой проект уже существует';
    }

    $errors = array_filter($errors);

    if (empty($errors)) {
        $answ = add_project($con, $_POST['name'], $user_id);
        if ($answ > 0) {
            header('Location: index.php');
            exit;
        }
    }
}

$main_content = include_template('form_project.php', ['projects' => $projects, 'errors' => $errors]);

$layout = include_template('layout.php', ['main_content' => $main_content, 'title' => $title]);

print($layout);
