<?php
require_once('settings.php');

$title = 'Добавить задачу';

$main_content = include_template('form_task.php', ['projects' => $projects, 'tasks' => $tasks ]);

$layout = include_template('layout.php', ['main_content' => $main_content, 'title' => $title]);


print($layout);
