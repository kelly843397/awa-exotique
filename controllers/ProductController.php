<?php

namespace App\Controllers;

use App\Managers\ProductManager;
use App\Managers\UserManager;

class ProductController extends AbstractController
{
    private UserManager $userManager;

    public function __construct()
    {
        $this->userManager = new UserManager();
    }

    // Vérifier si l'utilisateur est un administrateur
    private function isAdmin(): bool
    {
         // Session active avec des informations utilisateur
        // $_SESSION['user_role'] contient le rôle de l'utilisateur ('admin' ou 'user')
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    // Méthode pour afficher tous les produits (accessible à tous)
    public function readAllProducts()
    {
        $productManager = new ProductManager();
        $products = $productManager->getAllProducts();
        return $this->render('product/index.html.twig', ['products' => $products]);
    }

    // Méthode pour afficher un produit spécifique (accessible à tous)
    public function show(int $id)
    {
        $productManager = new ProductManager();
        $product = $productManager->getProductById($id);

        if ($product) {
            return $this->render('product/show.html.twig', ['product' => $product]);
        } else {
            return $this->render('errors/404.html.twig', ['error' => 'Produit non trouvé']);
        }
    }

    // Méthode pour afficher le formulaire d'ajout d'un produit (admin uniquement)
    public function create()
    {
        if ($this->isAdmin()) {
            return $this->render('product/create.html.twig', []);   // Ajout d'un tableau vide
        } else {
            return $this->render('errors/403.html.twig', ['error' => 'Accès refusé']);
        }
    }

    // Méthode pour gérer la soumission du formulaire d'ajout d'un produit (admin uniquement)
    public function store()
    {
        if ($this->isAdmin()) {
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $categoryId = $_POST['category_id'] ?? 0;
            $picture = $_POST['picture'] ?? '';

            $productManager = new ProductManager();
            if ($productManager->addProduct($name, $price, $categoryId, $picture)) {
                header('Location: /products');
            } else {
                return $this->render('product/create.html.twig', ['error' => 'Erreur lors de l\'ajout du produit.']);
            }
        } else {
            return $this->render('errors/403.html.twig', ['error' => 'Accès refusé']);
        }
    }

    // Méthode pour afficher le formulaire de modification d'un produit (admin uniquement)
    public function edit(int $id)
    {
        if ($this->isAdmin()) {
            $productManager = new ProductManager();
            $product = $productManager->getProductById($id);

            if ($product) {
                return $this->render('product/edit.html.twig', ['product' => $product]);
            } else {
                return $this->render('errors/404.html.twig', ['error' => 'Produit non trouvé']);
            }
        } else {
            return $this->render('errors/403.html.twig', ['error' => 'Accès refusé']);
        }
    }

    // Méthode pour gérer la soumission du formulaire de mise à jour d'un produit (admin uniquement)
    public function update(int $id)
    {
        if ($this->isAdmin()) {
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $categoryId = $_POST['category_id'] ?? 0;
            $picture = $_POST['picture'] ?? '';

            $productManager = new ProductManager();
            if ($productManager->updateProduct($id, $name, $price, $categoryId, $picture)) {
                header('Location: /products');
            } else {
                return $this->render('product/edit.html.twig', ['error' => 'Erreur lors de la mise à jour du produit.']);
            }
        } else {
            return $this->render('errors/403.html.twig', ['error' => 'Accès refusé']);
        }
    }

    // Méthode pour supprimer un produit (admin uniquement)
    public function delete(int $id)
    {
        if ($this->isAdmin()) {
            $productManager = new ProductManager();
            if ($productManager->deleteProduct($id)) {
                header('Location: /products');
            } else {
                return $this->render('errors/500.html.twig', ['error' => 'Erreur lors de la suppression du produit.']);
            }
        } else {
            return $this->render('errors/403.html.twig', ['error' => 'Accès refusé']);
        }
    }

    // Méthode pour rechercher des produits par mot-clé (accessible à tous)
    public function search()
    {
        $query = $_GET['q'] ?? '';
        $productManager = new ProductManager();
        $products = $productManager->searchProducts($query);
        return $this->render('product/search.html.twig', ['products' => $products, 'query' => $query]);
    }
}
