<?php

use Pecee\SimpleRouter\SimpleRouter;
use App\Controllers\OrderStatusController;

// Route pour la page d'accueil
SimpleRouter::get('/', function() {
    // Par exemple, redirige vers la liste des statuts
    return header('Location: /orderstatus');
});

// Définir les routes pour OrderStatusController
// Route pour afficher tous les statuts de commande
/*SimpleRouter::get('/orderstatus', [OrderStatusController::class, 'findAll']);
SimpleRouter::get('/orderstatus', 'App\Controllers\OrderStatusController@findAll');

// Route pour afficher le formulaire de création de statut de commande
SimpleRouter::get('/orderstatus/create', [OrderStatusController::class, 'create']);
SimpleRouter::post('/orderstatus/create', [OrderStatusController::class, 'create']);

// Route pour mettre à jour un statut de commande
SimpleRouter::get('/orderstatus/update/{orderId}', [OrderStatusController::class, 'update']);
SimpleRouter::post('/orderstatus/update/{orderId}', [OrderStatusController::class, 'update']);



// Route pour supprimer un statut de commande
SimpleRouter::get('/orderstatus/delete/{orderId}', [OrderStatusController::class, 'delete']);
SimpleRouter::post('/orderstatus/delete/{orderId}', [OrderStatusController::class, 'delete']);*/

// Définir les routes pour OrderStatusController
// Route pour afficher tous les statuts de commande
SimpleRouter::get('/orderstatus', 'App\Controllers\OrderStatusController@findAll');

// Route pour afficher le formulaire de création de statut de commande
SimpleRouter::get('/orderstatus/create', 'App\Controllers\OrderStatusController@create');
SimpleRouter::post('/orderstatus/create', 'App\Controllers\OrderStatusController@create');

// Route pour mettre à jour un statut de commande
SimpleRouter::get('/orderstatus/update/{orderId}', 'App\Controllers\OrderStatusController@update');
SimpleRouter::post('/orderstatus/update/{orderId}', 'App\Controllers\OrderStatusController@update');

// Route pour supprimer un statut de commande
SimpleRouter::post('/orderstatus/delete/{orderId}', 'App\Controllers\OrderStatusController@delete');



