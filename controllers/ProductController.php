<?php

class ProductController extends AbstractController
{
    // Afficher la liste des produits
    public function index(): void
    {
        $productManager = new ProductManager();
        $products = $productManager->findAllProducts();

        $this->render('product/index.html.twig', ['products' => $products]);
    }

    // Afficher un produit par son ID
    public function show(int $id): void
    {
        $productManager = new ProductManager();
        $product = $productManager->findProductById($id);

        if (!$product) {
            $this->redirect('/404'); // Redirection si le produit n'est pas trouvé
        }

        $this->render('product/show.html.twig', ['product' => $product]);
    }

    // Créer un nouveau produit
    public function create(): void
    {
        // Redirection si l'utilisateur n'est pas administrateur
        $this->redirectIfNotAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfManager = new CSRFTokenManager();
            if (!$csrfManager->validateCSRFToken($_POST['csrf_token'] ?? '')) {
                die("Token CSRF invalide.");
            }

            // Récupérer les données envoyées
            $productData = [
                'name' => $_POST['name'] ?? '',
                'price' => $_POST['price'] ?? '',
                'category_id' => $_POST['category_id'] ?? '',
                'picture' => $_POST['picture'] ?? ''
            ];

            $productManager = new ProductManager();

            try {
                // Créer le produit
                $productManager->createProduct(
                    $productData['name'],
                    (float) $productData['price'],
                    (int) $productData['category_id'],
                    $productData['picture']
                );
                $this->redirect('/products'); // Redirection après création
            } catch (\PDOException $e) {
                // Gérer les erreurs
                echo "Erreur : " . $e->getMessage();
            }
        }

        $this->render('product/create.html.twig');
    }

    // Mettre à jour un produit
    public function edit(int $id): void
    {
        // Redirection si l'utilisateur n'est pas administrateur
        $this->redirectIfNotAdmin();

        $productManager = new ProductManager();
        $product = $productManager->findProductById($id);

        if (!$product) {
            $this->redirect('/404'); // Redirection si le produit n'est pas trouvé
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfManager = new CSRFTokenManager();
            if (!$csrfManager->validateCSRFToken($_POST['csrf_token'] ?? '')) {
                die("Token CSRF invalide.");
            }

            $productData = [
                'id' => $id,
                'name' => $_POST['name'] ?? '',
                'price' => $_POST['price'] ?? '',
                'category_id' => $_POST['category_id'] ?? '',
                'picture' => $_POST['picture'] ?? ''
            ];

            try {
                // Mettre à jour le produit
                $productManager->updateProduct(
                    $id,
                    $productData['name'],
                    (float) $productData['price'],
                    (int) $productData['category_id'],
                    $productData['picture']
                );
                $this->redirect('/products'); // Redirection après mise à jour
            } catch (\PDOException $e) {
                // Gérer les erreurs
                echo "Erreur : " . $e->getMessage();
            }
        }

        $this->render('product/edit.html.twig', ['product' => $product]);
    }

    // Supprimer un produit
    public function delete(int $id): void
    {
        // Redirection si l'utilisateur n'est pas administrateur
        $this->redirectIfNotAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfManager = new CSRFTokenManager();
            if (!$csrfManager->validateCSRFToken($_POST['csrf_token'] ?? '')) {
                die("Token CSRF invalide.");
            }

            $productManager = new ProductManager();

            try {
                // Supprimer le produit
                $productManager->deleteProduct($id);
                $this->redirect('/products'); // Redirection après suppression
            } catch (\PDOException $e) {
                // Gérer les erreurs
                echo "Erreur : " . $e->getMessage();
            }
        }

        $this->render('product/delete.html.twig', ['id' => $id]);
    }

    // Recherche de produits
    public function search(): void
    {
        $searchQuery = $_GET['query'] ?? '';

        if ($searchQuery !== '') {
            $productManager = new ProductManager();
            $products = $productManager->searchProducts($searchQuery);

            $this->render('product/search.html.twig', ['products' => $products, 'query' => $searchQuery]);
        } else {
            $this->redirect('/products'); // Redirection si la recherche est vide
        }
    }
}
