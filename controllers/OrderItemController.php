<?php

class OrderItemController extends AbstractController
{
    // Afficher les articles d'une commande
    public function index(int $orderId): void
    {
        $orderItemManager = new OrderItemManager();
        try {
            $orderItems = $orderItemManager->findAllOrderItemsByOrderId($orderId);
            $this->render('order_items/index.html.twig', ['orderItems' => $orderItems, 'orderId' => $orderId]);
        } catch (\Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    // Créer un nouvel article dans une commande
    public function create(int $orderId): void
    {
        // Redirection si l'utilisateur n'est pas authentifié
        $this->redirectIfNotAuthenticated();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfManager = new CSRFTokenManager();
            if (!$csrfManager->validateCSRFToken($_POST['csrf_token'] ?? '')) {
                die("Token CSRF invalide.");
            }

            $productId = (int)$_POST['product_id'] ?? 0;
            $quantity = (int)$_POST['quantity'] ?? 1;
            $price = (float)$_POST['price'] ?? 0.0;

            $orderItemManager = new OrderItemManager();
            try {
                $orderItemManager->createOrderItem($orderId, $productId, $quantity, $price);
                $this->redirect('/orders/' . $orderId . '/items'); // Redirection après ajout
            } catch (\Exception $e) {
                echo "Erreur : " . $e->getMessage();
            }
        }

        $this->render('order_items/create.html.twig', ['orderId' => $orderId]);
    }

    // Modifier un article dans une commande
    public function edit(int $id): void
    {
        // Redirection si l'utilisateur n'est pas authentifié
        $this->redirectIfNotAuthenticated();

        $orderItemManager = new OrderItemManager();
        try {
            $orderItem = $orderItemManager->findOrderItemById($id);

            if (!$orderItem) {
                $this->redirect('/404'); // Redirection si l'article n'existe pas
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $csrfManager = new CSRFTokenManager();
                if (!$csrfManager->validateCSRFToken($_POST['csrf_token'] ?? '')) {
                    die("Token CSRF invalide.");
                }

                $quantity = (int)$_POST['quantity'] ?? 1;
                $price = (float)$_POST['price'] ?? 0.0;

                $orderItemManager->updateOrderItem($id, $quantity, $price);
                $this->redirect('/orders/' . $orderItem['order_id'] . '/items'); // Redirection après mise à jour
            }

            $this->render('order_items/edit.html.twig', ['orderItem' => $orderItem]);
        } catch (\Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    // Supprimer un article d'une commande
    public function delete(int $id): void
    {
        // Redirection si l'utilisateur n'est pas authentifié
        $this->redirectIfNotAuthenticated();

        $orderItemManager = new OrderItemManager();
        try {
            $orderItem = $orderItemManager->findOrderItemById($id);

            if (!$orderItem) {
                $this->redirect('/404'); // Redirection si l'article n'existe pas
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $csrfManager = new CSRFTokenManager();
                if (!$csrfManager->validateCSRFToken($_POST['csrf_token'] ?? '')) {
                    die("Token CSRF invalide.");
                }

                $orderItemManager->deleteOrderItem($id);
                $this->redirect('/orders/' . $orderItem['order_id'] . '/items'); // Redirection après suppression
            }

            $this->render('order_items/delete.html.twig', ['orderItem' => $orderItem]);
        } catch (\Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }
}
