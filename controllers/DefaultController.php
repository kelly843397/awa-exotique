<?php

class DefaultController extends AbstractController
{
    /**
     * Affiche la page d'accueil.
     */
    public function home(): void
    {
        // Afficher le template 'base.html.twig' pour la page d'accueil
        $this->render('base.html.twig', []);
    }

    /**
     * Affiche la page de contact.
     */
    public function contact(): void
    {
        // Afficher le template 'contact.html.twig' pour la page de contact
        $this->render('contact.html.twig', []);
    }

    /**
     * Affiche la page d'erreur 404.
     */
    public function notFound(): void
    {
        // Afficher le template '404.html.twig' pour la page d'erreur 404
        http_response_code(404); // Définir le code de réponse HTTP à 404
        $this->render('404.html.twig', []);
    }
}
