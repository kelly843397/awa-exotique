<?php
/**
 * @author : Gaellan
 * @link : https://github.com/Gaellan
 */

 require_once __DIR__ . '/../vendor/autoload.php'; // Inclure l'autoload de Composer
abstract class AbstractController
{
    private \Twig\Environment $twig;
    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
        $twig = new \Twig\Environment($loader,[
            'debug' => true,
            'cache' => false, // Désactivation du cache pendant le développement
        ]);

        $twig->addExtension(new \Twig\Extension\DebugExtension());

        $this->twig = $twig;
    }

    protected function render(string $template, array $data) : void
    {
        echo $this->twig->render($template, $data);  
    }

    /**
     * Redirige vers une autre URL.
     *
     * @param string $url L'URL vers laquelle rediriger.
     */
    protected function redirect(string $url): void
    {
        header("Location: " . $url);
        //exit;
    }

    /**
     * Affiche une notification avec un type donné.
     *
     * @param string $message Le message de la notification.
     * @param string $type Le type de notification (success, error, info, etc.).
     */
    protected function notify(string $message, string $type): void
    {
        $_SESSION["notifications"][] = [
            "message" => $message,
            "type" => $type,
        ];
    }
}
 