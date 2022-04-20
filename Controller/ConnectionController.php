<?php

namespace App\Controller;

use App\Manager\UserManager;

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
            self::render('user/account');

        } else {

            $_SESSION['error'] = ['Adresse mail ou mot de passe incorrect'];
            self::default();
            exit();
        }
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

        if (strlen($_POST['username']) < 3 || strlen($_POST['username']) >= 100) {
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

            $mail = filter_var($mail, FILTER_VALIDATE_EMAIL);

            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            $userManager->registerUser($mail, $username, $password);

            $user = $userManager->userExist($mail);
            $user->setPassword('');
            $_SESSION['user'] = $user;

            self::render('user/account');

        } else {
            $_SESSION['error'] = ["Les mot de passe ne corespondent pas"];
            self::default();
            exit();
        }
    }
}
