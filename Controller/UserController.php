<?php

use App\Controller\AbstractController;

class UserController extends AbstractController
{

    static public function index()
    {
        // TODO: Implement index() method.
    }



    /**
     * @return void
     */
    public static function register()
    {
        self::redirectIfConnected();

        if (self::isFormSubmitted()) {
            $mail = filter_var(self::getFormField('email'), FILTER_SANITIZE_EMAIL);
            $username = filter_var(self::getFormField('username'), FILTER_SANITIZE_STRING);
            $password = self::getFormField('password');
            $passwordRepeat = self::getFormField('password-repeat');


            $errors = [];
            if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                // l'email n'est pas valide.
                $errors[] = "L'adresse mail n'est pas au bon format";
            }

            if (!strlen($username) >= 2) {
                // Le firstname ne fait pas au moins 2 caractères.
                $errors[] = "Le username ne fait pas au moins 2 chars";
            }

            if ($password !== $passwordRepeat) {
                // Les passwords ne correspondent pas !
                $errors[] = "Les password ne correspondent pas";
            }

            if (!preg_match('/^(?=.*[!@#$%^&*-\])(?=.*[0-9])(?=.*[A-Z]).{8,20}$/', $password)) {
                // Le password ne correspond pas au critère.
                $errors[] = "Le password ne correpsond pas au critère";
            }

            // S'il y a une erreur, enregistrement des messages en session.
            if (count($errors) > 0) {
                $_SESSION['errors'] = $errors;
            } else {
                // C'est ok, pas d'erreurs, enregistrement.

                $user = (new User())
                    ->setUsername($username)
                    ->setEmail($mail)
                    ->setPassword(password_hash($password, PASSWORD_DEFAULT))
                    ;

                if (!UserManager::userMailExists($user->getEmail())) {
                    UserManager::addUser($user);
                    if (null !== $user->getId()) {
                        $_SESSION['success'] = "Félicitations votre compte est actif";
                        $user->setPassword('');
                        $_SESSION['user'] = $user;
                    } else {
                        $_SESSION['errors'] = ["Impossible de vous enregistrer"];
                    }
                } else {
                    $_SESSION['errors'] = ["Cette adresse mail existe déjà !"];
                }
            }
        }
        self::render('user/register');
    }



    /**
     * User logout.
     * @return void
     */
    public static function logout(): void
    {
        if (self::isUserConnected()) {
            $_SESSION = [];
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
            session_destroy();
        }

        HomeController::index();
    }


    /**
     * User login
     * @return void
     */
    public static function login()
    {
        self::redirectIfConnected();

        if (self::isFormSubmitted()) {
            $errorMessage = "L'utilisateur / le password est mauvais";
            $mail = filter_var(self::getFormField('email'), FILTER_SANITIZE_EMAIL);
            $password = self::getFormField('password');

            $user = UserManager::getUserByMail($mail);
            if (null === $user) {
                $_SESSION['errors'][] = $errorMessage;
            } else {
                if (password_verify($password, $user->getPassword())) {
                    $user->setPassword('');
                    $_SESSION['user'] = $user;
                    self::redirectIfConnected();
                    exit();
                } else {
                    $_SESSION['errors'][] = $errorMessage;
                }
            }
        }

        self::render('user/login');
    }

}