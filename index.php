<?php

// Activer l'affichage des erreurs
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();  // Démarrer la session pour gérer les tokens CSRF

// charge l'autoload de composer (chargement automatique des classes et dépendances)
require "vendor/autoload.php";

// Importer SimpleRouter
use Pecee\SimpleRouter\SimpleRouter;

// charge le contenu du .env dans $_ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Instancier le CSRFTokenManager
$csrfManager = new CSRFTokenManager();

// Générer un token CSRF
$token = $csrfManager->generateCSRFToken();

// Charger les routes
require_once __DIR__ . '/services/Router.php';

