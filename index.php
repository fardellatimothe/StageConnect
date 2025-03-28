<?php

// Inclut l'autoloader de Composer
require_once __DIR__ . '/vendor/autoload.php';

// Charge Twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig = new \Twig\Environment($loader);


$template = $twig->load('home.html.twig');

echo $template->render();