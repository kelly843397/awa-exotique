<?php

// Import de SimpleRouter
use Pecee\SimpleRouter\SimpleRouter;
use App\Controllers\OrderStatusController;

// Route pour l'URL racine (page d'accueil)
SimpleRouter::get('/', function() {
    SimpleRouter::response()->redirect('/orderstatus');
});

// Utilisation de SimpleRouter pour définir les routes pour OrderstatusController
SimpleRouter::get('/orderstatus', [OrderStatusController::class, 'findAll']);
SimpleRouter::get('/orderstatus/create', [OrderStatusController::class, 'create']);
SimpleRouter::post('/orderstatus/create', [OrderStatusController::class, 'create']);
SimpleRouter::get('/orderstatus/update/{orderId}', [OrderStatusController::class, 'update']);
SimpleRouter::post('/orderstatus/update/{orderId}', [OrderStatusController::class, 'update']);
SimpleRouter::post('/orderstatus/delete/{orderId}', [OrderStatusController::class, 'delete']);

// Lancer le routeur pour traiter la requête
SimpleRouter::start();
