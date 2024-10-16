<?php

class ProductManager extends AbstractManager
{
    // Méthode pour récupérer tous les produits
    public function findAllProducts(): array
    {
        try {
            // Requête SQL pour sélectionner tous les produits dans la base de données
            $query = "SELECT * FROM products";
            
            // Préparer la requête
            $stmt = $this->pdo->prepare($query);

            // Exécuter la requête
            $stmt->execute();

            // Retourne tous les résultats sous forme de tableau associatif
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Gestion des erreurs
            throw new \Exception("Erreur lors de la récupération des produits : " . $e->getMessage());
        }
    }

    // Méthode pour récupérer un produit par son ID
    public function findProductById(int $id): ?array
    {
        try {
            // Requête SQL pour sélectionner un produit par son ID
            $query = "SELECT * FROM products WHERE id = :id";

            // Préparer la requête
            $stmt = $this->pdo->prepare($query);

            // Lier l'ID au paramètre de la requête
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            // Exécuter la requête
            $stmt->execute();

            // Récupérer le produit
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            // Retourner le produit ou null si non trouvé
            return $product ?: null;
        } catch (\PDOException $e) {
            // Gestion des erreurs
            throw new \Exception("Erreur lors de la récupération du produit : " . $e->getMessage());
        }
    }

    // Méthode pour ajouter un produit dans la base de données
    public function createProduct(string $name, float $price, int $categoryId, string $picture): bool
    {
        try {
            // Requête SQL pour insérer un nouveau produit
            $query = "INSERT INTO products (name, price, category_id, picture) 
                      VALUES (:name, :price, :category_id, :picture)";

            // Préparer la requête
            $stmt = $this->pdo->prepare($query);

            // Lier les valeurs aux paramètres
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            $stmt->bindValue(':price', $price, PDO::PARAM_STR); // Utilise PDO::PARAM_STR pour les valeurs float
            $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
            $stmt->bindValue(':picture', $picture, PDO::PARAM_STR);

            // Exécuter la requête
            return $stmt->execute();
        } catch (\PDOException $e) {
            // Gestion des erreurs
            throw new \Exception("Erreur lors de la création du produit : " . $e->getMessage());
        }
    }

    // Méthode pour mettre à jour un produit existant
    public function updateProduct(int $id, string $name, float $price, int $categoryId, string $picture): bool
    {
        try {
            // Requête SQL pour mettre à jour les informations du produit
            $query = "UPDATE products 
                      SET name = :name, price = :price, category_id = :category_id, picture = :picture
                      WHERE id = :id";

            // Préparer la requête
            $stmt = $this->pdo->prepare($query);

            // Lier les valeurs aux paramètres
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            $stmt->bindValue(':price', $price, PDO::PARAM_STR);
            $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
            $stmt->bindValue(':picture', $picture, PDO::PARAM_STR);

            // Exécuter la requête
            return $stmt->execute();
        } catch (\PDOException $e) {
            // Gestion des erreurs
            throw new \Exception("Erreur lors de la mise à jour du produit : " . $e->getMessage());
        }
    }

    // Méthode pour supprimer un produit de la base de données
    public function deleteProduct(int $id): bool
    {
        try {
            // Supprimer d'abord toutes les lignes liées à ce produit dans orders_items
            $queryOrdersItems = "DELETE FROM orders_items WHERE product_id = :id";
            $stmtOrdersItems = $this->pdo->prepare($queryOrdersItems);
            $stmtOrdersItems->bindValue(':id', $id, PDO::PARAM_INT);
            $stmtOrdersItems->execute();

            // Ensuite, supprimer le produit lui-même
            $queryProduct = "DELETE FROM products WHERE id = :id";
            $stmtProduct = $this->pdo->prepare($queryProduct);
            $stmtProduct->bindValue(':id', $id, PDO::PARAM_INT);

            // Exécuter la requête pour supprimer le produit
            return $stmtProduct->execute();
        } catch (\PDOException $e) {
            // Gestion des erreurs
            throw new \Exception("Erreur lors de la suppression du produit : " . $e->getMessage());
        }
    }

    // Méthode pour récupérer tous les produits d'une catégorie spécifique
    public function getProductsByCategory(int $categoryId): array
    {
        try {
            // Requête SQL pour sélectionner les produits appartenant à une catégorie
            $query = "SELECT * FROM products WHERE category_id = :category_id";

            // Préparer la requête
            $stmt = $this->pdo->prepare($query);

            // Lier l'ID de la catégorie
            $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);

            // Exécuter la requête
            $stmt->execute();

            // Retourner tous les résultats sous forme de tableau associatif
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Gestion des erreurs
            throw new \Exception("Erreur lors de la récupération des produits par catégorie : " . $e->getMessage());
        }
    }

    // Méthode pour rechercher des produits par mot-clé
    public function searchProducts(string $query): array
    {
        try {
            // Requête SQL pour rechercher des produits dont le nom correspond au mot-clé
            $sql = "SELECT * FROM products WHERE name LIKE :query";

            // Préparer la requête
            $stmt = $this->pdo->prepare($sql);

            // Lier le mot-clé avec des wildcards pour la recherche
            $stmt->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);

            // Exécuter la requête
            $stmt->execute();

            // Retourner tous les résultats sous forme de tableau associatif
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Gestion des erreurs
            throw new \Exception("Erreur lors de la recherche de produits : " . $e->getMessage());
        }
    }
}
