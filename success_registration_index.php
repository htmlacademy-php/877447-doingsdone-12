<?php
require_once 'settings.php';

$title = 'Успешная регистрация';
$user_id = null;

$main_content = include_template('registered_guest.php');

$layout = include_template('layout.php', ['main_content' => $main_content, 'title' => $title]);

print($layout);
