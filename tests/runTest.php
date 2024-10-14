<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Affichage pour tester si les variables sont chargées
use App\Tests\TestManager;

$testManager = new TestManager();
$testManager->testConnection();
