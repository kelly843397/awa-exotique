<?php

// charge l'autoload de composer
require "vendor/autoload.php";

// charge le contenu du .env dans $_ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once 'controllers/AbstractController.php';
require_once 'controllers/TestController.php'; // Chemin vers TestController

// Instancier le contrôleur et tester le rendu du template
$controller = new TestController();
$controller->testRender(); // Test du rendu Twig