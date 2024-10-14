<?php

namespace App\Controllers;

use App\Managers\ProductManager;

class ProductController extends AbstractController
{
    // Méthode pour afficher tous les produits
    public function readAllProducts()
    {
        // Création d'une instance du ProductManager pour récupérer les produits
        $productManager = new ProductManager();
        
        // Récupère tous les produits
        $products = $productManager->getAllProducts();
        
        // Rendu de la vue avec la liste des produits
        return $this->render('product/index.html.twig', ['products' => $products]);
    }

    // Méthode pour afficher un produit spécifique par son ID
    public function show(int $id)
    {
        // Création d'une instance du ProductManager pour récupérer le produit par ID
        $productManager = new ProductManager();
        
        // Récupère le produit
        $product = $productManager->getProductById($id);
        
        // Si le produit existe, on l'affiche, sinon, on redirige vers une page 404
        if ($product) {
            return $this->render('product/show.html.twig', ['product' => $product]);
        } else {
            // Passe un tableau vide ou un message d'erreur à la vue d'erreur 404
            return $this->render('errors/404.html.twig', ['error' => 'Produit non trouvé']);
        }
    }

    // Méthode pour afficher le formulaire d'ajout d'un produit
    public function create()
    {
        // Affiche la vue avec le formulaire de création de produit
        return $this->render('product/create.html.twig', []);
    }

    // Méthode pour gérer la soumission du formulaire d'ajout d'un produit
    public function store()
    {
        // Récupération des données du formulaire via $_POST
        $name = $_POST['name'] ?? '';
        $price = $_POST['price'] ?? 0;
        $categoryId = $_POST['category_id'] ?? 0;
        $picture = $_POST['picture'] ?? '';

        // Création d'une instance du ProductManager pour ajouter un produit
        $productManager = new ProductManager();

        // Ajout du produit
        if ($productManager->addProduct($name, $price, $categoryId, $picture)) {
            // Redirection vers la liste des produits si l'ajout a réussi
            header('Location: /products');
        } else {
            // Si une erreur survient, on retourne à la vue de création avec un message d'erreur
            return $this->render('product/create.html.twig', ['error' => 'Erreur lors de l\'ajout du produit.']);
        }
    }

    // Méthode pour afficher le formulaire de modification d'un produit
    public function edit(int $id): void
    {
        // Création d'une instance du ProductManager pour récupérer le produit par ID
        $productManager = new ProductManager();
        
        // Récupère le produit à modifier
        $product = $productManager->getProductById($id);
        
        // Si le produit existe, on affiche le formulaire de modification, sinon on affiche une erreur
        if ($product) {
            // Passe les données à la vue (le produit)
            return $this->render('product/edit.html.twig', ['product' => $product]);
        } else {
            // Affiche une page d'erreur 404 avec un message d'erreur
            return $this->render('errors/404.html.twig', ['error' => 'Produit non trouvé']);
        }
    }

    // Méthode pour gérer la soumission du formulaire de mise à jour d'un produit
    public function update(int $id)
    {
        // Récupération des données du formulaire via $_POST
        $name = $_POST['name'] ?? '';
        $price = $_POST['price'] ?? 0;
        $categoryId = $_POST['category_id'] ?? 0;
        $picture = $_POST['picture'] ?? '';

        // Création d'une instance du ProductManager pour mettre à jour le produit
        $productManager = new ProductManager();

        // Mise à jour du produit
        if ($productManager->updateProduct($id, $name, $price, $categoryId, $picture)) {
            // Redirection vers la liste des produits si la mise à jour a réussi
            header('Location: /products');
        } else {
            // Si une erreur survient, on retourne à la vue de modification avec un message d'erreur
            return $this->render('product/edit.html.twig', ['error' => 'Erreur lors de la mise à jour du produit.']);
        }
    }

    // Méthode pour supprimer un produit
    public function delete(int $id)
    {
        // Création d'une instance du ProductManager pour supprimer le produit
        $productManager = new ProductManager();

        // Suppression du produit
        if ($productManager->deleteProduct($id)) {
            // Redirection vers la liste des produits si la suppression a réussi
            header('Location: /products');
        } else {
            // Affiche une page d'erreur si la suppression échoue
            return $this->render('errors/500.html.twig', ['error' => 'Erreur lors de la suppression du produit.']);
        }
    }

    // Méthode pour rechercher des produits par mot-clé
    public function search()
    {
        // Récupération du mot-clé de recherche depuis les paramètres GET
        $query = $_GET['q'] ?? '';
        
        // Création d'une instance du ProductManager pour effectuer la recherche
        $productManager = new ProductManager();
        
        // Rechercher les produits
        $products = $productManager->searchProducts($query);
        
        // Affichage des résultats de la recherche
        return $this->render('product/search.html.twig', ['products' => $products, 'query' => $query]);
    }
}
