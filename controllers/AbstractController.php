<?php

namespace App\Controllers;
use Pecee\SimpleRouter\SimpleRouter;

abstract class AbstractController
{
    private \Twig\Environment $twig;
    public function __construct()
    {
        // Configuration de Twig
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader,[
            'debug' => true,
        ]);

        // Ajout de l'extension Debug de Twig
        $twig->addExtension(new \Twig\Extension\DebugExtension());

        $this->twig = $twig;
    }

    /**
     * Rendu des templates Twig avec les données
     * 
     * @param string $template - Le fichier Twig à rendre
     * @param array $data - Les données à passer au template
     */

    protected function render(string $template, array $data) : void
    {
        echo $this->twig->render($template, $data);
    }

    /**
     * Redirection vers une route définie dans SimpleRouter
     * 
     * @param string $route - Le nom ou l'URL de la route
     */
    protected function redirect(string $route) : void
    {
        // Utilisation de SimpleRouter pour gérer la redirection
        SimpleRouter::response()->redirect($route);
    }

    /**
     * Récupérer les paramètres depuis la requête
     *
     * @return mixed|null - Renvoie les paramètres de la route
     */
    protected function getParams(string $name): mixed
    {
        // Récupérer les paramètres de la route actuellement chargée
        $parameters = SimpleRouter::request()->getLoadedRoute()->getParameters();

        // Retourner le paramètre correspondant au nom passé en argument
        return $parameters[$name] ?? null;
    }
}
