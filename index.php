<?php
require ('helpers.php');
$main_content = include_template('main.php', ['projects' => $projects, 'tasks' => $tasks]);
$layout = include_template('layout.php', ['content' => $main_content, 'user_name' => $user_name, 'title' => $title]);
print ($layout);


