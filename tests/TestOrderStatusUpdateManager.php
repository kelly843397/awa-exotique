<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Assurez-vous que l'autoload est inclus correctement

use App\Managers\OrderStatusUpdateManager;


use PHPUnit\Framework\TestCase;

class TestOrderStatusUpdateManager extends TestCase
{
    private $pdo;
    private $orderStatusUpdateManager;

    protected function setUp(): void
    {
        // Charger les variables d'environnement
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../'); // Chemin vers le dossier contenant le .env
        $dotenv->load();

        // Connexion à la base de données de test
        $this->pdo = new PDO('mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Instanciation du manager
        $this->orderStatusUpdateManager = new OrderStatusUpdateManager();
    }

    public function testUpdateOrderStatus(): void
    {
        $result = $this->orderStatusUpdateManager->updateOrderStatus(9, 'Livré');
        $this->assertTrue($result, 'La mise à jour du statut a échoué.');
    }
}
