<?php

namespace App\Controllers;

use App\Managers\CategoryManager;

class CategoryController extends AbstractController
{
    // Afficher la liste des catégories (accès public ou limité selon les besoins)
    public function index(): void
    {
        // Création d'une instance de CategoryManager sans constructeur
        $categoryManager = new CategoryManager();
        $categories = $categoryManager->findAll();

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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Protection contre les injections XSS
            $name = htmlspecialchars(trim($_POST['name'] ?? ''));

            // Vérification de la validité des données (exemple : si le champ name n'est pas vide)
            if (!empty($name)) {
                // Création d'une instance de CategoryManager
                $categoryManager = new CategoryManager();

                // Création de la catégorie dans la base de données
                $categoryManager->create($name);

                $this->redirect('/categories'); // Redirection après la création
            }
        }

        // Affichage du formulaire d'ajout
        $this->render('category/create.html.twig');
    }

    // Modifier une catégorie existante (accès réservé à l'administrateur)
    public function edit(int $id): void
    {
        // Vérification de l'accès administrateur
        $this->redirectIfNotAdmin();

        // Création d'une instance de CategoryManager
        $categoryManager = new CategoryManager();
        $category = $categoryManager->find($id);

        if (!$category) {
            $this->redirect('/404'); // Redirection si la catégorie est introuvable
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Protection contre les injections XSS
            $name = htmlspecialchars(trim($_POST['name'] ?? ''));

            // Vérification des données
            if (!empty($name)) {
                // Mise à jour de la catégorie
                $categoryManager->update($id, $name);
                $this->redirect('/categories'); // Redirection après la modification
            }
        }

        // Affichage du formulaire de modification
        $this->render('category/edit.html.twig', ['category' => $category]);
    }

    // Supprimer une catégorie (accès réservé à l'administrateur)
    public function delete(int $id): void
    {
        // Vérification de l'accès administrateur
        $this->redirectIfNotAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Création d'une instance de CategoryManager
            $categoryManager = new CategoryManager();

            // Suppression de la catégorie
            $categoryManager->delete($id);
            $this->redirect('/categories'); // Redirection après suppression
        }

        // Affichage d'une confirmation avant la suppression
        $this->render('category/delete.html.twig', ['id' => $id]);
    }
}
