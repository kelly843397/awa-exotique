<?php

class OrderManager extends AbstractManager
{
    // Nom de la table à manipuler dans la base de données
    protected string $table = 'orders';

    /**
     * Méthode pour insérer une nouvelle commande dans la base de données
     *
     * @param Order $order - L'objet Order à insérer
     * @return bool - Retourne true si l'insertion a réussi, false sinon
     */
    public function create(Order $order): bool
    {
        // Requête SQL pour insérer une nouvelle commande
        $query = 'INSERT INTO ' . $this->table . ' (user_id, order_date, status) VALUES (:user_id, :order_date, :status)';
        $stmt = $this->pdo->prepare($query);
        
        // Liaison des paramètres de l'objet Order avec la requête SQL
        $stmt->bindValue(':user_id', $order->getUserId(), PDO::PARAM_INT);
        $stmt->bindValue(':order_date', $order->getOrderDate());
        $stmt->bindValue(':status', $order->getStatus());

        // Exécution de la requête SQL
        return $stmt->execute();
    }

    /**
     * Méthode pour récupérer une commande par son ID
     *
     * @param int $id - L'ID de la commande à récupérer
     * @return Order|null - Retourne un objet Order si trouvé, sinon null
     */
    public function find(int $orderId): ?Order
    {
        // Préparer la requête SQL pour récupérer une commande spécifique par ID
        $query = 'SELECT * FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->pdo->prepare($query);
        
       // Lier la valeur de l'ID à la requête en tant qu'entier
        $stmt->bindValue(':id', $orderId, PDO::PARAM_INT);
        // Exécuter la requête
        $stmt->execute();

        // Récupérer une seule ligne de résultat sous forme de tableau associatif
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si une commande est trouvée, créer et retourner un objet Order
        if ($data) {
            return new Order(
                (int)$data['id'],           // Conversion explicite de l'ID en entier
                (int)$data['user_id'],      // Conversion explicite du user_id en entier
                $data['order_date'],        // La date de commande reste une chaîne (format datetime)
                $data['status']             // Le statut reste une chaîne
            );
        }

        // Si aucune commande n'est trouvée, retourner null
        return null;
    }


    /**
     * Méthode pour récupérer toutes les commandes
     *
     * @return array - Retourne un tableau d'objets Order
     */
    public function findAll(): array
    {
        // Requête SQL pour récupérer toutes les commandes
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->pdo->query($query);
        
        // Récupérer toutes les lignes de résultats
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Tableau pour stocker les objets Order
        $orders = [];

        // Boucle sur chaque ligne pour créer un objet Order et l'ajouter au tableau
        foreach ($results as $row) {
            $orders[] = new Order(
                $row['id'],
                $row['user_id'],
                $row['order_date'],
                $row['status']
            );
        }

        // Retourne le tableau d'objets Order
        return $orders;
    }

    /**
     * Méthode pour mettre à jour une commande existante
     *
     * @param Order $order - L'objet Order à mettre à jour
     * @return bool - Retourne true si la mise à jour a réussi, false sinon
     */
    public function update(Order $order): bool
    {
        // Requête SQL pour mettre à jour une commande existante
        $query = 'UPDATE ' . $this->table . ' SET user_id = :user_id, order_date = :order_date, status = :status WHERE id = :id';
        $stmt = $this->pdo->prepare($query);

        // Liaison des paramètres de l'objet Order avec la requête SQL
        $stmt->bindValue(':user_id', $order->getUserId(), PDO::PARAM_INT);
        $stmt->bindValue(':order_date', $order->getOrderDate());
        $stmt->bindValue(':status', $order->getStatus());
        $stmt->bindValue(':id', $order->getId(), PDO::PARAM_INT);

        // Exécution de la requête SQL
        return $stmt->execute();
    }

    /**
     * Méthode pour supprimer une commande par son ID
     *
     * @param int $id - L'ID de la commande à supprimer
     * @return bool - Retourne true si la suppression a réussi, false sinon
     */
    public function delete(int $id): bool
    {
        // Requête SQL pour supprimer une commande par son ID
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        // Exécution de la requête SQL
        return $stmt->execute();
    }
}
