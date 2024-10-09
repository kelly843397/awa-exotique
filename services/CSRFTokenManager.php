<?php

class CSRFTokenManager
{
    // Générer un token CSRF et le stocker dans la session
    public function generateCSRFToken(): string
    {
        // Générer un token aléatoire sécurisé
        $token = bin2hex(random_bytes(32));

        // Stocker le token dans la session
        $_SESSION['csrf-token'] = $token;

        return $token;
    }

    // Valider le token CSRF envoyé avec la requête
    public function validateCSRFToken(string $token): bool
    {
        // Vérifier si le token stocké en session correspond à celui fourni par l'utilisateur
        if (isset($_SESSION['csrf-token']) && hash_equals($_SESSION['csrf-token'], $token)) {
            // Si les tokens correspondent, on retourne vrai
            return true;
        }

        // Si le token est absent ou incorrect, on retourne faux
        return false;
    }

    // Supprimer le token CSRF après utilisation (optionnel)
    public function removeCSRFToken(): void
    {
        unset($_SESSION['csrf-token']); // Supprime le token de la session
    }
}
