<?php

// charge l'autoload de composer
require "vendor/autoload.php";

// charge le contenu du .env dans $_ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once 'managers/CategoryManager.php';

// Instancier le CategoryManager
$categoryManager = new CategoryManager();

// Récupérer la catégorie avec l'ID 1 (par exemple)
$category = $categoryManager->getCategoryById(1);

if ($category) {
    echo "Nom de la catégorie : " . $category['name'];
} else {
    echo "Catégorie non trouvée.";

}