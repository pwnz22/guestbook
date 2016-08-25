<?php

use Core\Messages;
use Core\ConnectDB;
use Core\Upload;

// Ловим значение GET
$delete = filter_input(INPUT_GET, 'delete');
$activateMessage = filter_input(INPUT_GET, 'active');

$args = array(
    "name" => FILTER_SANITIZE_STRING,
    "email" => FILTER_SANITIZE_STRING,
    "message" => FILTER_SANITIZE_STRING,
);

$post = filter_input_array(INPUT_POST, $args);
$messages = new Messages(new ConnectDB());

if (null !== $post) {
    $upload = new Upload();
    $messages->addMessage($post['message'], $post['name'], $post['email'], ($upload->getFiles()['avatar']['name'] ?: 'no_image.png'));
    header('Location: /');
}

if (null !== $delete) {
    $messages->deleteMessage($delete);
    header('Location: /');
}

if (null !== $activateMessage) {
    $messages->activateMessage($activateMessage);
    header('Location: /notactive');
}
