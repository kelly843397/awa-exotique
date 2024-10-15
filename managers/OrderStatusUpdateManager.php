<?php

class OrderStatusUpdateManager extends AbstractManager
{
    // Méthode pour récupérer toutes les mises à jour de Orderstatus
    public function findAll(): array
    {
        $query = $this->pdo->prepare('SELECT * FROM orders_status_updates');
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $statusUpdates = [];

        foreach ($result as $item) {
            $statusUpdate = new OrderStatusUpdate(
                (int) $item['order_id'],  // Conversion en entier
                $item['status'],
                $item['updated_at'],
                (int) $item['id']         // Conversion en entier
            );
            $statusUpdates[] = $statusUpdate;
        }

        return $statusUpdates;
    }

    // Méthode pour récupérer une  mise à jour de Orderstatus
    public function find(int $orderId): ?OrderStatusUpdate
    {
        $query = $this->pdo->prepare('SELECT * FROM orders_status_updates WHERE order_id = :orderId');
        $query->bindValue(':orderId', $orderId, PDO::PARAM_INT);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return new OrderStatusUpdate(
                $result['order_id'],       // Assure-toi que 'order_id' est bien un entier dans ta base de données
                $result['status'],
                $result['updated_at'],
                isset($result['id']) ? (int) $result['id'] : null  // Conversion explicite de l'ID en entier
            );
        }

        return null;
    }

    // Méthode pour créer une nouvelle mise à jour de statut pour une commande
    public function createOrderStatus(int $orderId, string $status): bool
    {
        try {
            // Requête SQL pour insérer une nouvelle mise à jour de statut
            $query = 'INSERT INTO orders_status_updates (order_id, status, updated_at) VALUES (:orderId, :status, NOW())';

            // Préparation de la requête
            $stmt = $this->pdo->prepare($query);

            // Liaison des paramètres
            $stmt->bindValue(':orderId', $orderId, PDO::PARAM_INT);
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);

            // Exécuter la requête et retourner true si l'insertion a réussi
            return $stmt->execute();
        } catch (\PDOException $e) {
            // En cas d'erreur, retourner false
            return false;
        }
    }


    // Méthode pour mettre à jour le statut d'une commande OrderStatus
    public function updateOrderStatus(int $orderId, string $status): bool
    {
        try {
            // Requête SQL pour mettre à jour le statut d'une commande par son order_id
            $query = 'UPDATE orders_status_updates SET status = :status, updated_at = NOW() WHERE order_id = :orderId';

            // Préparation de la requête
            $stmt = $this->pdo->prepare($query);

            // Liaison des paramètres
            $stmt->bindValue(':orderId', $orderId, PDO::PARAM_INT);
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);

            // Exécuter la requête et retourner true si la mise à jour a réussi
            return $stmt->execute();
        } catch (\PDOException $e) {
            // En cas d'erreur, retourner false
            return false;
        }
    }

    // Méthode pour supprimer la mise à jour d'une commande OrderStatus
    public function deleteOrderStatus(int $orderId): bool
    {
        try {
            // Requête SQL pour supprimer une mise à jour de statut par son ID
            $query = 'DELETE FROM orders_status_updates WHERE order_id = :orderId';

            // Préparation de la requête
            $stmt = $this->pdo->prepare($query);

            // Liaison du paramètre
            $stmt->bindValue(':orderId', $orderId, \PDO::PARAM_INT);

            // Exécuter la requête et retourner true si la suppression a réussi
            return $stmt->execute();
        } catch (\PDOException $e) {
            // En cas d'erreur, retourner false
            return false;
        }
    }
}
