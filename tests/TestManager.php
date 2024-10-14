<?php

namespace App\Tests;

use App\Managers\AbstractManager;

class TestManager extends AbstractManager
{
    public function testConnection()
    {
        if ($this->pdo instanceof \PDO) {
            echo "Connexion réussie à la base de données.";
        } else {
            echo "Échec de la connexion à la base de données.";
        }
    }
}
