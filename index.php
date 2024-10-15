<?php

// Activer l'affichage des erreurs
ini_set('display_errors', 1);
error_reporting(E_ALL);

// charge l'autoload de composer (chargement automatique des classes et dépendances)
require "vendor/autoload.php";

// Charger le fichier Router.php
require __DIR__ . '/services/Router.php';

session_start();  // Démarrer la session pour gérer les tokens CSRF

// charge le contenu du fichier .env dans $_ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Instancier le CSRFTokenManager
$csrfManager = new CSRFTokenManager();

// Générer un token CSRF
$token = $csrfManager->generateCSRFToken();

// Charger les routes
require_once __DIR__ . '/services/Router.php';


