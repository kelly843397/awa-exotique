<?php

class ProductManager extends AbstractManager
{
    /**
     * Fetches a product based on its identifier.
     *
     * @param int $id The identifier of the product to fetch.
     * @return Product|null The found product object or null if it doesn't exist.
     */
    public function findOne(int $id): ?Product
    {
        $query = $this->pdo->prepare('SELECT * FROM products WHERE id=:id');
        $parameters = [
            "id" => $id
        ];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $product = new Product(
                $result["name"],
                $result["picture"],
                $result["price"],
                $result["category_id"],
                $result["id"]
            );
            return $product;
        }
        return null;
    }

    /**
     * Fetches all products.
     *
     * @return array List of products.
     */
    public function findAll(): array
    {
        $query = $this->pdo->prepare('SELECT * FROM products');
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $products = [];

        foreach ($result as $item) {
            $product = new Product(
                $item["name"],
                $item["picture"],
                $item["price"],
                $item["category_id"],
                $item["id"]
            );
            $products[] = $product;
        }

        return $products;
    }

    /**
     * Creates a product.
     *
     * @param Product $product The product object to insert.
     * @return int The ID of the newly inserted product.
     */
    public function create(Product $product): int
    {
        $query = $this->pdo->prepare(
            'INSERT INTO products (name, picture, price, category_id) VALUES (:name, :picture, :price, :category_id)'
        );
        $parameters = [
            'name' => $product->getName(),
            'picture' => $product->getPicture(),
            'price' => $product->getPrice(),
            'category_id' => $product->getCategoryId()
        ];
        $query->execute($parameters);
        return $this->pdo->lastInsertId();
    }

    /**
     * Updates a product.
     *
     * @param Product $product The product object to update.
     * @return bool Result of the database update operation.
     */
    public function update(Product $product): bool
    {
        $query = $this->pdo->prepare(
            'UPDATE products SET name=:name, picture=:picture, price=:price, category_id=:category_id WHERE id=:id'
        );

        $parameters = [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'picture' => $product->getPicture(),
            'price' => $product->getPrice(),
            'category_id' => $product->getCategoryId()
        ];
        return $query->execute($parameters);
    }

    /**
     * Deletes a product.
     *
     * @param int $id The ID of the product to delete.
     * @return bool Result of the database deletion operation.
     */
    public function delete(int $id): bool
    {
        $query = $this->pdo->prepare('DELETE FROM products WHERE id=:id');
        $parameters = [
            "id" => $id
        ];
        return $query->execute($parameters);
    }
}
