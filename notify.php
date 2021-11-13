<?php
// подключаем composer
require_once 'vendor/autoload.php';
require_once 'settings.php';
require_once 'settings_mail_smtp.php';

// Конфигурация траспорта
$transport = (new Swift_SmtpTransport('smtp.mailtrap.io', 2525))
    ->setEncryption(null)
    ->setUsername($mail_smtp_username)
    ->setPassword($mail_smtp_password);

$mailer = new Swift_Mailer($transport);

$users = get_users_list_with_tasks_today($con);
$recipients = [];

foreach ($users as $user) {
    $recipients[$user['id']]['name'] = $user['user_name'];
    $recipients[$user['id']]['email'] = $user['user_email'];
    $recipients[$user['id']]['tasks'][] = [
        'title' => $user['task_title'],
        'deadline' => $user['date_deadline']
    ];
}

foreach ($recipients as $recipient) {
    $message = (new Swift_Message())
        ->setSubject("Уведомление от сервиса «Дела в порядке»")
        ->setFrom('keks@phpdemo.ru')
        ->setTo($recipient['email']);

    $messageContent = "Уважаемый {$recipient['name']}! </br>";

    foreach ($recipient['tasks'] as $task) {
        $task['deadline'] = date('d.m.Y');
        $messageContent .= "У вас запланирована задача: {$task['title']} на {$task['deadline']} </br>";
    }

    $message->setBody($messageContent, "text/html");
    $result = $mailer->send($message);


    if ($result) {
        print("Рассылка для {$recipient['name']} успешно отправлена");
    } else {
        print("Не удалось отправить рассылку для {$recipient['name']}");
    }
}
