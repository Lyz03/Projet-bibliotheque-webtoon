<?php

namespace App\Controller;

use App\Config;
use App\Entity\User;
use App\Manager\NumberManager;
use App\Manager\UserManager;
use DateTime;

class ConnectionController extends AbstractController
{
    public function default()
    {
        self::render('connection-inscription/connection');
    }

    /**
     * Check POST content to log in a user
     */
    public function logIn() {

        if (!isset($_POST['submit'])) {
            self::default();
            exit();
        }

        if (!isset($_POST['email'], $_POST['password'])) {
            self::default();
            exit();
        }

        $mail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $error = [];

        if (strlen($mail) < 8 || strlen($mail) >= 100) {
            $error[] = "l'adresse email doit faire entre 8 et 150 caractères";
        }

        $userManager = new UserManager();
        $user = $userManager->userExist($mail);

        if ($user === null) {
            $error[] = "L'utilisateur demandé n'est pas enregistré";
        }

        if (count($error) > 0) {

            $_SESSION['error'] = $error;
            self::default();
            exit();
        }

        if (password_verify($_POST['password'], $user->getPassword())) {

            $user->setPassword('');
            $_SESSION['user'] = $user;
            $userController = new UserController();
            $userController->default();

        } else {

            $_SESSION['error'] = ['Adresse mail ou mot de passe incorrect'];
            self::default();
            exit();
        }
    }

    /**
     * Destroy the session
     */
    public static function logOut() {
        session_unset();
        session_destroy();
        header('Location: /index.php?c=home');
        exit();
    }

    /**
     * sanitize POST content to create a new user
     */
    public function register() {

        if (!isset($_POST['submit'])) {
            self::default();
            exit();
        }

        if (!isset($_POST['email'], $_POST['username'], $_POST['password'])) {
            self::default();
            exit();
        }

        $mail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = $_POST['password'];
        $error = [];

        if (strlen($mail) < 8 || strlen($mail) >= 150) {
            $error[] = "l'adresse email doit faire entre 8 et 100 caractères";
        }

        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $error[] = "Adresse mail non valide";
        }

        if (strlen($_POST['username']) < 3 || strlen($_POST['username']) >= 45) {
            $error[] = "le pseudo doit faire entre 3 et 100 caractères";
        }

        if (strlen($password) < 8 || strlen($password) >= 255) {
            $error[] = "le mot de passe doit faire au moins 8 caractères";
        }

        if (count($error) > 0) {
            $_SESSION['error'] = $error;
            self::default();
            exit();
        }

        $userManager = new UserManager();

        if ($userManager->userExist($mail) !== null) {
            $_SESSION['error'] = ['adresse mail déjà enregistré'];
            self::default();
            exit();
        }

        if(!preg_match('/^(?=.*[!@#$%^&*-\])(?=.*[0-9])(?=.*[A-Z]).{8,20}$/', $password)) {
            $_SESSION['error'] = ["Le mot de passe n'est pas assez sécurisé"];
            self::default();
            exit();
        }

        if ($password === $_POST['passwordRepeat']) {

            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            $userManager->registerUser($mail, $username, $password);

            $user = $userManager->userExist($mail);

            //send a mail with a code + the redirect link
            $numberManager = new NumberManager();
            $code = [rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9)];
            $code = join('', $code);

            $numberManager->addNumber($user->getId(), $code);

            if (self::createAccountMail($user->getId())) {
                self::codePage($user->getId());
            } else {
                $_SESSION['error'] = ["Une erreur s'est produite, veuillez réessayer ultérieurement"];
                self::default();
            }

        } else {
            $_SESSION['error'] = ["Les mot de passe ne corespondent pas"];
            self::default();
            exit();
        }
    }

    /**
     * go to code page (create account, change password)
     * @param int $id
     */
    public function codePage(int $id) {
        $numberManager = new NumberManager();

        $number = $numberManager->getNumberByUserId($id);

        if ($number === null) {
            self::default();
            exit();
        }

        $date = new DateTime();

        if ($number->getTime() >= $date->modify("+1 day")) {
            $_SESSION['error'] = ['La demande de création de compte a expiré, veuillez recommencer'];

            $userController = new UserController();
            $userController->deleteUser($id);
            self::default();
            exit();
        }

        self::render('user/code', $data = ['id' => $id]);
    }

    /**
     * Send mail to validate a new account
     * @param int $id
     * @return bool
     */
    public function createAccountMail(int $id): bool {
        $url = Config::APP_URL . '/index.php?c=connection&a=code-page&id=' . $id;
        $numberManager = new NumberManager();
        $code = $numberManager->getNumberByUserId($id)->getNumber();

        $message = "
        <html lang='fr'>
            <head>
                <title>Vérification de votre compte Webtoon Library (annlio.com)</title>
            </head>
            <body>
                <span>Bonjour,</span>
                <p>
                    Afin de finaliser votre inscription sur notre site, 
                    <br>
                    merci de rentrer ce code : $code
                    <br>
                    à l'adresse <a href=\"$url\">suivante</a> pour vérifier votre adresse email.
                </p>
            </body>
        </html>
        ";

        $userManager = new UserManager();
        $to = $userManager->getUserById($id)->getEmail();
        $subject = 'Vérification de votre adresse email';
        $headers = [
            'Reply-to' => "no-reply@email.com",
            'X-Mailer' => 'PHP/' . phpversion(),
            'Mime-version' => '1.0',
            'Content-type' => 'text/html; charset=utf-8'
        ];

        return mail($to, $subject, $message, $headers, "-f no-reply@email.com");
    }

    /**
     * Validate an account
     * @param int $id
     */
    public function validateAccount(int $id) {
        if (!isset($_POST['submit']) || !isset($_POST['code'])) {
            self::default();
            exit();
        }

        $numberManager = new NumberManager();

        if ((int) $_POST['code'] === $numberManager->getNumberByUserId($id)->getNumber() ) {
            $numberManager->deleteNumber($id);
            $_SESSION['error'] = ['Compte validé, vous pouvez désormais vous connecter'];
            self::default();
        } else {
            $_SESSION['error'] = ['Code non valide'];
            self::codePage($id);
        }
    }
}
