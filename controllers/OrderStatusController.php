<?php

namespace App\Controllers;

use App\Managers\OrderStatusUpdateManager; // Assure-toi d'importer correctement le manager
use App\Controllers\AbstractController;    // Assure-toi d'importer correctement l'AbstractController

class OrderStatusController extends AbstractController
{
    /**
     * Action pour lister tous les statuts de commande
     */
    public function findAll()
    {
        // Instanciation du manager directement dans la méthode
        $orderStatusUpdateManager = new OrderStatusUpdateManager();

        // Récupération de tous les statuts via le manager
        $statuses = $orderStatusUpdateManager->findAll();

        // Affichage de la vue avec Twig en utilisant $this->render hérité d'AbstractController
        return $this->render('orderstatus/index.html.twig', ['statuses' => $statuses]);
    }

    /**
     * Action pour créer un nouveau statut de commande
     */
    public function create()
    {
        $orderStatusUpdateManager = new OrderStatusUpdateManager();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'];
            $status = $_POST['status'];

            if (!empty($orderId) && !empty($status)) {
                echo "Données reçues : Order ID = $orderId, Statut = $status <br>";

                // Appel du manager pour créer le statut
                if ($orderStatusUpdateManager->createOrderStatus($orderId, $status)) {
                   // Redirection après création
                   return $this->redirect('/orderstatus');
                } else {
                    echo "Erreur lors de l'appel au manager.<br>";
                }
            } else {
                echo "Les champs sont vides.<br>";
            }
        }

        // Afficher le formulaire de création avec Twig
        return $this->render('orderstatus/create.html.twig', [
            'status' => null
        ]);
    }

    /**
     * Action pour mettre à jour un statut de commande
     */
    public function update($orderId)
    {
        // Instanciation du manager directement dans la méthode
        $orderStatusUpdateManager = new OrderStatusUpdateManager();

        // Debug de l'orderId reçu dans l'URL
        var_dump("Order ID reçu : ", $orderId);

        // Vérifier si les données POST sont soumises pour mise à jour
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $_POST['status'];

            // Debug du statut envoyé via le formulaire
            var_dump("Statut reçu via POST : ", $status);

            // Validation basique des données
            if (!empty($status)) {
                // Appel du manager pour mise à jour
                if ($orderStatusUpdateManager->updateOrderStatus($orderId, $status)) {
                    // Debug : confirmation de la mise à jour réussie
                    var_dump("Mise à jour réussie pour l'ID : ", $orderId);

                    // Rediriger après mise à jour
                    return $this->redirect('/orderstatus/update/' . $orderId);
                } else {
                    echo "Erreur lors de la mise à jour du statut.";
                }
            } else {
                echo "Veuillez remplir le champ de statut.";
            }
        }

        // Récupérer le statut existant pour le formulaire de mise à jour
        $statusUpdate = $orderStatusUpdateManager->find($orderId);

        // Debug du statut récupéré avant d'afficher la vue
        var_dump("Statut récupéré pour l'ID : ", $statusUpdate);

        // Afficher la vue de mise à jour avec Twig
       return $this->render('orderstatus/update.html.twig', ['statusUpdate' => $statusUpdate]);
    }

    /**
     * Action pour supprimer un statut de commande
     */
    public function delete($orderId): bool
    {
        // Débogage pour voir si l'order_id est bien récupéré
        error_log("Order ID reçu dans la méthode delete : " . $orderId);
        
        // Vérifier si la méthode est POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Instanciation du manager directement dans la méthode
            $orderStatusUpdateManager = new OrderStatusUpdateManager();

            // Appel du manager pour supprimer le statut
            if ($orderStatusUpdateManager->deleteOrderStatus($orderId)) {
                error_log("Suppression réussie pour l'ID : " . $orderId); // Log la suppression
                // Rediriger après suppression
                $this->redirect('/orderstatus');
                
                return true; // Retourner true en cas de succès
            } else {
                error_log("Erreur lors de la suppression de l'ID : " . $orderId);
                return false; // Retourner false en cas d'erreur
            }
        } else {
            error_log("Méthode non autorisée");
            // Si la méthode n'est pas POST, afficher un message d'erreur
            return false; // Retourner false si la méthode n'est pas POST
        }
    }

}
