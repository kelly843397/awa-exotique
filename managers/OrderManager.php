<?php

class OrderManager extends AbstractManager
{
    /**
     * Crée une nouvelle commande dans la base de données.
     *
     * @param Order $order La commande à insérer.
     * @return int L'ID de la commande nouvellement insérée.
     */
    public function create(Order $order): int
    {
        $query = $this->pdo->prepare(
            'INSERT INTO orders (user_id, order_date, status) VALUES (:userId, :orderDate, :status)'
        );
        $parameters = [
            'userId' => $order->getUserId(),
            'orderDate' => $order->getOrderDate(),
            'status' => $order->getStatus()
        ];
        $query->execute($parameters);
        return $this->pdo->lastInsertId();
    }

    /**
     * Récupère une commande par son identifiant.
     *
     * @param int $id L'ID de la commande à récupérer.
     * @return Order|null La commande trouvée ou null si elle n'existe pas.
     */
    public function findOne(int $id): ?Order
    {
        $query = $this->pdo->prepare('SELECT * FROM orders WHERE id=:id');
        $parameters = ['id' => $id];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return new Order($result['user_id'], $result['order_date'], $result['status'], $result['id']);
        }
        return null;
    }

    /**
     * Récupère toutes les commandes.
     *
     * @return array La liste des commandes.
     */
    public function findAll(): array
    {
        $query = $this->pdo->prepare('SELECT * FROM orders');
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $orders = [];

        foreach ($result as $item) {
            $orders[] = new Order($item['user_id'], $item['order_date'], $item['status'], $item['id']);
        }

        return $orders;
    }

    /**
     * Met à jour une commande dans la base de données.
     *
     * @param Order $order La commande à mettre à jour.
     * @return bool Résultat de l'opération de mise à jour.
     */
    public function update(Order $order): bool
    {
        $query = $this->pdo->prepare(
            'UPDATE orders SET user_id=:userId, order_date=:orderDate, status=:status WHERE id=:id'
        );
        $parameters = [
            'id' => $order->getId(),
            'userId' => $order->getUserId(),
            'orderDate' => $order->getOrderDate(),
            'status' => $order->getStatus()
        ];
        return $query->execute($parameters);
    }

    /**
     * Supprime une commande par son identifiant.
     *
     * @param int $id L'ID de la commande à supprimer.
     * @return bool Résultat de l'opération de suppression.
     */
    public function delete(int $id): bool
    {
        $query = $this->pdo->prepare('DELETE FROM orders WHERE id=:id');
        $parameters = ['id' => $id];
        return $query->execute($parameters);
    }
}
