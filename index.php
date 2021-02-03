<?php
require_once('settings.php');
require_once('settings.php');


// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
$projects = ['Входящие', 'Учеба', 'Работа', 'Домашние дела', 'Авто'];
$tasks = [
    [
        'title' => 'Собеседование в IT компании',
        'date' => '30.03.2021',
        'category' => 'Работа',
        'done' => false
    ],
    [
        'title' => 'Выполнить тестовое задание',
        'date' => '20.02.2021',
        'category' => 'Работа',
        'done' => false
    ],
    [
        'title' => 'Сделать задание первого раздела',
        'date' => '21.12.2020',
        'category' => 'Учеба',
        'done' => true
    ],
    [
        'title' => 'Встреча с другом',
        'date' => '22.01.2021',
        'category' => 'Входящие',
        'done' => false
    ],
    [
        'title' => 'Купить корм для кота',
        'date' => null,
        'category' => 'Домашние дела',
        'done' => false
    ],
    [
        'title' => 'Заказать пиццу',
        'date' => null,
        'category' => 'Домашние дела',
        'done' => false
    ]
];
$title = 'Дела в порядке';
$quantity_hours_in_day = 24;

$main_content = include_template('main.php', ['projects' => $projects, 'tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks, 'quantity_hours_in_day' => $quantity_hours_in_day]);
$layout = include_template('layout.php', ['main_content' => $main_content, 'title' => $title]);
print ($layout);
