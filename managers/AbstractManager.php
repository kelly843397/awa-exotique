<?php

abstract class AbstractManager
{
    protected PDO $db;

    public function __construct()
    {
        $connexion = "mysql:host=" . $_ENV['DB_HOST'] . 
                     ";port=" . $_ENV['DB_PORT'] . 
                     ";dbname=" . $_ENV['DB_NAME'] . 
                     ";charset=utf8"; // Charset par défaut

        $this->db = new PDO(
            $connexion,
            $_ENV['DB_USER'],
            $_ENV['DB_PASSWORD']
        );

        if (!$this->db) {
            die("Erreur lors de la connexion à la base de données.");
        }
    }
}
