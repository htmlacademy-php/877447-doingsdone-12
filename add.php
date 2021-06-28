<?php
require_once('settings.php');

$user_id = 3;
$title = 'Добавить задачу';
$projects = get_projects($con, $user_id);
$required_fields = ['name', 'project']; // обязательные для заполнения поля
$errors = [];

$rules = [
    'name' => function () {
        if (isset($_POST['name'])) {
            $min_char = 3;
            $max_char = 50;
            return isCorrectLength($_POST['name'], $min_char, $max_char);
        }
    },
    'project' => function () {
        if (isset($_POST['project'])) {
            return isCorrectNumberProject($_POST['project']);
        }
    },
    'date' => function () {
        if (isset($_POST['date'])) {
            return isCorrectDate($_POST['date']);
        }
    }
];


if (isset($_POST['submit'])) {

    foreach ($_POST as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

    $errors = array_filter($errors);

    // // валидация обязательных полей
    // foreach ($required_fields as $field) {
    //     if (empty($_POST[$field])) {
    //         $errors[$field] = 'Поле не заполнено';
    //     }
    // };

    // валидация файлового поля
    if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
        $file_name = $_FILES['file']['name'];
        $file_path = __DIR__ . '/uploads/';
        $file_url = '/uploads/' . $file_name;

        move_uploaded_file($_FILES['file']['tmp_name'], $file_path . $file_name);
    } else {
        $file_url = '';
    };


    if (empty($errors)) {
        add_task($con, $_POST['name'], $_POST['project'], $_POST['date'], $file_url);
        header('Location: index.php');
        exit;
    };
};

$main_content = include_template('form_task.php', ['projects' => $projects, 'errors' => $errors]);

$layout = include_template('layout.php', ['main_content' => $main_content, 'title' => $title]);

print($layout);
