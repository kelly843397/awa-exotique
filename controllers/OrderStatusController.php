<?php

class OrderStatusController
{
    private $orderStatusUpdateManager;

    // Constructeur pour instancier le manager
    public function __construct()
    {
        $this->orderStatusUpdateManager = new OrderStatusUpdateManager();
    }

    /**
     * Action pour lister tous les statuts de commande
     */
    public function index()
    {
        // Récupération de tous les statuts via le manager
        $statuses = $this->orderStatusUpdateManager->findAll();

        // Vérification si des statuts existent
        if (!empty($statuses)) {
            // Inclure une vue pour afficher les statuts (à adapter à ton projet)
            include 'views/statuses/index.php';
        } else {
            echo "Aucun statut trouvé.";
        }
    }

    /**
     * Action pour créer un nouveau statut de commande
     */
    public function create()
    {
        // Vérifier si les données POST sont soumises (par exemple depuis un formulaire)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'];
            $status = $_POST['status'];

            // Validation basique des données
            if (isset($orderId, $status) && !empty($orderId) && !empty($status)) {
                // Appel du manager pour créer le statut
                if ($this->orderStatusUpdateManager->createOrderStatus($orderId, $status)) {
                    // Rediriger après création (adapter selon ta logique)
                    header('Location: index.php?controller=orderstatus&action=index');
                } else {
                    echo "Erreur lors de la création du statut.";
                }
            } else {
                echo "Veuillez remplir tous les champs.";
            }
        } else {
            // Inclure une vue pour afficher le formulaire de création
            include 'views/statuses/create.php';
        }
    }

    /**
     * Action pour mettre à jour un statut de commande
     */
    public function update($orderId)
    {
        // Vérifier si les données POST sont soumises pour mettre à jour
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $_POST['status'];

            // Validation basique des données
            if (isset($status) && !empty($status)) {
                // Appel du manager pour mettre à jour le statut
                if ($this->orderStatusUpdateManager->updateOrderStatus($orderId, $status)) {
                    // Rediriger après mise à jour (adapter selon ta logique)
                    header('Location: index.php?controller=orderstatus&action=index');
                } else {
                    echo "Erreur lors de la mise à jour du statut.";
                }
            } else {
                echo "Veuillez remplir le champ de statut.";
            }
        } else {
            // Inclure une vue pour afficher le formulaire de mise à jour
            include 'views/statuses/update.php';
        }
    }

    /**
     * Action pour supprimer un statut de commande
     */
    public function delete($orderId)
    {
        // Appel du manager pour supprimer le statut
        if ($this->orderStatusUpdateManager->deleteOrderStatus($orderId)) {
            // Rediriger après suppression (adapter selon ta logique)
            header('Location: index.php?controller=orderstatus&action=index');
        } else {
            echo "Erreur lors de la suppression du statut.";
        }
    }
}
