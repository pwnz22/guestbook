<?php

require 'vendor/autoload.php';

$loader = new Twig_Loader_Filesystem('view');

$twig = new Twig_Environment($loader, array('debug' => true));
$twig->addExtension(new Twig_Extension_Debug());

require_once 'actions.php';
require_once 'router.php';