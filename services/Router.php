<?php

// Import de SimpleRouter
use Pecee\SimpleRouter\SimpleRouter;
use App\Controllers\OrderStatusController;
use App\Controllers\ProductController;

// Pour la route "test-simple"
SimpleRouter::get('/test-simple', function() {
    echo 'Test simple fonctionnel';
});

// Route pour l'URL racine (page d'accueil)
SimpleRouter::get('/', function() {
    SimpleRouter::response()->redirect('/orderstatus');
});

SimpleRouter::get('/test_product_controller.php', [ProductController::class, 'create']);
SimpleRouter::get('/products', [ProductController::class, 'readAllProducts']);
SimpleRouter::get('/product/{id}', [ProductController::class, 'show']);
SimpleRouter::post('/product/store', [ProductController::class, 'store']);

// Utilisation de SimpleRouter pour définir les routes pour OrderstatusController
SimpleRouter::get('/orderstatus', [OrderStatusController::class, 'findAll']);
SimpleRouter::get('/orderstatus/create', [OrderStatusController::class, 'create']);
SimpleRouter::post('/orderstatus/create', [OrderStatusController::class, 'create']);
SimpleRouter::get('/orderstatus/update/{orderId}', [OrderStatusController::class, 'update']);
SimpleRouter::post('/orderstatus/update/{orderId}', [OrderStatusController::class, 'update']);
SimpleRouter::post('/orderstatus/delete/{orderId}', [OrderStatusController::class, 'delete']);


