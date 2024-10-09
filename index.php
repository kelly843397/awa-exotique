<?php

// charge l'autoload de composer
require "vendor/autoload.php";

// charge le contenu du .env dans $_ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once 'managers/CategoryManager.php';

// Instancier le CategoryManager
$categoryManager = new CategoryManager();

// Supprimer une catégorie (par exemple, avec l'ID 1)
$categoryDeleted = $categoryManager->deleteCategory(1);

if ($categoryDeleted) {
    echo "Catégorie supprimée avec succès.";
} else {
    echo "Erreur lors de la suppression de la catégorie.";
}