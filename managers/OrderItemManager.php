<?php

class OrderItemManager extends AbstractManager
{
    // Méthode pour récupérer tous les éléments de commande
    public function findAll(): array
    {
        $query = $this->pdo->prepare('SELECT * FROM orders_items');
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $orderItems = [];

        foreach ($result as $item) {
            $orderItem = new OrderItem(
                $item['id'],
                $item['order_id'],
                $item['product_id'],
                $item['quantity'],
                $item['price']  // Ajout de la donnée "price"
            );
            $orderItems[] = $orderItem;
        }

        return $orderItems;
    }

    // Méthode pour ajouter un nouvel élément de commande
    public function addOrderItem(OrderItem $orderItem): bool
    {
        try {
            // Requête SQL incluant la colonne "price"
            $query = $this->pdo->prepare('INSERT INTO orders_items (order_id, product_id, quantity, price) 
                                        VALUES (:orderId, :productId, :quantity, :price)');

            // Liaison des valeurs
            $query->bindValue(':orderId', $orderItem->getOrderId(), PDO::PARAM_INT);
            $query->bindValue(':productId', $orderItem->getProductId(), PDO::PARAM_INT);
            $query->bindValue(':quantity', $orderItem->getQuantity(), PDO::PARAM_INT);
            $query->bindValue(':price', $orderItem->getPrice(), PDO::PARAM_STR);  // Liaison du prix

            // Exécution de la requête et vérification de la réussite
            if ($query->execute()) {
                // Met à jour l'ID de l'objet après insertion
                $orderItem->setId((int) $this->pdo->lastInsertId());
                return true; // Retourne true si l'insertion a réussi
            } else {
                return false; // Retourne false en cas d'échec
            }
        } catch (PDOException $e) {
            // En cas d'erreur, retourner false
            return false;
        }
    }

    //Méthode pour mettre à jour une commande
    public function updateOrderItem(OrderItem $orderItem): bool
    {
        try {
            // Requête SQL pour mettre à jour un article de commande
            $query = 'UPDATE orders_items
                    SET order_id = :orderId,
                        product_id = :productId,
                        quantity = :quantity,
                        price = :price
                    WHERE id = :id';

            // Préparer la requête
            $stmt = $this->pdo->prepare($query);

            // Lier les paramètres à la requête SQL
            $stmt->bindValue(':orderId', $orderItem->getOrderId(), PDO::PARAM_INT);
            $stmt->bindValue(':productId', $orderItem->getProductId(), PDO::PARAM_INT);
            $stmt->bindValue(':quantity', $orderItem->getQuantity(), PDO::PARAM_INT);
            $stmt->bindValue(':price', $orderItem->getPrice(), PDO::PARAM_STR);
            $stmt->bindValue(':id', $orderItem->getId(), PDO::PARAM_INT);

            // Exécuter la requête et retourner true si cela a fonctionné
            return $stmt->execute();
        } catch (PDOException $e) {
            // En cas d'erreur, retourner false
            return false;
        }
    }

    public function deleteOrderItem(int $id): bool
    {
        try {
            // Requête SQL pour supprimer l'article de commande par son ID
            $query = $this->pdo->prepare('DELETE FROM orders_items WHERE id = :id');
            
            // Lier l'ID de l'article à supprimer
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            
            // Exécuter la requête et retourner true si la suppression a réussi
            return $query->execute();
        } catch (PDOException $e) {
            // En cas d'erreur, retourner false
            return false;
        }
    }
}
