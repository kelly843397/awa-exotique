<?php

session_start();  // Démarrer la session pour gérer les tokens CSRF

// charge l'autoload de composer (chargement automatique des classes et dépendances)
require "vendor/autoload.php";

// charge le contenu du .env dans $_ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Instancier le CSRFTokenManager
$csrfManager = new CSRFTokenManager();

// Générer un token CSRF
$token = $csrfManager->generateCSRFToken();

