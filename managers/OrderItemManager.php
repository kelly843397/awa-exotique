<?php

class OrderItemManager extends AbstractManager
{
    // Le constructeur accepte un objet PDO
    public function __construct(PDO $pdo)
    {
        // Utilise le PDO fourni pour la connexion
        $this->pdo = $pdo;
    }

    /**
     * Crée un nouvel élément de commande.
     *
     * @param OrderItem $orderItem L'objet OrderItem à insérer.
     * @return int L'ID du nouvel élément inséré.
     */
    public function create(OrderItem $orderItem): int
    {
        $query = $this->pdo->prepare(
            'INSERT INTO orders_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)'
        );

        $parameters = [
            'order_id' => $orderItem->getOrderId(),
            'product_id' => $orderItem->getProductId(),
            'quantity' => $orderItem->getQuantity(),
            'price' => $orderItem->getPrice(),
        ];

        $query->execute($parameters);
        return $this->pdo->lastInsertId();
    }

    /**
     * Récupère un élément de commande par son ID.
     *
     * @param int $id L'identifiant de l'élément de commande à récupérer.
     * @return OrderItem|null L'objet OrderItem trouvé ou null s'il n'existe pas.
     */
    public function findOne(int $id): ?OrderItem
    {
        $query = $this->pdo->prepare('SELECT * FROM orders_items WHERE id = :id');
        $query->execute(['id' => $id]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return new OrderItem(
                $result['order_id'],
                $result['product_id'],
                $result['quantity'],
                $result['price'],
                $result['id']
            );
        }

        return null;
    }

    /**
     * Récupère tous les éléments de commande.
     *
     * @return array La liste des éléments de commande.
     */
    public function findAll(): array
    {
        $query = $this->pdo->prepare('SELECT * FROM orders_items');
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $orderItems = [];

        foreach ($results as $item) {
            $orderItems[] = new OrderItem(
                $item['order_id'],
                $item['product_id'],
                $item['quantity'],
                $item['price'],
                $item['id']
            );
        }

        return $orderItems;
    }

    /**
     * Met à jour un élément de commande.
     *
     * @param OrderItem $orderItem L'objet OrderItem à mettre à jour.
     * @return bool Résultat de l'opération de mise à jour.
     */
    public function update(OrderItem $orderItem): bool
    {
        $query = $this->pdo->prepare(
            'UPDATE orders_items SET order_id = :order_id, product_id = :product_id, quantity = :quantity, price = :price WHERE id = :id'
        );

        $parameters = [
            'order_id' => $orderItem->getOrderId(),
            'product_id' => $orderItem->getProductId(),
            'quantity' => $orderItem->getQuantity(),
            'price' => $orderItem->getPrice(),
            'id' => $orderItem->getId(),
        ];

        return $query->execute($parameters);
    }

    /**
     * Supprime un élément de commande.
     *
     * @param int $id L'identifiant de l'élément de commande à supprimer.
     * @return bool Résultat de l'opération de suppression.
     */
    public function delete(int $id): bool
    {
        $query = $this->pdo->prepare('DELETE FROM orders_items WHERE id = :id');
        return $query->execute(['id' => $id]);
    }
}
