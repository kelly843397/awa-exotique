<?php

// Inclure l'autoload de composer et les fichiers nécessaires
require_once 'vendor/autoload.php'; // Assure-toi que le chemin est correct

// Inclusion du fichier où la classe et la méthode à tester se trouvent
use App\Controllers\OrderStatusController; // Assure-toi que le namespace et le chemin sont corrects

// Charger le fichier .env pour accéder aux variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Créer une instance du contrôleur
$orderStatusController = new OrderStatusController();

// Début de la capture de la sortie HTML générée par Twig
ob_start();

// Appel de la méthode findAll (qui génère du HTML via Twig)
$orderStatusController->findAll();

// Capturer le rendu généré par Twig
$result = ob_get_clean();

// Affichage du rendu capturé pour vérifier que la méthode fonctionne correctement
echo "<pre>";
echo htmlspecialchars($result);  // Utilisation de htmlspecialchars pour voir le HTML brut sans interprétation
echo "</pre>";
