<?php
require_once 'settings.php';

$title = 'Дела в порядке';
$user_id = null;
session_start();

if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']['id'];

    $filter = filter_input(INPUT_GET, 'filter');
    $check = filter_input(INPUT_GET, 'check');

    $projects = get_projects($con, $user_id);

    $error_template = include_template('error.php');

    if (isset($_GET['check'])) update_task($con, $check);

    if (isset($_GET['submit-search'])) {
        $tasks = search_tasks($con, $user_id);
    } else {
        $tasks = get_tasks($con, $user_id, $filter);
    }

    if (isset($_GET['show_completed'])) {
        $show_completed_tasks = $_GET['show_completed'];
    } else {
        $show_completed_tasks = 0;
    }

    $main_content = include_template('main.php', ['error_template' => $error_template, 'projects' => $projects, 'tasks' => $tasks, 'show_completed_tasks' => $show_completed_tasks, 'quantity_hours_in_day' => $quantity_hours_in_day]);
} else {
    $main_content = include_template('guest.php');
}


$layout = include_template('layout.php', ['main_content' => $main_content, 'title' => $title]);

print($layout);
