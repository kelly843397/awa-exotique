<?php

class OrderManager extends AbstractManager
{
    // Méthode pour créer une nouvelle commande
    public function createOrder(int $userId, array $orderItems): bool
    {
        try {
            // Démarrer une transaction pour garantir l'intégrité des données
            $this->pdo->beginTransaction();
    
            // Requête SQL pour insérer une nouvelle commande dans la table `orders`
            $queryOrder = "INSERT INTO orders (user_id, order_date, status) VALUES (:user_id, NOW(), 'pending')";
            $stmtOrder = $this->pdo->prepare($queryOrder);
            $stmtOrder->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmtOrder->execute();
    
            // Récupérer l'ID de la commande nouvellement créée
            $orderId = $this->pdo->lastInsertId();
    
            // Vérifier que chaque product_id existe dans la table `products`
            $queryCheckProduct = "SELECT id FROM products WHERE id = :product_id";
            $stmtCheckProduct = $this->pdo->prepare($queryCheckProduct);
    
            // Insérer les articles de la commande dans la table `orders_items`
            $queryItem = "INSERT INTO orders_items (order_id, product_id, quantity) VALUES (:order_id, :product_id, :quantity)";
            $stmtItem = $this->pdo->prepare($queryItem);
    
            foreach ($orderItems as $item) {
                // Vérifier si le produit existe
                $stmtCheckProduct->bindValue(':product_id', $item['product_id'], PDO::PARAM_INT);
                $stmtCheckProduct->execute();
                $productExists = $stmtCheckProduct->fetch(PDO::FETCH_ASSOC);
    
                if (!$productExists) {
                    throw new PDOException("Le produit avec l'ID {$item['product_id']} n'existe pas.");
                }
    
                // Insérer l'article si le produit existe
                $stmtItem->bindValue(':order_id', $orderId, PDO::PARAM_INT);
                $stmtItem->bindValue(':product_id', $item['product_id'], PDO::PARAM_INT);
                $stmtItem->bindValue(':quantity', $item['quantity'], PDO::PARAM_INT);
                $stmtItem->execute();
            }
    
            // Confirmer la transaction
            $this->pdo->commit();
    
            return true;
        } catch (PDOException $e) {
            // Afficher l'erreur exacte
            echo "Erreur SQL : " . $e->getMessage();
            $this->pdo->rollBack();
            return false;
        }
    }

    // Méthode pour récupérer une commande par son ID
    public function getOrderById(int $orderId): ?array
    {
        try {
            // Requête SQL pour récupérer les détails de la commande
            $queryOrder = "SELECT * FROM orders WHERE id = :order_id";
            $stmtOrder = $this->pdo->prepare($queryOrder);
            $stmtOrder->bindValue(':order_id', $orderId, PDO::PARAM_INT);
            $stmtOrder->execute();
            $order = $stmtOrder->fetch(PDO::FETCH_ASSOC);

            // Si la commande n'existe pas, retourner null
            if (!$order) {
                return null;
            }

            // Requête SQL pour récupérer les articles associés à la commande
            $queryItems = "SELECT oi.*, p.name, p.price FROM orders_items oi
                           JOIN products p ON oi.product_id = p.id
                           WHERE oi.order_id = :order_id";
            $stmtItems = $this->pdo->prepare($queryItems);
            $stmtItems->bindValue(':order_id', $orderId, PDO::PARAM_INT);
            $stmtItems->execute();
            $orderItems = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

            // Ajouter les articles récupérés dans la commande
            $order['items'] = $orderItems;

            return $order;  // Retourner les détails de la commande avec ses articles
        } catch (PDOException $e) {
            echo "Erreur SQL : " . $e->getMessage();
            return null;
        }
    }

    // Méthode pour récupérer toutes les commandes avec leurs articles associés
    public function getAllOrders(): array
    {
        try {
            // Requête SQL pour récupérer toutes les commandes
            $queryOrders = "SELECT * FROM orders";
            $stmtOrders = $this->pdo->prepare($queryOrders);
            $stmtOrders->execute();
            $orders = $stmtOrders->fetchAll(PDO::FETCH_ASSOC);

            // Si aucune commande n'est trouvée, retourner un tableau vide
            if (!$orders) {
                return [];
            }

            // Parcourir chaque commande pour récupérer ses articles
            foreach ($orders as &$order) {
                $queryItems = "SELECT oi.*, p.name, p.price FROM orders_items oi
                               JOIN products p ON oi.product_id = p.id
                               WHERE oi.order_id = :order_id";
                $stmtItems = $this->pdo->prepare($queryItems);
                $stmtItems->bindValue(':order_id', $order['id'], PDO::PARAM_INT);
                $stmtItems->execute();
                $orderItems = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

                // Ajouter les articles à la commande
                $order['items'] = $orderItems;
            }

            return $orders;  // Retourner toutes les commandes avec leurs articles
        } catch (PDOException $e) {
            echo "Erreur SQL : " . $e->getMessage();
            return [];
        }
    }

    // Méthode pour mettre à jour le statut d'une commande
    public function updateOrderStatus(int $orderId, string $status): bool
    {
        try {
            // Requête SQL pour mettre à jour le statut d'une commande spécifique
            $query = "UPDATE orders SET status = :status WHERE id = :order_id";
            $stmt = $this->pdo->prepare($query);
            
            // Lier les valeurs des paramètres
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);
            $stmt->bindValue(':order_id', $orderId, PDO::PARAM_INT);

            // Exécuter la requête
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur SQL : " . $e->getMessage();
            return false;
        }
    }

    // Méthode pour supprimer une commande et les articles associés
    public function deleteOrder(int $orderId): bool
    {
        try {
            // Démarrer une transaction pour garantir l'intégrité des données
            $this->pdo->beginTransaction();

            // Supprimer les articles associés à la commande dans la table `orders_items`
            $queryDeleteItems = "DELETE FROM orders_items WHERE order_id = :order_id";
            $stmtDeleteItems = $this->pdo->prepare($queryDeleteItems);
            $stmtDeleteItems->bindValue(':order_id', $orderId, PDO::PARAM_INT);
            $stmtDeleteItems->execute();

            // Supprimer la commande dans la table `orders`
            $queryDeleteOrder = "DELETE FROM orders WHERE id = :order_id";
            $stmtDeleteOrder = $this->pdo->prepare($queryDeleteOrder);
            $stmtDeleteOrder->bindValue(':order_id', $orderId, PDO::PARAM_INT);
            $stmtDeleteOrder->execute();

            // Confirmer la transaction
            $this->pdo->commit();

            return true;
        } catch (PDOException $e) {
            // En cas d'erreur, annuler la transaction
            $this->pdo->rollBack();
            echo "Erreur SQL : " . $e->getMessage();
            return false;
        }
    }

    // Méthode pour récupérer toutes les commandes d'un utilisateur avec leurs articles associés
    public function getOrdersByUserId(int $userId): array
    {
        try {
            // Requête SQL pour récupérer toutes les commandes d'un utilisateur spécifique
            $queryOrders = "SELECT * FROM orders WHERE user_id = :user_id";
            $stmtOrders = $this->pdo->prepare($queryOrders);
            $stmtOrders->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmtOrders->execute();
            $orders = $stmtOrders->fetchAll(PDO::FETCH_ASSOC);

            // Si aucune commande n'est trouvée, retourner un tableau vide
            if (!$orders) {
                return [];
            }

            // Parcourir chaque commande pour récupérer ses articles
            foreach ($orders as &$order) {
                $queryItems = "SELECT oi.*, p.name, p.price FROM orders_items oi
                               JOIN products p ON oi.product_id = p.id
                               WHERE oi.order_id = :order_id";
                $stmtItems = $this->pdo->prepare($queryItems);
                $stmtItems->bindValue(':order_id', $order['id'], PDO::PARAM_INT);
                $stmtItems->execute();
                $orderItems = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

                // Ajouter les articles à la commande
                $order['items'] = $orderItems;
            }

            return $orders;  // Retourner toutes les commandes de l'utilisateur avec leurs articles
        } catch (PDOException $e) {
            echo "Erreur SQL : " . $e->getMessage();
            return [];
        }
    }

    // Méthode pour ajouter un nouvel article à une commande existante
    public function addOrderItem(int $orderId, int $productId, int $quantity): bool
    {
        try {
            // Vérifier si la commande existe
            $queryCheckOrder = "SELECT id FROM orders WHERE id = :order_id";
            $stmtCheckOrder = $this->pdo->prepare($queryCheckOrder);
            $stmtCheckOrder->bindValue(':order_id', $orderId, PDO::PARAM_INT);
            $stmtCheckOrder->execute();
            $orderExists = $stmtCheckOrder->fetch(PDO::FETCH_ASSOC);

            if (!$orderExists) {
                throw new PDOException("La commande avec l'ID $orderId n'existe pas.");
            }

            // Vérifier si le produit existe
            $queryCheckProduct = "SELECT id FROM products WHERE id = :product_id";
            $stmtCheckProduct = $this->pdo->prepare($queryCheckProduct);
            $stmtCheckProduct->bindValue(':product_id', $productId, PDO::PARAM_INT);
            $stmtCheckProduct->execute();
            $productExists = $stmtCheckProduct->fetch(PDO::FETCH_ASSOC);

            if (!$productExists) {
                throw new PDOException("Le produit avec l'ID $productId n'existe pas.");
            }

            // Requête SQL pour ajouter un article dans `orders_items`
            $query = "INSERT INTO orders_items (order_id, product_id, quantity) 
                      VALUES (:order_id, :product_id, :quantity)";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':order_id', $orderId, PDO::PARAM_INT);
            $stmt->bindValue(':product_id', $productId, PDO::PARAM_INT);
            $stmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);

            // Exécuter la requête
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur SQL : " . $e->getMessage();
            return false;
        }
    }

    // Méthode pour mettre à jour la quantité d'un article dans une commande existante
    public function updateOrderItem(int $orderId, int $productId, int $quantity): bool
    {
        try {
            // Vérifier si l'article existe dans la commande
            $queryCheckItem = "SELECT * FROM orders_items WHERE order_id = :order_id AND product_id = :product_id";
            $stmtCheckItem = $this->pdo->prepare($queryCheckItem);
            $stmtCheckItem->bindValue(':order_id', $orderId, PDO::PARAM_INT);
            $stmtCheckItem->bindValue(':product_id', $productId, PDO::PARAM_INT);
            $stmtCheckItem->execute();
            $itemExists = $stmtCheckItem->fetch(PDO::FETCH_ASSOC);

            if (!$itemExists) {
                throw new PDOException("L'article avec le produit ID $productId n'existe pas dans la commande ID $orderId.");
            }

            // Requête SQL pour mettre à jour la quantité d'un article dans la table `orders_items`
            $query = "UPDATE orders_items SET quantity = :quantity WHERE order_id = :order_id AND product_id = :product_id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->bindValue(':order_id', $orderId, PDO::PARAM_INT);
            $stmt->bindValue(':product_id', $productId, PDO::PARAM_INT);

            // Exécuter la requête
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur SQL : " . $e->getMessage();
            return false;
        }
    }
    
    // Méthode pour supprimer un article spécifique d'une commande
    public function deleteOrderItem(int $orderId, int $productId): bool
    {
        try {
            // Vérifier si l'article existe dans la commande
            $queryCheckItem = "SELECT * FROM orders_items WHERE order_id = :order_id AND product_id = :product_id";
            $stmtCheckItem = $this->pdo->prepare($queryCheckItem);
            $stmtCheckItem->bindValue(':order_id', $orderId, PDO::PARAM_INT);
            $stmtCheckItem->bindValue(':product_id', $productId, PDO::PARAM_INT);
            $stmtCheckItem->execute();
            $itemExists = $stmtCheckItem->fetch(PDO::FETCH_ASSOC);

            if (!$itemExists) {
                throw new PDOException("L'article avec le produit ID $productId n'existe pas dans la commande ID $orderId.");
            }

            // Requête SQL pour supprimer l'article de la table `orders_items`
            $query = "DELETE FROM orders_items WHERE order_id = :order_id AND product_id = :product_id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':order_id', $orderId, PDO::PARAM_INT);
            $stmt->bindValue(':product_id', $productId, PDO::PARAM_INT);

            // Exécuter la requête
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur SQL : " . $e->getMessage();
            return false;
        }
    }
}