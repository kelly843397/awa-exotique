<?php

class CategoryManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    // Méthode pour récupérer toutes les catégories
    public function findAll(): array
    {
        // Préparer la requête pour récupérer toutes les catégories
        $query = $this->db->prepare('SELECT * FROM categories');
        $query->execute();

        // Récupérer les résultats
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $categories = [];

        // Parcourir chaque ligne et créer un objet Category
        foreach ($result as $item) {
            // Créer une instance de Category
            $category = new Category($item['id'], $item['name']);
            // Ajouter l'objet Category au tableau
            $categories[] = $category;
        }

        return $categories;
    }
}
