<?php

require_once 'vendor/autoload.php';

use Core\Messages;
use Core\ConnectDB;
use Core\Upload;

$messages = new Messages(new ConnectDB());

$act = filter_input(INPUT_GET, 'act', FILTER_SANITIZE_STRING);

switch ($act) {
    case 'getMessages':
        $json = $messages->getMessage();
        break;
    case 'addMessage':
        $args = array(
            "name" => FILTER_SANITIZE_STRING,
            "email" => FILTER_SANITIZE_STRING,
            "message" => FILTER_SANITIZE_STRING,
        );

        $post = filter_input_array(INPUT_POST, $args);
        if (null != $post) {
            $upload = new Upload();
            $json = [
                'status' => 'Сообщение успешно добавлена!',
                $messages->addMessage(
                    $post['message'],
                    $post['name'],
                    $post['email'],
                    ($upload->getFiles()['avatar']['name'] ?: 'no_image.png')
                )
            ];
        } else {
            $json = [];
        }
        break;
    case 'activateAllMessages':
        $json = [
            'status' => 'Все сообщения активированы!',
            $messages->activateAllMessages()
        ];
        break;
    case 'deleteNotActiveAllMessages':
        $json = [
            'status' => 'Все сообщения удалены!',
            $messages->deleteNotActiveAllMessages()
        ];
        break;
    default:
        $json = [];
        break;
}

header('Content-Type: application/json');

echo json_encode($json, JSON_UNESCAPED_UNICODE);