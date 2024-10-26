<?php

require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/DefaultController.php';
// Ajoutez ici les autres contrôleurs que vous avez

class Router 
{
    public function handleRequest(array $get) : void 
    {
        echo "Route demandée : " . ($get['route'] ?? 'aucune') . "<br>";

        // Instanciation des contrôleurs
        $userController = new UserController();
        $defaultController = new DefaultController();
        
        if (isset($get['route'])) {
            switch ($get['route']) {
                case 'login':
                    echo "Appel de login() dans UserController<br>";
                    // Route pour afficher la page de connexion
                    $userController->login();
                    break;
                
                case 'check-login':
                    echo "Appel de checkLogin() dans UserController<br>";
                    // Route pour vérifier les informations de connexion
                    $userController->checkLogin();
                    break;
                
                case 'register':
                    // Route pour afficher la page d'inscription
                    $userController->register();
                    break;

                case 'check-register':
                    // Route pour vérifier l'inscription
                    $userController->checkRegister();
                    break;

                case 'logout':
                    // Route pour se déconnecter
                    $userController->logout();
                    break;

                case 'home':
                    // Route pour la page d'accueil
                    $defaultController->home();
                    break;

                case 'contact':
                    // Route pour la page de contact
                    $defaultController->contact();
                    break;

                default:
                    // Route pour la page 404 si la route n'existe pas
                    echo "Route non reconnue, redirection vers notFound()<br>";
                    $defaultController->notFound();
                    break;
            }
        } else {
            // Si aucune route n'est spécifiée, rediriger vers la page d'accueil
            echo "Aucune route spécifiée, redirection vers home()<br>";
            $defaultController->home();
        }
    }
}
