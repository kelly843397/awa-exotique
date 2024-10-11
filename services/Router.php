<?php

use Pecee\SimpleRouter\SimpleRouter;

SimpleRouter::get('/orderstatus', [OrderStatusController::class, 'findAll']);
SimpleRouter::get('/orderstatus/create', [OrderStatusController::class, 'create']);
SimpleRouter::post('/orderstatus/create', [OrderStatusController::class, 'create']);
SimpleRouter::get('/orderstatus/update/{id}', [OrderStatusController::class, 'update']);
SimpleRouter::post('/orderstatus/update/{id}', [OrderStatusController::class, 'update']);
SimpleRouter::get('/orderstatus/delete/{id}', [OrderStatusController::class, 'delete']);

// Lance le routeur
SimpleRouter::start();
