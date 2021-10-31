<?php
// подключаем composer
require_once 'vendor/autoload.php';
require_once 'settings.php';

// Конфигурация траспорта
$transport = new Swift_SmtpTransport("mailtrap.io", 25);
$transport->setUsername("keks@phpdemo.ru");
$transport->setPassword("htmlacademy");

$mailer = new Swift_Mailer($transport);

$users = get_users_list_with_tasks_today($con);
$recipients = [];

foreach($users as $user) {
  $recipients[$user['id']]['name'] = $user['user_name'];
  $recipients[$user['id']]['email'] = $user['user_email'];
  $recipients[$user['id']]['tasks'][] = [
      'title' => $user['task_title'],
      'deadline' => $user['date_deadline']
  ];

}

foreach($recipients as $recipient) {
    $message = new Swift_Message();
    $message->setSubject("Уведомление от сервиса «Дела в порядке»");
    $message->setFrom('keks@phpdemo.ru');
    $message->setTo($recipient['email']);

    $messageContent = "Уважаемый {$recipient['name']}!";

    foreach($recipient['tasks'] as $task) {
        $messageContent .= "У вас запланирована задача: {$task['title']} на {$task['deadline']}";
    }

    $message->addPart($messageСontent . '<br>', 'text/plain');
    $result = $mailer->send($message);


    if ($result) {
        print("Рассылка для {$recipient['name']} успешно отправлена");
    }
    else {
        print("Не удалось отправить рассылку для {$recipient['name']}");
    }
}


