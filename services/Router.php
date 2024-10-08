<?php

class Router
{
    public function handleRequest(array $get)
    {
        // Instanciation des contrôleurs nécessaires
        $productController = new ProductController();
        $cartController = new CartController();
        $defaultController = new DefaultController();

        // Gestion des routes
        if (isset($get['route']) && $get['route'] === 'home') {
            $defaultController->home();
        }
        else if (isset($get['route']) && $get['route'] === 'cart') {
            $cartController->viewCart();
        }
        else if (isset($get['route']) && $get['route'] === 'products') {
            if (isset($get['category_id'])) {
                $productController->index($get['category_id']);
            } else {
                $productController->index(); // Affiche tous les produits si aucune catégorie n'est spécifiée
            }
        }
        else if (isset($get['route']) && $get['route'] === 'search') {
            if (isset($get['query'])) {
                $productController->search($get['query']);
            } else {
                echo "Veuillez entrer un mot-clé pour la recherche.";
            }
        }
        else {
            // Si aucune route n'est spécifiée, afficher la page d'accueil
            $defaultController->home();
        }
    }
}
