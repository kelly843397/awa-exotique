<?php

class OrderItemManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    // Méthode pour récupérer tous les éléments de commande
    public function findAll(): array
    {
        $query = $this->db->prepare('SELECT * FROM orders_items');
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $orderItems = [];

        foreach ($result as $item) {
            $orderItem = new OrderItem(
                $item['id'],
                $item['order_id'],
                $item['product_id'],
                $item['quantity']
            );
            $orderItems[] = $orderItem;
        }

        return $orderItems;
    }

    // Méthode pour ajouter un nouvel élément de commande
    public function addOrderItem(OrderItem $orderItem): void
    {
        $query = $this->db->prepare('INSERT INTO orders_items (order_id, product_id, quantity) VALUES (:orderId, :productId, :quantity)');
        $query->bindValue(':orderId', $orderItem->getOrderId());
        $query->bindValue(':productId', $orderItem->getProductId());
        $query->bindValue(':quantity', $orderItem->getQuantity());
        $query->execute();

        // Met à jour l'ID de l'objet après insertion
        $orderItem->setId((int) $this->db->lastInsertId());
    }
}
