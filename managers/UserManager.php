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
        $email = trim($user['email']);

       
        // Validation du prénom (seulement lettres et tirets)
        if (!preg_match("/^[a-zA-Z'-]+$/", $firstName)) {
            throw new Exception("Prénom invalide.");
        }

        // Validation de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email invalide.");
        }

        // Vérifier si l'email existe déjà dans la base de données
        $checkEmailSql = "SELECT COUNT(*) FROM users WHERE email = :email";
        $checkEmailStmt = $this->pdo->prepare($checkEmailSql);
        $checkEmailStmt->bindValue(':email', $email);
        $checkEmailStmt->execute();

        if ($checkEmailStmt->fetchColumn() > 0) {
            throw new Exception("Cet email est déjà utilisé.");
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

        // Exécution de la requête SQL et gestion des erreurs
        try {
            if ($statement->execute() === false) {
                throw new Exception("Échec de l'insertion de l'utilisateur dans la base de données.");
            }
            return true; // Succès de la création de l'utilisateur
        } catch (PDOException $e) {
            // Gestion des erreurs SQL et affichage d'un message d'erreur
            error_log($e->getMessage()); // Enregistrement de l'erreur dans les logs
            throw new Exception("Une erreur s'est produite lors de la création de l'utilisateur.");
        }
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

    public function updateUser(array $user): bool
    {
        // Vérification que l'utilisateur a bien un id pour l'identifier
        if (!isset($user['id'], $user['firstName'], $user['email'])) {
            throw new Exception("Les données utilisateur sont incomplètes.");
        }

        // Assainissement des données utilisateur
        $firstName = htmlspecialchars(trim($user['firstName']), ENT_QUOTES, 'UTF-8');
        $email = trim($user['email']);

        // Validation du prénom (seulement lettres et tirets)
        if (!preg_match("/^[a-zA-Z'-]+$/", $firstName)) {
            throw new Exception("Prénom invalide.");
        }

        // Validation de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email invalide.");
        }

        // Préparation de la requête SQL pour mettre à jour l'utilisateur
        $sql = "UPDATE users 
                SET firstName = :firstName, email = :email";

        // Si un mot de passe est fourni, on l'ajoute dans la requête
        if (isset($user['password']) && !empty($user['password'])) {
            if (!$this->validatePassword($user['password'])) {
                throw new Exception("Le mot de passe n'est pas assez fort.");
            }

            // Hachage du mot de passe avec bcrypt
            $hashedPassword = password_hash($user['password'], PASSWORD_BCRYPT);

            // Ajout du mot de passe dans la requête
            $sql .= ", password = :password";
        }

        // Ajout de la condition WHERE pour identifier l'utilisateur à mettre à jour
        $sql .= " WHERE id = :id";

        // Préparation de la requête SQL
        $statement = $this->pdo->prepare($sql);

        // Liaison des paramètres obligatoires
        $statement->bindValue(':firstName', $firstName);
        $statement->bindValue(':email', $email);
        $statement->bindValue(':id', $user['id'], PDO::PARAM_INT);

        // Liaison du mot de passe si fourni
        if (isset($user['password']) && !empty($user['password'])) {
            $statement->bindValue(':password', $hashedPassword);
        }

        // Exécution de la requête SQL
        try {
            if ($statement->execute() === false) {
                throw new Exception("Échec de la mise à jour de l'utilisateur.");
            }
            return true; // Succès de la mise à jour
        } catch (PDOException $e) {
            // Gestion des erreurs SQL et affichage d'un message d'erreur
            error_log($e->getMessage()); // Enregistrement de l'erreur dans les logs
            throw new Exception("Une erreur s'est produite lors de la mise à jour de l'utilisateur.");
        }
    }

    public function deleteUser(int $id): bool
    {
        // Vérification que l'ID est bien un entier valide
        if ($id <= 0) {
            throw new Exception("ID utilisateur invalide.");
        }

        // Préparation de la requête SQL pour supprimer l'utilisateur
        $sql = "DELETE FROM users WHERE id = :id";
        $statement = $this->pdo->prepare($sql);

        // Liaison de l'ID de l'utilisateur
        $statement->bindValue(':id', $id, PDO::PARAM_INT);

        // Exécution de la requête SQL et gestion des erreurs
        try {
            if ($statement->execute() === false) {
                throw new Exception("Échec de la suppression de l'utilisateur.");
            }
            return true; // Succès de la suppression
        } catch (PDOException $e) {
            // Gestion des erreurs SQL et affichage d'un message d'erreur
            error_log($e->getMessage()); // Enregistrement de l'erreur dans les logs
            throw new Exception("Une erreur s'est produite lors de la suppression de l'utilisateur.");
        }
    }

}
