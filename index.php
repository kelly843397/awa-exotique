<?php

// charge l'autoload de composer
require "vendor/autoload.php";

// charge le contenu du .env dans $_ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once 'managers/CategoryManager.php';

// Instancier le OrderManager
$orderManager = new OrderManager();

// Supprimer l'article (produit 2) de la commande 1
$deleted = $orderManager->deleteOrderItem(4, 1);

if ($deleted) {
    echo "Article supprimé de la commande avec succès.";
} else {
    echo "Erreur lors de la suppression de l'article de la commande.";
}