<?php

// charge l'autoload de composer
require "vendor/autoload.php";

// charge le contenu du .env dans $_ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once 'managers/CategoryManager.php';

// Instancier le OrderManager
$orderManager = new OrderManager();

// Supprimer la commande avec l'ID 1
$orderDeleted = $orderManager->deleteOrder(1);

if ($orderDeleted) {
    echo "Commande supprimée avec succès.";
} else {
    echo "Erreur lors de la suppression de la commande.";
}