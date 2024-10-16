<?php

class OrderItemManager extends AbstractManager
{
    // Méthode pour ajouter un nouvel article à une commande
    public function createOrderItem(int $orderId, int $productId, int $quantity, float $price): bool
    {
        try {
            // Préparer la requête SQL pour insérer l'article dans la commande
            $query = "INSERT INTO orders_items (order_id, product_id, quantity, price) 
                      VALUES (:order_id, :product_id, :quantity, :price)";
            $stmt = $this->pdo->prepare($query);
            
            // Lier les paramètres à la requête
            $stmt->bindValue(':order_id', $orderId, PDO::PARAM_INT);
            $stmt->bindValue(':product_id', $productId, PDO::PARAM_INT);
            $stmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->bindValue(':price', $price, PDO::PARAM_STR);
            
            // Exécuter la requête
            return $stmt->execute();
        } catch (\PDOException $e) {
            // Enregistrer l'erreur et renvoyer une exception
            error_log($e->getMessage());
            throw new \Exception("Erreur lors de l'ajout de l'article à la commande.");
        }
    }

    // Méthode pour récupérer tous les articles d'une commande
    public function findAllOrderItemsByOrderId(int $orderId): array
    {
        try {
            // Préparer la requête SQL pour récupérer les articles d'une commande
            $query = "SELECT oi.*, p.name FROM orders_items oi 
                      JOIN products p ON oi.product_id = p.id 
                      WHERE oi.order_id = :order_id";
            $stmt = $this->pdo->prepare($query);
            
            // Lier l'ID de la commande à la requête
            $stmt->bindValue(':order_id', $orderId, PDO::PARAM_INT);
            $stmt->execute();
            
            // Retourner les résultats sous forme de tableau associatif
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("Erreur lors de la récupération des articles de la commande.");
        }
    }

    // Méthode pour mettre à jour un article d'une commande
    public function updateOrderItem(int $id, int $quantity, float $price): bool
    {
        try {
            // Préparer la requête SQL pour mettre à jour un article
            $query = "UPDATE orders_items 
                      SET quantity = :quantity, price = :price 
                      WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            
            // Lier les valeurs aux paramètres
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->bindValue(':price', $price, PDO::PARAM_STR);
            
            // Exécuter la requête
            return $stmt->execute();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("Erreur lors de la mise à jour de l'article de la commande.");
        }
    }

    // Méthode pour supprimer un article d'une commande
    public function deleteOrderItem(int $id): bool
    {
        try {
            // Préparer la requête SQL pour supprimer un article de la commande
            $query = "DELETE FROM orders_items WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            
            // Lier l'ID de l'article à supprimer
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            
            // Exécuter la requête
            return $stmt->execute();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("Erreur lors de la suppression de l'article de la commande.");
        }
    }

    // Méthode pour récupérer un article spécifique d'une commande
    public function findOrderItemById(int $id): ?array
    {
        try {
            // Préparer la requête SQL pour récupérer un article par son ID
            $query = "SELECT * FROM orders_items WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            
            // Lier l'ID de l'article à la requête
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            // Récupérer l'article
            $orderItem = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Retourner l'article ou null s'il n'est pas trouvé
            return $orderItem ? $orderItem : null;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("Erreur lors de la récupération de l'article de la commande.");
        }
    }
}
