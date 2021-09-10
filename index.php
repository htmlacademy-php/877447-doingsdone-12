<?php
require_once('settings.php');

$title = 'Дела в порядке';
$user_id = null;
session_start();

if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']['id'];

    // показывать или нет выполненные задачи
    $show_complete_tasks = rand(0, 1);
    $quantity_hours_in_day = 24;
    $projects = get_projects($con, $user_id);
    $tasks = get_tasks($con, $user_id);

    $error_template = include_template('error.php');

    // if(isset($_GET['submit-search'])) {
    //     $tasks = search_task($con, $user_id);
    // } else {
    //     $tasks = get_tasks($con, $user_id);
    // }

    $main_content = include_template('main.php', ['error_template' => $error_template, 'projects' => $projects, 'tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks, 'quantity_hours_in_day' => $quantity_hours_in_day]);
} else {
    $main_content = include_template('guest.php');
}


$layout = include_template('layout.php', ['main_content' => $main_content, 'title' => $title]);

print($layout);
