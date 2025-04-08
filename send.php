<?php
    require_once __DIR__ . '/vendor/autoload.php';
    use PhpAmqpLib\Connection\AMQPStreamConnection;
    use PhpAmqpLib\Message\AMQPMessage;

    $connection = new AMQPStreamConnection('localhost', 5672, 'admin', 'admin');
    $channel = $connection->channel();

    $channel->queue_declare('hello', false, false, false, false);

    $msg = new AMQPMessage('Hello!');
    $channel->basic_publish($msg, '', 'hello');

    echo " [x] Sent 'Hello!'\n";

    $channel->close();
    $connection->close();
