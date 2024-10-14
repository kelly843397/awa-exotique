<?php

// Inclure l'autoload de composer et les fichiers nécessaires
require_once __DIR__ . '/vendor/autoload.php';  // Assure-toi que le chemin est correct

// Inclure le modèle
use App\Models\Order;

// Créer une nouvelle instance de la classe Order
$order = new Order();

// Utiliser les setters pour définir des valeurs
$order->setId(1);
$order->setUserId(1001);
$order->setOrderDate('2024-10-14 10:00:00');
$order->setStatus('en attente');

// Utiliser les getters pour récupérer les valeurs
echo 'Order ID : ' . $order->getId() . PHP_EOL;
echo 'User ID : ' . $order->getUserId() . PHP_EOL;
echo 'Order Date : ' . $order->getOrderDate() . PHP_EOL;
echo 'Status : ' . $order->getStatus() . PHP_EOL;
