<?php

class OrderStatusController extends AbstractController
{
    // Afficher toutes les mises à jour de statut de commande
    public function index(): void
    {
        $orderStatusManager = new OrderStatusUpdateManager();
        $statusUpdates = $orderStatusManager->findAll();

        $this->render('order_status/index.html.twig', ['statusUpdates' => $statusUpdates]);
    }

    // Créer une nouvelle mise à jour de statut de commande
    public function create(int $orderId): void
    {
        // Redirection si l'utilisateur n'est pas administrateur
        $this->redirectIfNotAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfManager = new CSRFTokenManager();
            if (!$csrfManager->validateCSRFToken($_POST['csrf_token'] ?? '')) {
                die("Token CSRF invalide.");
            }

            $status = $_POST['status'] ?? '';

            $orderStatusManager = new OrderStatusUpdateManager();

            try {
                $orderStatusManager->createOrderStatus($orderId, $status);
                $this->redirect('/orders/' . $orderId . '/status'); // Redirection après création
            } catch (\Exception $e) {
                echo "Erreur : " . $e->getMessage();
            }
        }

        $this->render('order_status/create.html.twig', ['orderId' => $orderId]);
    }

    // Mettre à jour une mise à jour de statut de commande
    public function edit(int $orderId): void
    {
        // Redirection si l'utilisateur n'est pas administrateur
        $this->redirectIfNotAdmin();

        $orderStatusManager = new OrderStatusUpdateManager();
        $statusUpdate = $orderStatusManager->find($orderId);

        if (!$statusUpdate) {
            $this->redirect('/404'); // Redirection si la mise à jour de statut n'est pas trouvée
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfManager = new CSRFTokenManager();
            if (!$csrfManager->validateCSRFToken($_POST['csrf_token'] ?? '')) {
                die("Token CSRF invalide.");
            }

            $status = $_POST['status'] ?? '';

            try {
                $orderStatusManager->updateOrderStatus($orderId, $status);
                $this->redirect('/orders/' . $orderId . '/status'); // Redirection après mise à jour
            } catch (\Exception $e) {
                echo "Erreur : " . $e->getMessage();
            }
        }

        $this->render('order_status/edit.html.twig', ['statusUpdate' => $statusUpdate]);
    }

    // Supprimer une mise à jour de statut de commande
    public function delete(int $orderId): void
    {
        // Redirection si l'utilisateur n'est pas administrateur
        $this->redirectIfNotAdmin();

        $orderStatusManager = new OrderStatusUpdateManager();
        $statusUpdate = $orderStatusManager->find($orderId);

        if (!$statusUpdate) {
            $this->redirect('/404'); // Redirection si la mise à jour de statut n'est pas trouvée
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfManager = new CSRFTokenManager();
            if (!$csrfManager->validateCSRFToken($_POST['csrf_token'] ?? '')) {
                die("Token CSRF invalide.");
            }

            try {
                $orderStatusManager->deleteOrderStatus($orderId);
                $this->redirect('/orders/' . $orderId . '/status'); // Redirection après suppression
            } catch (\Exception $e) {
                echo "Erreur : " . $e->getMessage();
            }
        }

        $this->render('order_status/delete.html.twig', ['orderId' => $orderId]);
    }
}
