<?php
session_start();  // Démarrer la session pour gérer les tokens CSRF

// Vérifier si la session est bien démarrée
if (session_status() === PHP_SESSION_ACTIVE) {
    echo "La session est bien démarrée.<br>";
} else {
    echo "La session n'a pas pu être démarrée.<br>";
}

// charge l'autoload de composer (chargement automatique des classes et dépendances)
require "vendor/autoload.php";

// charge le contenu du .env dans $_ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Instancier le CSRFTokenManager
$csrfManager = new CSRFTokenManager();

// Générer un token CSRF
$token = $csrfManager->generateCSRFToken();

// Afficher le token pour vérifier visuellement
echo "Token généré : " . $token . "<br>";

echo "<pre>";
print_r($_SESSION);
echo "</pre>";

