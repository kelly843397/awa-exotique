<?php

class CategoryController extends AbstractController
{
    // Afficher la liste des catégories (accès public ou limité selon les besoins)
    public function index(): void
    {
        // Création d'une instance de CategoryManager
        $categoryManager = new CategoryManager();
        $categories = $categoryManager->findAll();

        // Vérifier si la requête est une requête AJAX (fetch)
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            // Retourner les catégories au format JSON pour les appels AJAX
            header('Content-Type: application/json');
            echo json_encode($categories);
            exit;
        }


        // Affichage de la liste des catégories
        $this->render('category/index.html.twig', ['categories' => $categories]);
    }

    // Afficher une catégorie en particulier (accès public ou réservé)
    public function show(int $id): void
    {
        // Création d'une instance de CategoryManager
        $categoryManager = new CategoryManager();
        $category = $categoryManager->find($id);

        if (!$category) {
            $this->redirect('/404'); // Redirection en cas de catégorie introuvable
        }

        // Affichage du détail d'une catégorie
        $this->render('category/show.html.twig', ['category' => $category]);
    }

    // Ajouter une nouvelle catégorie (accès réservé à l'administrateur)
    public function create(): void
    {
        // Vérification de l'accès administrateur
        $this->redirectIfNotAdmin();

        // Instancier le CSRFTokenManager
        $csrfManager = new CSRFTokenManager();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifier le token CSRF
            if (!$csrfManager->validateCSRFToken($_POST['csrf_token'] ?? '')) {
                die("CSRF token invalide.");
            }

            // Protection contre les injections XSS
            $name = htmlspecialchars(trim($_POST['name'] ?? ''));

            // Vérification de la validité des données
            if (!empty($name)) {
                $categoryManager = new CategoryManager();
                $categoryManager->create($name);
                $this->redirect('/categories'); // Redirection après la création
            }
        }

        // Générer un token CSRF pour le formulaire
        $csrfToken = $csrfManager->generateCSRFToken();
        $this->render('category/create.html.twig', ['csrf_token' => $csrfToken]);
    }

    // Modifier une catégorie existante (accès réservé à l'administrateur)
    public function edit(int $id): void
    {
        // Vérification de l'accès administrateur
        $this->redirectIfNotAdmin();

        // Instancier le CSRFTokenManager
        $csrfManager = new CSRFTokenManager();

        // Création d'une instance de CategoryManager
        $categoryManager = new CategoryManager();
        $category = $categoryManager->find($id);

        if (!$category) {
            $this->redirect('/404'); // Redirection si la catégorie est introuvable
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifier le token CSRF
            if (!$csrfManager->validateCSRFToken($_POST['csrf_token'] ?? '')) {
                die("CSRF token invalide.");
            }

            // Protection contre les injections XSS
            $name = htmlspecialchars(trim($_POST['name'] ?? ''));

            // Vérification des données
            if (!empty($name)) {
                $categoryManager->update($id, $name);
                $this->redirect('/categories'); // Redirection après la modification
            }
        }

        // Générer un token CSRF pour le formulaire
        $csrfToken = $csrfManager->generateCSRFToken();
        $this->render('category/edit.html.twig', ['category' => $category, 'csrf_token' => $csrfToken]);
    }

    // Supprimer une catégorie (accès réservé à l'administrateur)
    public function delete(int $id): void
    {
        // Vérification de l'accès administrateur
        $this->redirectIfNotAdmin();

        // Instancier le CSRFTokenManager
        $csrfManager = new CSRFTokenManager();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifier le token CSRF
            if (!$csrfManager->validateCSRFToken($_POST['csrf_token'] ?? '')) {
                die("CSRF token invalide.");
            }

            // Création d'une instance de CategoryManager
            $categoryManager = new CategoryManager();

            // Suppression de la catégorie
            $categoryManager->delete($id);
            $this->redirect('/categories'); // Redirection après suppression
        }

        // Générer un token CSRF pour la confirmation de suppression
        $csrfToken = $csrfManager->generateCSRFToken();
        $this->render('category/delete.html.twig', ['id' => $id, 'csrf_token' => $csrfToken]);
    }
}
