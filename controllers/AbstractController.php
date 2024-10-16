<?php

abstract class AbstractController
{
    protected $twig;

    public function __construct()
    {
        // Charger les templates depuis le dossier "templates"
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');

        // Instancier Twig avec le loader et activer le mode debug
        $this->twig = new \Twig\Environment($loader, [
            'debug' => true,  // Active le mode debug
            'cache' => false, // Désactive le cache pour le développement
        ]);

        // Ajouter l'extension Debug à Twig
        $this->twig->addExtension(new \Twig\Extension\DebugExtension());

        // Vérifier que la session est démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Méthode pour afficher un template Twig avec des données
    protected function render(string $template, array $data = []): void
    {
        echo $this->twig->render($template, $data);
    }

    // Méthode pour rediriger vers une autre route
    protected function redirect(string $route): void
    {
        header('Location: ' . $route);
        exit();
    }

    // Méthode pour récupérer un paramètre depuis l'URL (GET)
    protected function getParam(string $name): mixed
    {
        return $_GET[$name] ?? null;
    }

    // Méthodes de vérification d'authentification et rôle

    // Vérifie si l'utilisateur est authentifié
    protected function isAuthenticated(): bool
    {
        return isset($_SESSION['user']);
    }

    // Vérifie si l'utilisateur est administrateur
    protected function isAdmin(): bool
    {
        return $this->isAuthenticated() && $_SESSION['user']['role'] === 'admin';
    }

    // Redirige l'utilisateur vers la page de connexion s'il n'est pas authentifié
    protected function redirectIfNotAuthenticated(): void
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            exit();
        }
    }

    // Redirige l'utilisateur vers une page d'erreur s'il n'est pas administrateur
    protected function redirectIfNotAdmin(): void
    {
        if (!$this->isAdmin()) {
            $this->redirect('/awa-exotique/403');
            exit();
        }
    }

    // Méthode pour ajouter des variables globales à Twig, comme l'utilisateur authentifié
    protected function addGlobalTwigVariables(): void
    {
        $this->twig->addGlobal('user', $_SESSION['user'] ?? null);
    }
}
