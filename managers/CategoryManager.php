<?php

class CategoryManager extends AbstractManager
{
   // Méthode pour récupérer toutes les catégories
       public function findAll(): array
    {
        try {
            // Requête SQL pour récupérer toutes les catégories
            $query = "SELECT * FROM categories";

            // Exécuter la requête
            $stmt = $this->pdo->query($query);

            // Vérifier si la requête a été exécutée correctement
            if ($stmt === false) {
                return [];
            }

            // Retourner les résultats sous forme de tableau associatif
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Gérer les erreurs SQL
            return [];
        }
    }

    // Méthode pour récupérer une catégorie par son ID 
    public function find(int $id): ?array
    {
        try {
            // Requête SQL pour récupérer la catégorie par ID
            $query = "SELECT * FROM categories WHERE id = :id";

            // Préparer la requête
            $stmt = $this->pdo->prepare($query);

            // Lier l'ID au paramètre de la requête
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            // Exécuter la requête
            $stmt->execute();

            // Récupérer le résultat sous forme de tableau associatif
            $category = $stmt->fetch(PDO::FETCH_ASSOC);

            // Retourner la catégorie si elle existe, sinon null
            return $category ?: null;

        } catch (\PDOException $e) {
            // En cas d'erreur, retourner null
            return null;
        }
    }

    // Méthode pour ajouter une nouvelle catégorie
    public function create(string $name): bool
    {
        try {
            // Requête SQL pour insérer une nouvelle catégorie
            $query = "INSERT INTO categories (name) VALUES (:name)";

            // Préparer la requête
            $stmt = $this->pdo->prepare($query);

            // Lier le nom de la catégorie au paramètre SQL
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);

            // Exécuter la requête et retourner true si cela a fonctionné
            return $stmt->execute();
        } catch (\PDOException $e) {
            // En cas d'erreur, retourner false
            return false;
        }
    }

    // Méthode pour mettre à jour une catégorie
    public function update(int $id, string $name): bool
    {
        try {
            // Requête SQL pour mettre à jour le nom d'une catégorie par son ID
            $query = "UPDATE categories SET name = :name WHERE id = :id";

            // Préparer la requête
            $stmt = $this->pdo->prepare($query);

            // Lier les paramètres à la requête SQL
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);

            // Exécuter la requête et retourner true si cela a fonctionné
            return $stmt->execute();
        } catch (\PDOException $e) {
            // En cas d'erreur, retourner false
            return false;
        }
    }

    // Méthode pour supprimer une catégorie par son ID
    public function delete(int $id): bool
    {
        try {
            // Requête SQL pour supprimer une catégorie par son ID
            $query = "DELETE FROM categories WHERE id = :id";

            // Préparer la requête
            $stmt = $this->pdo->prepare($query);

            // Lier l'ID de la catégorie au paramètre SQL
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            // Exécuter la requête et retourner true si cela a fonctionné
            return $stmt->execute();
        } catch (\PDOException $e) {
            // En cas d'erreur, retourner false
            return false;
        }
    }
}
