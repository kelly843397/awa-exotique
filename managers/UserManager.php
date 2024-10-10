<?php

class UserManager extends AbstractManager
{
    // Méthode pour créer un nouvel utilisateur
    public function createUser(array $user): bool
    {
        // Validation des données utilisateur
        if (!isset($user['firstName'], $user['email'], $user['password'])) {
            throw new Exception("Les données utilisateur sont incomplètes.");
        }

        // Assainissement des données utilisateur avec htmlspecialchars
        $firstName = htmlspecialchars($user['firstName'], ENT_QUOTES, 'UTF-8');
        $email = filter_var($user['email'], FILTER_SANITIZE_EMAIL);

        // Validation du prénom (seulement lettres et tirets)
        if (!preg_match("/^[a-zA-Z'-]+$/", $firstName)) {
            throw new Exception("Prénom invalide.");
        }

        // Validation de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email invalide.");
        }

        // Validation et sécurisation du mot de passe
        if (!$this->validatePassword($user['password'])) {
            throw new Exception("Le mot de passe n'est pas assez fort.");
        }

        // Hachage du mot de passe avec bcrypt
        $hashedPassword = password_hash($user['password'], PASSWORD_BCRYPT);

        // Préparation de la requête SQL pour insérer l'utilisateur dans la base de données
        $sql = "INSERT INTO users (firstName, email, password) VALUES (:firstName, :email, :password)";
        $statement = $this->pdo->prepare($sql);

        // Liaison des paramètres
        $statement->bindValue(':firstName', $firstName);
        $statement->bindValue(':email', $email);
        $statement->bindValue(':password', $hashedPassword);

        // Exécution de la requête SQL et retour du résultat
        return $statement->execute();
    }

    // Méthode pour valider la robustesse d'un mot de passe
    private function validatePassword(string $password): bool
    {
        // Le mot de passe doit contenir au moins 8 caractères, une majuscule, un chiffre, et un caractère spécial
        if (strlen($password) < 8 ||
            !preg_match('/[A-Z]/', $password) ||    // Vérifie la présence d'une majuscule
            !preg_match('/[0-9]/', $password) ||    // Vérifie la présence d'un chiffre
            !preg_match('/[\W]/', $password)) {     // Vérifie la présence d'un caractère spécial
            return false;
        }

        return true;
    }
}
