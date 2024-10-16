<?php
// Classe Router pour gérer les routes
class Router
{
    private array $routes = [];

    // Ajouter une route au routeur
    public function addRoute(string $method, string $path, callable $action): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'action' => $action,
        ];
    }

    // Exécuter la route appropriée
    public function run(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes as $route) {
            // Vérifie si la méthode HTTP et l'URL correspondent
            if ($route['method'] === $requestMethod && $route['path'] === $requestUri) {
                call_user_func($route['action']);
                return;
            }
        }

        // Si aucune route ne correspond, afficher une page 404
        header("HTTP/1.0 404 Not Found");
        echo "Page non trouvée.";
    }
}

// Charger les contrôleurs
require_once 'controllers/CategoryController.php';

// Instancier le contrôleur des catégories
$categoryController = new CategoryController();
// Instancier le contrôleur des users
$userController = new UserController();

// Instancier le routeur
$router = new Router();

// Afficher toutes les catégories
$router->addRoute('GET', '/awa-exotique/categories', [$categoryController, 'index']);

// Afficher une catégorie spécifique
$router->addRoute('GET', '/awa-exotique/categories/show', function () use ($categoryController) {
    $id = (int)$_GET['id'] ?? 0;
    $categoryController->show($id);
});

// Afficher le formulaire de création d'une nouvelle catégorie
$router->addRoute('GET', '/awa-exotique/categories/create', [$categoryController, 'create']);

// Traiter la création d'une nouvelle catégorie
$router->addRoute('POST', '/awa-exotique/categories/create', [$categoryController, 'create']);

// Afficher le formulaire de modification d'une catégorie
$router->addRoute('GET', '/awa-exotique/categories/edit', function () use ($categoryController) {
    $id = (int)$_GET['id'] ?? 0;
    $categoryController->edit($id);
});

// Traiter la modification d'une catégorie
$router->addRoute('POST', '/awa-exotique/categories/edit', function () use ($categoryController) {
    $id = (int)$_GET['id'] ?? 0;
    $categoryController->edit($id);
});

// Traiter la suppression d'une catégorie
$router->addRoute('POST', '/awa-exotique/categories/delete', function () use ($categoryController) {
    $id = (int)$_GET['id'] ?? 0;
    $categoryController->delete($id);
});

$router->addRoute('GET', '/awa-exotique/test-route', function() {
    echo "Test route fonctionne";
});

// Routes pour gérer les utilisateurs

// Route pour afficher la liste des utilisateurs (GET)
$router->addRoute('GET', '/awa-exotique/users', function() {
    $userController = new UserController();
    $userController->index(); // Méthode pour afficher tous les utilisateurs
});

// Route pour afficher un utilisateur spécifique (GET)
$router->addRoute('GET', '/awa-exotique/users/show', function() {
    $id = (int)$_GET['id'] ?? 0;
    $userController = new UserController();
    $userController->show($id); // Méthode pour afficher un utilisateur spécifique
});

// Route pour créer un nouvel utilisateur (GET et POST)
$router->addRoute('GET', '/awa-exotique/users/create', function() {
    $userController = new UserController();
    $userController->create(); // Affiche le formulaire pour créer un utilisateur
});

$router->addRoute('POST', '/awa-exotique/users/create', function() {
    $userController = new UserController();
    $userController->create(); // Traite la soumission du formulaire de création d'utilisateur
});

// Route pour éditer un utilisateur (GET et POST)
$router->addRoute('GET', '/awa-exotique/users/edit', function() {
    $id = (int)$_GET['id'] ?? 0;
    $userController = new UserController();
    $userController->edit($id); // Affiche le formulaire pour modifier un utilisateur
});

$router->addRoute('POST', '/awa-exotique/users/edit', function() {
    $id = (int)$_GET['id'] ?? 0;
    $userController = new UserController();
    $userController->edit($id); // Traite la soumission du formulaire de modification
});

// Route pour supprimer un utilisateur (GET et POST)
$router->addRoute('GET', '/awa-exotique/users/delete', function() {
    $id = (int)$_GET['id'] ?? 0;
    $userController = new UserController();
    $userController->delete($id); // Affiche la confirmation pour supprimer un utilisateur
});

$router->addRoute('POST', '/awa-exotique/users/delete', function() {
    $id = (int)$_GET['id'] ?? 0;
    $userController = new UserController();
    $userController->delete($id); // Traite la soumission pour supprimer un utilisateur
});

// Route pour changer le mot de passe (GET et POST)
$router->addRoute('GET', '/awa-exotique/users/change-password', function() {
    $id = (int)$_GET['id'] ?? 0;
    $userController = new UserController();
    $userController->changePassword($id); // Affiche le formulaire de changement de mot de passe
});

$router->addRoute('POST', '/awa-exotique/users/change-password', function() {
    $id = (int)$_GET['id'] ?? 0;
    $userController = new UserController();
    $userController->changePassword($id); // Traite la soumission du changement de mot de passe
});

