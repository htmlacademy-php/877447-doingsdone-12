<?php
require_once('settings.php');

$user_id = 3;
$title = 'Добавить задачу';
$projects = get_projects($con, $user_id);


//определяем список обязательных полей
$required_fields = ['name', 'project'];
$errors = [];

if (isset($_POST['submit'])) {

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Поле не заполнено';
        }
    }

    if (isset($_FILES['file'])) {
        $file_name = $_FILES['file']['name'];
        $file_path = __DIR__ . '/uploads/';
        $file_url = '/uploads/' . $file_name;

        move_uploaded_file($_FILES['file']['tmp_name'], $file_path . $file_name);

        print("<a href='$file_url'>$file_name</a>");
    } else {
        $file_url = '';
    }

    if (empty($errors)) {
        add_task($con, $_POST['name'], $_POST['project'], $_POST['date'], $file_url);
        exit;
      };
}

// // показать ошибку валидации
// if (count($errors)) {
//     print_r($errors[$field]);
// }

$main_content = include_template('form_task.php', ['projects' => $projects, 'errors' => $errors]);

$layout = include_template('layout.php', ['main_content' => $main_content, 'title' => $title]);


print($layout);
