<?php

class OrderStatusUpdateManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    // Méthode pour récupérer toutes les mises à jour de Orderstatus
    public function findAll(): array
    {
        $query = $this->db->prepare('SELECT * FROM orders_status_updates');
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $statusUpdates = [];

        foreach ($result as $item) {
            $statusUpdate = new OrderStatusUpdate(
                $item['id'],
                $item['order_id'],
                $item['status'],
                $item['updated_at']
            );
            $statusUpdates[] = $statusUpdate;
        }

        return $statusUpdates;
    }

    // Méthode pour ajouter une nouvelle mise à jour de Orderstatus
    public function addStatusUpdate(OrderStatusUpdate $statusUpdate): void
    {
        $query = $this->db->prepare('INSERT INTO orders_status_updates (order_id, status, updated_at) VALUES (:orderId, :status, :updatedAt)');
        $query->bindValue(':orderId', $statusUpdate->getOrderId());
        $query->bindValue(':status', $statusUpdate->getStatus());
        $query->bindValue(':updatedAt', $statusUpdate->getUpdatedAt());
        $query->execute();

        // Met à jour l'ID de l'objet après insertion
        $statusUpdate->setId((int) $this->db->lastInsertId());
    }
}
