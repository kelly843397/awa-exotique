<?php

class UserManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    // Méthode pour récupérer tous les utilisateurs
    public function findAll(): array
    {
        $query = $this->db->prepare('SELECT * FROM users');
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $users = [];

        foreach ($result as $item) {
            $user = new User(
                $item['id'],
                $item['first_name'],
                $item['last_name'],
                $item['email'],
                $item['phone'],
                $item['password'],
                $item['role'],
                $item['created_at']
            );
            $users[] = $user;
        }

        return $users;
    }
}
