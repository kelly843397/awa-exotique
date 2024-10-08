<?php

class CategoryManager extends AbstractManager
{
   // Méthode pour récupérer toutes les catégories
       public function getAllCategories(): array
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
        } catch (PDOException $e) {
            // Gérer les erreurs SQL
            return [];
        }
    }

    // Méthode pour récupérer une catégorie par son ID
    public function getCategoryById(int $id): ?array
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

        } catch (PDOException $e) {
            // En cas d'erreur, retourner null
            return null;
        }
    }
}
