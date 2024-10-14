<?php

// Inclure l'autoload de composer et les fichiers nécessaires
require_once 'vendor/autoload.php'; // Assure-toi que le chemin est correct

// Inclusion du fichier où la classe et la méthode à tester se trouvent
use App\Controllers\OrderStatusController;

// Charger le fichier .env pour accéder aux variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Créer une instance du contrôleur
$orderStatusController = new OrderStatusController();

// Définir l'ID de l'élément à supprimer pour le test
$testId = 9; // Change cet ID selon celui que tu souhaites tester

// Simuler la requête DELETE
$_POST['id'] = $testId;
$_SERVER['REQUEST_METHOD'] = 'POST'; // Simuler la méthode POST

// Message de debug pour confirmer que le test commence
echo "Début du test de suppression pour l'ID : $testId\n";

// Appeler la méthode delete du contrôleur
$result = $orderStatusController->delete($testId);

// Vérification du résultat et affichage d'un message correspondant
if ($result) {
    echo "L'élément avec l'ID $testId a été supprimé avec succès par le contrôleur.\n";
} else {
    echo "Erreur : impossible de supprimer l'élément avec l'ID $testId via le contrôleur.\n";
}
