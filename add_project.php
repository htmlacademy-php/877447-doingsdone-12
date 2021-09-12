<?php
require_once('settings.php');

$title = 'Добавить проект';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
};

$user_id = $_SESSION['user']['id'];
$projects = get_projects($con, $user_id);
$required_fields = ['name']; // обязательные для заполнения поля
$errors = [];

$rules_projects = [
    'name' => function () {
        if (isset($_POST['name'])) {
            return isRequiredField($_POST['name']);
        }
    }
];

if (isset($_POST['submit'])) {
       //применяем функции валидации полей формы к каждому элементу формы внутри цикла
       foreach ($_POST as $key => $value) {
        if (isset($rules_projects[$key])) {
            $rule = $rules_projects[$key];
            $errors[$key] = $rule();
        }
    }

    if(empty($errors['name'])) $errors['name'] = get_saved_project_name($con, $_POST['name']);

    $errors = array_filter($errors);

    if (empty($errors)) {
        add_project($con, $_POST['name'], $user_id);
        header('Location: index.php');
        exit;
    }
}

$main_content = include_template('form_project.php', ['projects' => $projects, 'errors' => $errors]);

$layout = include_template('layout.php', ['main_content' => $main_content, 'title' => $title]);

print($layout);
