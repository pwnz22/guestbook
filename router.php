<?php

use Core\Router;

try {
    $router = new Router();
    $router
        ->addRoute(
            'GET', '/',
            function () use ($twig, $messages) {
                $template = $twig->loadTemplate('index.html');
                echo $template->render(array('getmessages' => $messages->getMessage()));;
            }
        )
        ->addRoute(
            'GET', '/notactive',
            function () use ($twig, $messages) {
                $template = $twig->loadTemplate('active.html');
                echo $template->render(array('getmessages' => $messages->notActiveMessages()));
            }
        )
        ->addRoute(
            'GET', '/angular',
            function () use($twig, $messages) {
                $template = $twig->loadTemplate('angular.html');
                echo $template->render(array('getmessages' => $messages->notActiveMessages()));
            }
        );
    echo $router->dispatch();
} catch (Exception $e) {
    echo $e->getMessage();
}