<?php

namespace App\Controller;

use Exception;

abstract class AbstractController
{

    abstract public function default();

    public function render(string $directoryFile, array $data = null) {
        ob_start();
        require __DIR__ . "/../view/" . $directoryFile . ".php";
        $page = ob_get_clean();
        require __DIR__ . "/../view/base.php";
    }

    /**
     * return random characters
     * @return string
     */
    public function randomChars(): string{
        try {
            $bytes = random_bytes(15);
        } catch (Exception $e) {
            $bytes = openssl_random_pseudo_bytes(15);
        }
        return bin2hex($bytes);
    }

    /**
     * check the email, username, password length from $_POST
     * @return array
     */
    public function checkMailUsernamePassword(): array {
        $mail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = $_POST['password'];
        $error = [];

        if (strlen($password) < 8 || strlen($password) >= 255) {
            $error[] = "le mot de passe doit faire au moins 8 caractères";
        }

        if (strlen($mail) < 8 || strlen($mail) >= 100) {
            $error[] = "l'adresse email doit faire entre 8 et 150 caractères";
        }

        if (strlen($username) < 5 || strlen($username) >= 100) {
            $error[] = "le pseudo doit faire entre 8 et 100 caractères";
        }

        return [
            'mail' => $mail,
            'username' => $username,
            'password' => $password,
            'error' => $error,
        ];
    }
}