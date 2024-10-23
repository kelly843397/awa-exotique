<?php

class OrderStatusUpdateManager
{
    private PDO $pdo;

    // Constructeur qui accepte un objet PDO pour la connexion à la base de données
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Crée une nouvelle mise à jour de statut de commande.
     *
     * @param OrderStatusUpdate $orderStatusUpdate L'objet OrderStatusUpdate à insérer.
     * @return int L'ID de la nouvelle mise à jour de statut insérée.
     */
    public function create(OrderStatusUpdate $orderStatusUpdate): int
    {
        $query = $this->pdo->prepare(
            'INSERT INTO orders_status_updates (order_id, status, updated_at) VALUES (:order_id, :status, :updated_at)'
        );

        $parameters = [
            'order_id' => $orderStatusUpdate->getOrderId(),
            'status' => $orderStatusUpdate->getStatus(),
            'updated_at' => $orderStatusUpdate->getUpdatedAt()->format('Y-m-d H:i:s')
        ];

        $query->execute($parameters);
        return $this->pdo->lastInsertId();
    }

    /**
     * Récupère une mise à jour de statut de commande par son ID.
     *
     * @param int $id L'ID de la mise à jour de statut.
     * @return OrderStatusUpdate|null L'objet OrderStatusUpdate trouvé ou null s'il n'existe pas.
     */
    public function findOne(int $id): ?OrderStatusUpdate
    {
        $query = $this->pdo->prepare('SELECT * FROM orders_status_updates WHERE id = :id');
        $query->execute(['id' => $id]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return new OrderStatusUpdate(
                $result['order_id'],
                $result['status'],
                new DateTime($result['updated_at']),
                $result['id']
            );
        }
        return null;
    }

    /**
     * Met à jour une mise à jour de statut de commande.
     *
     * @param OrderStatusUpdate $orderStatusUpdate L'objet OrderStatusUpdate à mettre à jour.
     * @return bool True si la mise à jour a réussi, false sinon.
     */
    public function update(OrderStatusUpdate $orderStatusUpdate): bool
    {
        $query = $this->pdo->prepare(
            'UPDATE orders_status_updates SET order_id = :order_id, status = :status, updated_at = :updated_at WHERE id = :id'
        );

        $parameters = [
            'id' => $orderStatusUpdate->getId(),
            'order_id' => $orderStatusUpdate->getOrderId(),
            'status' => $orderStatusUpdate->getStatus(),
            'updated_at' => $orderStatusUpdate->getUpdatedAt()->format('Y-m-d H:i:s')
        ];

        return $query->execute($parameters);
    }

    /**
     * Supprime une mise à jour de statut de commande par son ID.
     *
     * @param int $id L'ID de la mise à jour de statut à supprimer.
     * @return bool True si la suppression a réussi, false sinon.
     */
    public function delete(int $id): bool
    {
        $query = $this->pdo->prepare('DELETE FROM orders_status_updates WHERE id = :id');
        return $query->execute(['id' => $id]);
    }
}
