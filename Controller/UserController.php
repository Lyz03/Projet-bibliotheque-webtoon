<?php

namespace App\Controller;

use App\Manager\UserManager;

class UserController extends AbstractController
{

    public function default()
    {
        if (isset($_SESSION['user'])) {
            self::render('user/account', $data = ['user' => $_SESSION['user']]);
            exit();
        }

        self::render('connection-inscription/connection');
    }

    /**
     * Delete a User
     * @param int $id
     */
    public function deleteUser(int $id) {
        if (!isset($_SESSION['user'])) {
            self::default();
            exit();
        }

        $userManager = new UserManager();
        $var = $userManager->deleteUser($id);

        if ($var) {
            ConnectionController::logOut();
            exit();
        }

        self::default();
    }
}