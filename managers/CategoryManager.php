<?php

class CategoryManager extends AbstractManager
{
    /**
     * Fetches a category based on its identifier.
     *
     * @param int $id The identifier of the category to fetch.
     * @return Category|null The found category object or null if it doesn't exist.
     */
    public function findOne(int $id): ?Category
    {
        $query = $this->pdo->prepare('SELECT * FROM categories WHERE id=:id');
        $parameters = [
            "id" => $id
        ];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $category = new Category($result["name"],$result["id"]);
            return $category;
        }
        return null;
    }

    /**
     * Fetches all categories.
     *
     * @return array List of categories.
     */
    public function findAll(): array
    {
        $query = $this->pdo->prepare('SELECT * FROM categories');
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $categories = [];

        foreach ($result as $item) {
            $category = new Category($result["name"],$result["id"]);
            $categories[] = $category;
        }
        return $categories;
    }

    /**
     * Updates a category.
     *
     * @param Category $category The category object to update.
     * @return bool Result of the database update operation.
     */
    public function update(Category $category): bool
    {
        $query = $this->pdo->prepare(
            'UPDATE categories SET name=:name WHERE id=:id'
        );

        $parameters = [
            'name' => $category->getName(),
            'id' => $category->getId(),
        ];
        return $query->execute($parameters);
    }

    /**
     * Creates a category.
     *
     * @param Category $category The category object to insert.
     * @return int The ID of the newly inserted category.
     */
    public function create(Category $category): int
    {
        $query = $this->pdo->prepare(
            'INSERT INTO categories (name) VALUES (:name)'
        );
        $parameters = [
            'name' => $category->getName(),
        ];
        $query->execute($parameters);
        return $this->pdo->lastInsertId();
    }

    /**
     * Deletes a category.
     *
     * @param int $id The ID of the category to delete.
     * @return bool Result of the database deletion operation.
     */
    public function delete(int $id): bool
    {
        $query = $this->pdo->prepare('DELETE FROM categories WHERE id=:id');
        $parameters = [
            "id" => $id
        ];
        return $query->execute($parameters);
    }
}
