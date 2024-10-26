<?php

class UserController extends AbstractController
{
    /**
     * Affiche le formulaire de connexion.
     */
    public function login(): void
    {
        $csrfTokenManager = new CSRFTokenManager();
        $csrfToken = $csrfTokenManager->generateCSRFToken();
        var_dump($csrfToken); // Debug: Afficher le jeton CSRF
        $this->render('account/login.html.twig', [
            'csrf_token' => $csrfToken,
        ]);
    }

    /**
     * Gère la soumission du formulaire de connexion et l'authentification de l'utilisateur.
     */
    public function checkLogin(): void
    {
        // Vérification des champs requis
        if (empty($_POST["email"]) || empty($_POST["password"]) || empty($_POST["csrf-token"])) {
            $this->notify("Veuillez remplir tous les champs.", "error");
            $this->redirect("index.php?route=login");
            exit;
        }

        // Validation du token CSRF
        $csrfTokenManager = new CSRFTokenManager();
        if (!$csrfTokenManager->validateCSRFToken($_POST["csrf-token"])) {
            $this->notify("Erreur de sécurité. Veuillez réessayer.", "error");
            $this->redirect("index.php?route=login");
            exit;
        }

        // Recherche de l'utilisateur par email
        $userManager = new UserManager();
        $user = $userManager->findByEmail($_POST["email"]);
        if ($user) {
            echo "Utilisateur trouvé : " . htmlspecialchars($user->getEmail()) . "<br>";
        } else {
            echo "Utilisateur non trouvé<br>";
        }

        // Vérification du mot de passe
        if ($user && password_verify($_POST["password"], $user->getPassword())) {
            $_SESSION["user"] = $user;
            $this->notify("Bienvenue " . htmlspecialchars($user->getFirstName()) . " !", "success");
            
        } else {
            $this->notify("Identifiants incorrects. Veuillez réessayer.", "error");
            // $this->redirect("index.php?route=login");// erreur
            exit;
        }
    }


    /**
     * Affiche le formulaire d'inscription.
     */
    public function register(): void
    {
        $csrfTokenManager = new CSRFTokenManager();
        $csrfToken = $csrfTokenManager->generateCSRFToken();
        
        $this->render('account/register.html.twig', [
            'csrf_token' => $csrfToken,
        ]);
    }

    /**
     * Gère la soumission du formulaire d'inscription et la création de l'utilisateur.
     */
    public function checkRegister(): void
    {
        $requiredFields = [
            "firstName", "lastName", "email", "password", "confirm-password",
            "phone", "role", "csrf-token"
        ];
    
        // Vérification des champs requis
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                $this->notify("Champs manquants : $field", "error");
                $this->redirect("index.php?route=register");
                exit; 
            }
        }
    
        // Vérification du token CSRF
        $csrfTokenManager = new CSRFTokenManager();
        $csrfTokenValid = $csrfTokenManager->validateCSRFToken($_POST["csrf-token"]);

        if (!$csrfTokenValid) {
            $this->notify("Token CSRF invalide", "error");
            $this->redirect("index.php?route=register");
            exit;
        }
    
        // Vérification de la correspondance des mots de passe
        if ($_POST["password"] !== $_POST["confirm-password"]) {
            $this->notify("Les mots de passe ne correspondent pas", "error");
            $this->redirect("index.php?route=register");
            exit;
        }
    
        // Vérification de la complexité du mot de passe
        $passwordPattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[#?!@$%^&*-]).{8,}$/';
        if (!preg_match($passwordPattern, $_POST["password"])) {
            $this->notify("Le mot de passe doit contenir au moins 8 caractères, une majuscule, un chiffre et un caractère spécial.", "error");
            $this->redirect("index.php?route=register");
            exit;
        }
    
        // Vérification de l'existence de l'utilisateur
        $userManager = new UserManager();
        $userExists = $userManager->findByEmail($_POST["email"]);
        if ($userExists) {
            $this->notify("L'utilisateur existe déjà", "error");
            $this->redirect("index.php?route=register");
            exit;
        }
    
        // Création de l'utilisateur
        $user = new User(
            htmlspecialchars($_POST["firstName"]),
            htmlspecialchars($_POST["lastName"]),
            htmlspecialchars($_POST["email"]),
            htmlspecialchars($_POST["phone"]),
            password_hash($_POST["password"], PASSWORD_BCRYPT),
            htmlspecialchars($_POST["role"]),
            new DateTime()
        );
    
        $userId = $userManager->create($user);
    
        if ($userId) {
            $user->setId($userId);
            $_SESSION["user"] = $user;
            $this->notify("Inscription réussie !", "success");
            //$this->redirect("?route=home");
        } else {
            $this->notify("Erreur lors de la création de l'utilisateur.", "error");
            $this->redirect("index.php?route=register");
        }
    }
    

    /**
     * Déconnecte l'utilisateur en détruisant la session.
     */
    public function logout(): void
    {
        session_destroy();
        //$this->redirect("index.php?route=home");
        exit;
    }
}
