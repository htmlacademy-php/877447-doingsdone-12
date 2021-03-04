<?php
require_once('settings.php');

$user_id = 3;
$title = 'Добавить задачу';
$projects = get_projects($con, $user_id);


//определяем список обязательных полей
$required_fields = ['name', 'project'];
$errors = [];

if (isset($_POST['submit'])) {

// валидация обязательных полей
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Поле не заполнено';
        }
    };

// валидация поля с названием задачи на количество символов
    if(isset($_POST['name'])) {
        $min_char = 3;
        $max_char = 50;
        $len = strlen($_POST['name']);

        if ($len < $min_char or $len > $max_char) {
           $errors['name'] = 'Длина поля должна быть от 3 до 50 символов';
        }
    };

// валидация поля даты
     if(isset($_POST['date'])) {
         $current_date = date('Ymd');

         if(strtotime($_POST['date']) < strtotime($current_date)) {
             $errors['date'] = 'Дата выполнения задачи должна быть больше или равна текущей.';
         } else {
            date_create_from_format('j-M-Y', $_POST['date']);
         }
     };

// валидация файлового поля
    if (isset($_FILES['file'])) {
        $file_name = $_FILES['file']['name'];
        $file_path = __DIR__ . '/uploads/';
        $file_url = '/uploads/' . $file_name;

        move_uploaded_file($_FILES['file']['tmp_name'], $file_path.$file_name);
        // print("<a href='$file_url'>$file_name</a>");
    } else {
        $file_url = '';
    };

    if (empty($errors)) {
        add_task($con, $_POST['name'], $_POST['project'], $_POST['date'], $file_url);
        header('Location: index.php');
        exit;
      };
}

$main_content = include_template('form_task.php', ['projects' => $projects, 'errors' => $errors]);

$layout = include_template('layout.php', ['main_content' => $main_content, 'title' => $title]);

print($layout);
