<?php

require_once 'AbstractController.php';

class TestController extends AbstractController
{
    public function testRender()
    {
        $data = [
            'title' => 'Test du AbstractController',
            'message' => 'Ceci est un test de la méthode render du AbstractController.'
        ];

        // Utiliser la méthode render pour afficher une vue
        $this->render('test.twig', $data);
    }

    public function testRedirect()
    {
        // Tester la redirection
        $this->redirect('/awa-exotique/index.php');
    }

    public function testGetParam()
    {
        // Récupérer un paramètre de l'URL
        $id = $this->getParam('id');
        echo "Le paramètre ID est : " . ($id ?? 'non défini');
    }

    public function testIsAuthenticated()
    {
        // Tester l'authentification
        echo $this->isAuthenticated() ? "Utilisateur authentifié." : "Utilisateur non authentifié.";
    }

    public function testIsAdmin()
    {
        // Tester si l'utilisateur est admin
        echo $this->isAdmin() ? "L'utilisateur est administrateur." : "L'utilisateur n'est pas administrateur.";
    }

    public function testRedirectIfNotAuthenticated()
    {
        // Tester la redirection si non authentifié
        $this->redirectIfNotAuthenticated();
    }

    public function testRedirectIfNotAdmin()
    {
        // Tester la redirection si non admin
        $this->redirectIfNotAdmin();
    }
}
