<?php

class UserManager extends AbstractManager
{
    /**
     * Fetches a user based on its identifier.
     *
     * @param int $id The identifier of the user to fetch.
     * @return User|null The found user object or null if it doesn't exist.
     */
    public function findOne(int $id): ?User
    {
        $query = $this->pdo->prepare('SELECT * FROM users WHERE id=:id');
        $parameters = [
            "id" => $id
        ];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Convertir la date en objet DateTime
            $createdAt = new DateTime($result["created_at"]);
            $user = new User(
                $result["firstName"],
                $result["lastName"],
                $result["email"],
                $result["phone"],
                $result["password"],
                $result["role"],
                $createdAt,
                $result["id"]
            );
            return $user;
        }
        return null;
    }

    /**
     * Fetches all users.
     *
     * @return array List of users.
     */
    public function findAll(): array
    {
        $query = $this->pdo->prepare('SELECT * FROM users');
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $users = [];

        foreach ($result as $item) {
            // Convertir la date en objet DateTime
            $createdAt = new DateTime($item["created_at"]);
            $user = new User(
                $item["firstName"],
                $item["lastName"],
                $item["email"],
                $item["phone"],
                $item["password"],
                $item["role"],
                $createdAt,
                $item["id"]
            );
            $users[] = $user;
        }

        return $users;
    }

    /**
     * Updates a user.
     *
     * @param User $user The user object to update.
     * @return bool Result of the database update operation.
     */
    public function update(User $user): bool
    {
        $query = $this->pdo->prepare(
            'UPDATE users SET firstName=:firstName, lastName=:lastName, email=:email, phone=:phone, role=:role, password=:password WHERE id=:id'
        );

        $parameters = [
            'id' => $user->getId(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail(),
            'phone' => $user->getPhone(),
            'role' => $user->getRole(),
            'password' => $user->getPassword(),
        ];
        return $query->execute($parameters);
    }

    /**
     * Creates a user.
     *
     * @param User $user The user object to insert.
     * @return int The ID of the newly inserted user.
     */
    public function create(User $user): int
    {
        $query = $this->pdo->prepare(
            'INSERT INTO users (firstName, lastName, email, phone, role, password, created_at) VALUES (:firstName, :lastName, :email, :phone, :role, :password, :created_at)'
        );
        $parameters = [
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail(),
            'phone' => $user->getPhone(),
            'role' => $user->getRole(),
            'password' => $user->getPassword(),
            // Conversion de DateTime en chaîne de caractères formatée
            'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
        ];
        $query->execute($parameters);
        return $this->pdo->lastInsertId();
    }

    /**
     * Deletes a user.
     *
     * @param int $id The ID of the user to delete.
     * @return bool Result of the database deletion operation.
     */
    public function delete(int $id): bool
    {
        $query = $this->pdo->prepare('DELETE FROM users WHERE id=:id');
        $parameters = [
            "id" => $id
        ];
        return $query->execute($parameters);
    }

    /**
     * Finds a user by email.
     *
     * @param string $email The email of the user to find.
     * @return User|null The found user object or null if it doesn't exist.
     */
    public function findByEmail(string $email): ?User
    {
        $query = $this->pdo->prepare('SELECT * FROM users WHERE email=:email');
        $parameters = [
            "email" => $email
        ];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Convertir la date en objet DateTime
            $createdAt = new DateTime($result["created_at"]);
            $user = new User(
                $result["firstName"],
                $result["lastName"],
                $result["email"],
                $result["phone"],
                $result["password"],
                $result["role"],
                $createdAt,
                $result["id"]
            );
            return $user;
        }
        return null;
    }
}
