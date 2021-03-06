<?php

namespace App\Controller;

use App\Config;
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
            $error[] = "l'adresse email doit faire entre 8 et 100 caractères";
        }

        $user = UserManager::userExist($mail);

        if ($user === null) {
            $error[] = "L'utilisateur demandé n'est pas enregistré";
        } elseif (($number = NumberManager::getNumberByUserId($user->getId())) !== null) {
            if ($number->getNumber() !== 0) {
                $error[] = "Veuillez vérifier votre compte avant de vous connecter";
            }
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

        if (!isset($_POST['email']) || !isset($_POST['emailRepeat']) || !isset($_POST['password']) ||
            !isset($_POST['passwordRepeat']) || !isset($_POST['username']) || !isset($_POST['submit'])) {
            self::default();
            exit();
        }

        // Google recaptcha
        if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
            $response = file_get_contents(
                'https://www.google.com/recaptcha/api/siteverify?secret=' . Config::CAPTCHA_KEY . '&response=' .
                $_POST['g-recaptcha-response']
            );

            $responseData = json_decode($response);
            if (!$responseData->success) {
                $_SESSION['error'] = ['La vérification a échoué, veuillez réessayer'];
                self::default();
                exit();
            }
        } else {
            $_SESSION['error'] = ['Veuillez cocher le captcha'];
            self::default();
            exit();
        }

        $mail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = $_POST['password'];
        $error = [];

        if (strlen($mail) < 8 || strlen($mail) >= 100) {
            $error[] = "l'adresse email doit faire entre 8 et 100 caractères";
        }

        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $error[] = "Adresse mail non valide";
        }

        if ($mail !== $_POST['emailRepeat']) {
            $error[] = "Les adresses email ne corespondent pas";
        }

        if (strlen($_POST['username']) < 3 || strlen($_POST['username']) >= 45) {
            $error[] = "le pseudo doit faire entre 3 et 45 caractères";
        }

        if (strlen($password) < 8 || strlen($password) >= 50) {
            $error[] = "le mot de passe doit faire au moins 8 caractères";
        }

        if (count($error) > 0) {
            $_SESSION['error'] = $error;
            self::default();
            exit();
        }

        if (UserManager::userExist($mail) !== null) {
            $_SESSION['error'] = ['adresse mail déjà enregistré'];
            self::default();
            exit();
        }

        if(!preg_match('/^(?=.*[!@#$%^&*-\])(?=.*[0-9])(?=.*[A-Z]).{8,50}$/', $password)) {
            $_SESSION['error'] = ["Le mot de passe n'est pas assez sécurisé"];
            self::default();
            exit();
        }

        if ($password === $_POST['passwordRepeat']) {

            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            UserManager::registerUser($mail, $username, $password);

            $user = UserManager::userExist($mail);

            //send a mail with a code + the redirect link
            $code = [rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9)];
            $code = join('', $code);

            NumberManager::addNumber($user->getId(), $code);

            if (self::createAccountMail($user->getId())) {
                self::codePage($user->getId());
            } else {
                $_SESSION['error'] = ["Une erreur s'est produite, veuillez réessayer ultérieurement"];
                self::default();
            }

        } else {
            $_SESSION['error'] = ["Les mots de passe ne corespondent pas"];
            self::default();
            exit();
        }
    }

    /**
     * go to code page (create account)
     * @param int $id
     */
    public function codePage(int $id) {

        $number = NumberManager::getNumberByUserId($id);

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
        $code = NumberManager::getNumberByUserId($id)->getNumber();

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

        $to = UserManager::getUserById($id)->getEmail();
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

        if (NumberManager::getNumberByUserId($id) === null) {
            $_SESSION['error'] = ['Ce compte a déjà été vérifié'];
            self::default();
            exit();
        }

        if ((int) $_POST['code'] === NumberManager::getNumberByUserId($id)->getNumber()) {
            NumberManager::deleteNumber($id);
            $_SESSION['error'] = ['Compte validé, vous pouvez désormais vous connecter'];
            $_SESSION['color'] = Config::SUCCESS;
            self::default();
        } else {
            $_SESSION['error'] = ['Code non valide'];
            self::codePage($id);
        }
    }

    /**
     * Go to changeUserInfo page
     */
    public function changeInfo() {
        if (!isset($_SESSION['user'])) {
            self::default();
            exit();
        }

        self::render('connection-inscription/changeUserInfo', $data = ['user' => $_SESSION['user']]);
    }

    /**
     * Change the Username
     */
    public function changeUsername() {
        if (!isset($_SESSION['user'])) {
            self::default();
            exit();
        }

        if (!isset($_POST['submit']) || !isset($_POST['username'])) {
            self::default();
            exit();
        }

        $id = $_SESSION['user']->getId();
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);

        if (UserManager::updateUsername($id, $username)) {
            $_SESSION['user']->setUsername($username);
            $_SESSION['error'] = ["Nom d'utilisateur changé avec succès"];
            $_SESSION['color'] = Config::SUCCESS;
        } else {
            $_SESSION['error'] = ['Une erreur est survenu, veuillez réessayer plus tard'];
        }

        (new UserController())->default();
    }

    /**
     * Change the email
     */
    public function changeEmail() {
        if (!isset($_SESSION['user'])) {
            self::default();
            exit();
        }

        if (!isset($_POST['submit']) || !isset($_POST['email'])) {
            self::default();
            exit();
        }

        $id = $_SESSION['user']->getId();
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

        if (UserManager::userExist($email) !== null) {
            $_SESSION['error'] = ["Adresse email déjà utilisée"];
            self::changeInfo();
            exit();
        }

        if (UserManager::updateEmail($id, $email)) {
            self::changeEmailMail($_SESSION['user']->getEmail(), $email);
            $_SESSION['user']->setEmail($email);
            $_SESSION['error'] = ["Adresse email changée avec succès"];
            $_SESSION['color'] = Config::SUCCESS;
        } else {
            $_SESSION['error'] = ['Une erreur est survenu, veuillez réessayer plus tard'];
        }

        (new UserController())->default();
    }

    /**
     * Send mail when the email address is changed
     * @param string $oldMail
     * @param string $newMail
     * @return bool
     */
    public function changeEmailMail(string $oldMail, string $newMail): bool {
        $message = "
        <html lang='fr'>
            <head>
                <title>Changement d'adresse email</title>
            </head>
            <body>
                <span>Bonjour,</span>
                <p>
                    Un changement d'adresse email a été effectué sur votre compte Webtoon Library (annlio.com)
                    <br>
                    il s'agit désormais de : $newMail
                    <br>
                    s'il ne s'agit pas de vous, contactez rapidement le support : lizoe.lallier@net-c.com
                </p>
            </body>
        </html>
        ";

        $to = $oldMail;
        $subject = "Changement d'adresse email";
        $headers = [
            'Reply-to' => "no-reply@email.com",
            'X-Mailer' => 'PHP/' . phpversion(),
            'Mime-version' => '1.0',
            'Content-type' => 'text/html; charset=utf-8'
        ];

        return mail($to, $subject, $message, $headers, "-f no-reply@email.com");
    }

    /**
     * Change the password
     */
    public function changePassword() {
        if (!isset($_SESSION['user'])) {
            self::default();
            exit();
        }

        if (!isset($_POST['submit']) || !isset($_POST['password']) || !isset($_POST['oldPassword']) || !isset($_POST['passwordRepeat'])) {
            self::default();
            exit();
        }

        $password = $_POST['password'];
        $id = $_SESSION['user']->getId();

        if (!password_verify($_POST['oldPassword'], UserManager::getUserById($id)->getPassword())) {
            $_SESSION['error'] = ["Votre mot de passe est incorrecte"];
            self::changeInfo();
            exit();
        }

        if(!preg_match('/^(?=.*[!@#$%^&*-\])(?=.*[0-9])(?=.*[A-Z]).{8,50}$/', $password)) {
            $_SESSION['error'] = ["Le mot de passe n'est pas assez sécurisé"];
            self::changeInfo();
            exit();
        }

        if ($password === $_POST['passwordRepeat']) {

            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            if (UserManager::updatePassword($id, $password)) {
                $_SESSION['error'] = ["Mot de passe changé avec succès"];
                $_SESSION['color'] = Config::SUCCESS;
            } else {
                $_SESSION['error'] = ['Une erreur est survenu, veuillez réessayer plus tard'];
            }

        } else {
            $_SESSION['error'] = ["Les mots de passe ne corespondent pas"];
            self::changeInfo();
            exit();
        }

        (new UserController())->default();
    }

    /**
     * Go to forgottenPassword page
     */
    public function forgottenPassword(string $token = '0', int $id = 0) {
        if ($token !== '0') {
            // no token for this id
            if (NumberManager::getTokenByUserId($id) === null) {
                self::default();
                exit();
                // wrong token
            } elseif (NumberManager::getTokenByUserId($id)->getToken() !== $token) {
                self::default();
                exit();
            }
        }

        self::render('connection-inscription/forgottenPassword', $data = [
            'token' => $token,
            'id' => $id,
        ]);
        exit();
    }

    /**
     * Check the mail and send password mail
     */
    public function newPassword() {
        if (isset($_SESSION['user'])) {
            self::render('home');
            exit();
        }

        if (!isset($_POST['submit']) || !isset($_POST['email'])) {
            self::default();
            exit();
        }

        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

        if (empty($email)) {
            $_SESSION['error'] = ["Veuillez renseigner une adresse email valide"];
            self::forgottenPassword();
            exit();
        }

        $user = UserManager::userExist($email);

        if ($user === null) {
            $_SESSION['error'] = ["L'adresse email n'existe pas"];
            self::forgottenPassword();
            exit();
        }

        $token = self::randomChars();
        NumberManager::addToken($user->getId(), $token);

        if (self::forgottenPasswordMail($email, $token, $user->getId())) {
            $_SESSION['error'] = ["Un email vous à été envoyé avec un lien pour réinitialiser votre mot de passe"];
            $_SESSION['color'] = Config::SUCCESS;
        } else {
            $_SESSION['error'] = ['Une erreur est survenu, veuillez réessayer plus tard'];
        }

        self::default();
    }

    /**
     * Send a mail with a link to change password
     * @param string $mail
     * @param string $token
     * @param int $id
     * @return bool
     */
    public function forgottenPasswordMail(string $mail, string $token, int $id): bool {
        $url = Config::APP_URL . '/index.php?c=connection&a=forgotten-password&token=' . $token . '&id=' . $id;
        $message = "
        <html lang='fr'>
            <head>
                <title>Changement de mot de passe</title>
            </head>
            <body>
                <span>Bonjour,</span>
                <p>
                    Une demande de nouveau mot de passe a été effectué sur votre compte Webtoon Library (annlio.com)
                    <br>
                    cliquer <a href='$url'>ici</a> et renseignez un nouveau mot de passe
                    <br>
                    s'il ne s'agit pas de vous, contactez rapidement le support : lizoe.lallier@net-c.com
                </p>
            </body>
        </html>
        ";

        $to = $mail;
        $subject = "Changement de mot de passe";
        $headers = [
            'Reply-to' => "no-reply@email.com",
            'X-Mailer' => 'PHP/' . phpversion(),
            'Mime-version' => '1.0',
            'Content-type' => 'text/html; charset=utf-8'
        ];

        return mail($to, $subject, $message, $headers, "-f no-reply@email.com");
    }

    /**
     * Set a new password if the old one was forgotten
     */
    public function setNewPassword() {
        if (isset($_SESSION['user'])) {
            self::render('home');
            exit();
        }

        if (!isset($_POST['submit']) || !isset($_POST['password']) || !isset($_POST['token'])|| !isset($_POST['id'])) {
            self::default();
            exit();
        }

        if (empty($_POST['token']) || empty($_POST['id'])) {
            self::default();
            exit();
        }

        $password = $_POST['password'];

        if(!preg_match('/^(?=.*[!@#$%^&*-\])(?=.*[0-9])(?=.*[A-Z]).{8,50}$/', $password)) {
            $_SESSION['error'] = ["Le mot de passe n'est pas assez sécurisé"];
            self::forgottenPassword($_POST['token'], $_POST['id']);
            exit();
        }

        if ($password === $_POST['passwordRepeat']) {

            $password = password_hash($password, PASSWORD_BCRYPT);

            if (UserManager::getUserById($_POST['id']) === null) {
                $_SESSION['error'] = ["Le lien n'est pas correcte"];
                self::forgottenPassword();
                exit();
            }

            if (UserManager::updatePassword($_POST['id'], $password)) {
                NumberManager::deleteToken($_POST['token']);
                $_SESSION['error'] = ["Mot de passe changé avec succès"];
                $_SESSION['color'] = Config::SUCCESS;
            } else {
                $_SESSION['error'] = ['Une erreur est survenu, veuillez réessayer plus tard'];
            }

        } else {
            $_SESSION['error'] = ["Les mots de passe ne corespondent pas"];
            self::forgottenPassword($_POST['token'], $_POST['id']);
            exit();
        }

        self::default();
        exit();
    }
}
