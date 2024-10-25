<?php

abstract class AbstractManager
{
    protected PDO $pdo;

    public function __construct()
    {
        // Connexion à la base de donnée via PDO
        $connexion = "mysql:host=" . $_ENV['DB_HOST'] .
                     ";port=" . $_ENV['DB_PORT'] .
                     ";dbname=" . $_ENV['DB_NAME'] .
                     ";charset=utf8"; // Charset par défaut

        $this->pdo = new PDO(
            $connexion,
            $_ENV['DB_USER'],
            $_ENV['DB_PASSWORD']
        );

        if (!$this->pdo) {
            die("Erreur lors de la connexion à la base de donnée.");
        }
    }
}