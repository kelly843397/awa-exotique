<?php

namespace App\Controllers;

use App\Managers\OrderManager;
use App\Models\Order;

class OrderController extends AbstractController
{
    private OrderManager $orderManager;

    public function __construct()
    {
        $this->orderManager = new OrderManager();
    }

    // Afficher toutes les commandes
    public function listOrders()
    {
        $orders = $this->orderManager->findAll();
        return $this->render('orders/list.html.twig', ['orders' => $orders]);
    }

    // Afficher une commande spécifique
    public function showOrder(int $id)
    {
        $order = $this->orderManager->find($id);
        if ($order) {
            return $this->render('orders/show.html.twig', ['order' => $order]);
        } else {
            // Gérer le cas où la commande n'existe pas
            return $this->render('errors/404.html.twig', []); // Fournir un tableau vide en deuxième argument
        }
    }

    // Créer une nouvelle commande
    public function createOrder()
    {
        // Logique pour la création d'une commande via un formulaire POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $order = new Order();
            $order->setUserId($_POST['user_id']);
            $order->setOrderDate($_POST['order_date']);
            $order->setStatus($_POST['status']);
            
            if ($this->orderManager->create($order)) {
                // Rediriger ou afficher un succès
                return $this->redirect('/orders');
            } else {
                return $this->render('errors/500.html.twig');
            }
        }
        // Afficher le formulaire de création si la requête est GET
        return $this->render('orders/create.html.twig');
    }

    // Mettre à jour une commande
    public function updateOrder(int $id)
    {
        // Logique pour la mise à jour d'une commande
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $order = $this->orderManager->find($id);
            if ($order) {
                $order->setStatus($_POST['status']); // Met à jour le statut
                if ($this->orderManager->update($order)) {
                    return $this->redirect('/orders');
                } else {
                    return $this->render('errors/500.html.twig');
                }
            }
        }

        // Afficher le formulaire de mise à jour si la requête est GET
        $order = $this->orderManager->find($id);
        return $this->render('orders/update.html.twig', ['order' => $order]);
    }

    // Supprimer une commande
    public function deleteOrder(int $id)
    {
        if ($this->orderManager->delete($id)) {
            return $this->redirect('/orders');
        } else {
            return $this->render('errors/500.html.twig');
        }
    }
}
