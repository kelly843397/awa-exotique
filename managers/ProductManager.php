<?php

class ProductManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    // Méthode pour récupérer tous les produits
    public function findAll(): array
    {
        $query = $this->db->prepare('SELECT * FROM products');
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $products = [];

        foreach ($result as $item) {
            $product = new Product(
                $item['id'],
                $item['name'],
                $item['picture'],
                $item['price'],
                $item['category_id']
            );
            $products[] = $product;
        }

        return $products;
    }
}
