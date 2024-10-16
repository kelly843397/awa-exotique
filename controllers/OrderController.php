<?php

class OrderController extends AbstractController
{
    // Afficher toutes les commandes
    public function index(): void
    {
        $orderManager = new OrderManager();
        try {
            $orders = $orderManager->findAll();
            $this->render('order/index.html.twig', ['orders' => $orders]);
        } catch (\Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    // Afficher une commande spécifique par son ID
    public function show(int $id): void
    {
        $orderManager = new OrderManager();
        try {
            $order = $orderManager->find($id);

            if (!$order) {
                $this->redirect('/404'); // Redirection si la commande n'est pas trouvée
            }

            $this->render('order/show.html.twig', ['order' => $order]);
        } catch (\Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    // Créer une nouvelle commande
    public function create(): void
    {
        // Redirection si l'utilisateur n'est pas authentifié
        $this->redirectIfNotAuthenticated();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfManager = new CSRFTokenManager();
            if (!$csrfManager->validateCSRFToken($_POST['csrf_token'] ?? '')) {
                die("Token CSRF invalide.");
            }

            $userId = $_SESSION['user']['id'];  // ID de l'utilisateur connecté
            $orderManager = new OrderManager();

            try {
                $orderManager->create($userId);
                $this->redirect('/orders'); // Redirection après création
            } catch (\Exception $e) {
                echo "Erreur : " . $e->getMessage();
            }
        }

        $this->render('order/create.html.twig');
    }

    // Mettre à jour le statut d'une commande
    public function edit(int $id): void
    {
        // Redirection si l'utilisateur n'est pas administrateur
        $this->redirectIfNotAdmin();

        $orderManager = new OrderManager();
        try {
            $order = $orderManager->find($id);

            if (!$order) {
                $this->redirect('/404'); // Redirection si la commande n'est pas trouvée
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $csrfManager = new CSRFTokenManager();
                if (!$csrfManager->validateCSRFToken($_POST['csrf_token'] ?? '')) {
                    die("Token CSRF invalide.");
                }

                $status = $_POST['status'] ?? '';
                $orderManager->update($id, $status);
                $this->redirect('/orders'); // Redirection après mise à jour
            }

            $this->render('order/edit.html.twig', ['order' => $order]);
        } catch (\Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    // Supprimer une commande
    public function delete(int $id): void
    {
        // Redirection si l'utilisateur n'est pas administrateur
        $this->redirectIfNotAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfManager = new CSRFTokenManager();
            if (!$csrfManager->validateCSRFToken($_POST['csrf_token'] ?? '')) {
                die("Token CSRF invalide.");
            }

            $orderManager = new OrderManager();

            try {
                $orderManager->delete($id);
                $this->redirect('/orders'); // Redirection après suppression
            } catch (\Exception $e) {
                echo "Erreur : " . $e->getMessage();
            }
        }

        $this->render('order/delete.html.twig', ['id' => $id]);
    }
}
