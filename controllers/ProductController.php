<?php

class ProductController extends AbstractController
{
    private $productManager;
    private $categoryManager;

    public function __construct()
    {
        // Appelle le constructeur du parent pour initialiser Twig
        parent::__construct();
        // Initialisation des managers pour la gestion des produits et des catégories
        $this->productManager = new ProductManager(); 
        $this->categoryManager = new CategoryManager(); 
    }

    // Affiche la liste des produits avec la possibilité de filtrer par catégorie
    public function index($categoryId = null)
    {
        if ($categoryId) {
            // Si une catégorie est fournie, on récupère les produits de cette catégorie
            $category = $this->categoryManager->getCategoryById($categoryId);
            $products = $this->productManager->getProductsByCategory($categoryId);
        } else {
            // Sinon, on affiche tous les produits
            $products = $this->productManager->getAllProducts();
            $category = null;
        }

        // Rendu du template Twig avec la liste des produits et la catégorie sélectionnée (ou null)
        return $this->render('product/index.html.twig', [
            'products' => $products,
            'category' => $category
        ]);
    }

    // Affiche les détails d'un produit spécifique
    public function show($id)
    {
        // Récupération des détails du produit via le ProductManager
        $product = $this->productManager->getProductById($id);

        // Si le produit n'est pas trouvé, on renvoie une page 404
        if (!$product) {
            return $this->render('error/404.html.twig');
        }

        // Rendu du template Twig avec les détails du produit
        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }

    // Gère l'ajout d'un produit (affichage du formulaire et traitement des données)
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupération des données du formulaire
            $name = $_POST['name'];
            $price = $_POST['price'];
            $categoryId = $_POST['category_id'];

            // Validation des données (à améliorer selon les besoins)
            if (empty($name) || empty($price) || empty($categoryId)) {
                $error = "Tous les champs sont obligatoires.";
                return $this->render('product/add.html.twig', ['error' => $error]);
            }

            // Ajout du produit dans la base de données via le ProductManager
            $this->productManager->addProduct($name, $price, $categoryId);

            // Redirection vers la liste des produits après l'ajout
            return $this->redirect('/index.php?route=products');
        }

        // Récupération des catégories pour les afficher dans le formulaire
        $categories = $this->categoryManager->getAllCategories();

        // Affichage du formulaire d'ajout de produit
        return $this->render('product/add.html.twig', [
            'categories' => $categories
        ]);
    }

    // Recherche des produits par mot-clé
    public function search($query)
    {
        // Récupération des produits correspondant à la requête de recherche
        $products = $this->productManager->searchProducts($query);

        // Rendu du template avec les résultats de la recherche
        return $this->render('product/search.html.twig', [
            'products' => $products,
            'query' => $query
        ]);
    }
}
