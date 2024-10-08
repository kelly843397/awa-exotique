<?php

class Router
{
    private $routes = []; // Tableau qui va stocker les routes ajoutées

    // Fonction pour ajouter une route
    public function addRoute($path, $controller, $method)
    {
        // On ajoute une route au tableau avec le chemin, le contrôleur, et la méthode à appeler
        $this->routes[$path] = ['controller' => $controller, 'method' => $method];
    }

    // Fonction qui va être appelée pour traiter la requête
    public function dispatch()
    {
        // Récupération de l'URL demandée, sans les slashes au début et à la fin
        $uri = trim($_SERVER['REQUEST_URI'], '/');

        // On vérifie si l'URL demandée existe dans nos routes
        if (isset($this->routes[$uri])) {
            $route = $this->routes[$uri]; // On récupère la route correspondante
            $controllerName = $route['controller']; // Le contrôleur à appeler
            $methodName = $route['method']; // La méthode du contrôleur à appeler

            // On instancie le contrôleur
            $controller = new $controllerName();

            // On appelle la méthode du contrôleur
            $controller->$methodName();
        } else {
            // Si l'URL n'est pas dans les routes, on affiche une erreur 404
            echo "404 - Page non trouvée";
        }
    }
}
