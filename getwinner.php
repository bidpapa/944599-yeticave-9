<?php
require_once 'vendor/autoload.php';
$transport = new Swift_SmtpTransport("phpdemo.ru", 25);
$transport->setUsername("keks@phpdemo.ru");
$transport->setPassword("htmlacademy");

$mailer = new Swift_Mailer($transport);

$logger = new Swift_Plugins_Loggers_ArrayLogger();
$mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

$sql = "SELECT l.id, l.name AS lot_name, b.MAX, b1.id_user, u.name, u.email
FROM lot AS l 
LEFT JOIN (SELECT id_lot, MAX(amount) AS MAX FROM bid GROUP BY id_lot) AS b
ON b.id_lot = l.id
LEFT JOIN bid AS b1 ON b1.amount = b.max
LEFT JOIN user AS u ON u.id = b1.id_user
WHERE l.end_date <= NOW() AND l.id_winner IS NULL AND b.MAX IS NOT NULL";
$winners = returnArrayFromDB($link, $sql, MYSQLI_ASSOC);

if ($winners) {
    foreach ($winners as $winner) {
        $id_user = $winner['id_user'];
        $id_lot = $winner['id'];
        $sql = "UPDATE lot SET id_winner = ? WHERE id = ?";
        $stmt = db_get_prepare_stmt($link, $sql, [$id_user, $id_lot]);
        $result = mysqli_stmt_execute($stmt);
        if (!$result) {
            showError(mysqli_error($link));
        } else {
            $message = new Swift_Message();
            $message->setSubject("Победа на аукционе!");
            $message->setFrom(['keks@phpdemo.ru' => 'Yeticave']);
            $message->setBcc($winner['email'], $winner['name']);

            $site = $_SERVER['HTTP_HOST'];
            $msg_content = include_template('email.php', ['winner' => $winner, 'site' => $site]);
            $message->setBody($msg_content, 'text/html');

            $result = $mailer->send($message);

            if ($result) {
                $log = date('Y-m-d H:i:s') . " Рассылка успешно отправлена. ID лота: " . $winner['id'];
            }
            else {
                $log = date('Y-m-d H:i:s') . " Не удалось отправить рассылку: " . $logger->dump() . "ID лота: " . $winner['id'];
            }
            file_put_contents(__DIR__ . '/log.txt', $log . PHP_EOL, FILE_APPEND);
        }
    }
}