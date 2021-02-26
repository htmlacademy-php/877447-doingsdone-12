<?php
require_once('settings.php');
$id = 3;

$title = 'Дела в порядке';
$quantity_hours_in_day = 24;
$projects = getProjects($con, $id);
$tasks = getTasks($con, $id);

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);


$error_template = include_template('error.php');

$main_content = include_template('main.php', ['error_template' => $error_template, 'projects' => $projects, 'tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks, 'quantity_hours_in_day' => $quantity_hours_in_day]);

$layout = include_template('layout.php', ['main_content' => $main_content, 'title' => $title]);

print ($layout);
