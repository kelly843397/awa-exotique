<?php
namespace App\Controllers;

use Pecee\SimpleRouter\SimpleRouter;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;


abstract class AbstractController
{
    protected Environment $twig;
    protected SimpleRouter $router;

    public function __construct(Environment $twig, SimpleRouter $router)
    {
        $this->twig = $twig;
        $this->router = $router;
    }

    protected function render(string $template, array $data = []): void
    {
            echo $this->twig->render($template, $data);
       
    }

    protected function redirect(string $route): void
    {
        $this->router->response()->redirect($route);
    }

    protected function getParam(string $name): mixed
    {
        $parameters = $this->router->request()->getLoadedRoute()->getParameters();
        return $parameters[$name] ?? null;
    }

}