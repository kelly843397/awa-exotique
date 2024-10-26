<?php

// Vérifie si une session est déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Démarrer la session pour gérer les tokens CSRF
}


// charge l'autoload de composer (chargement automatique des classes et dépendances)
require __DIR__ . '/vendor/autoload.php';

// charge le contenu du fichier .env dans $_ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Charger le fichier Router.php
require __DIR__ . '/services/Router.php';

// Vérifiez la route actuelle
$route = $_GET['route'] ?? null;

// Instancier le CSRFTokenManager seulement pour les routes qui en ont besoin
$csrfManager = null;
$token = null;

if ($route && !in_array($route, ['logout', 'home', 'contact'])) {
    // Instancier le CSRFTokenManager et générer un token CSRF
    $csrfManager = new CSRFTokenManager();
    $token = $csrfManager->generateCSRFToken();
}

// Créer une instance du routeur et gérer la requête
$router = new Router();
$router->handleRequest($_GET);

// Debug pour vérifier le token
var_dump($csrfToken);