<?php

namespace App\Controller;

use App\Config;
use App\Manager\ListManager;
use App\Manager\UserManager;

class UserController extends AbstractController
{

    public function default()
    {
        if (isset($_SESSION['user'])) {
            $listManager = new ListManager();

            $list = [];

            foreach (Config::DEFAULT_LIST as $value) {
                $list[$value] = $listManager->getTreeCardlist($value, $_SESSION['user']->getId());
            }

            self::render('user/account', $data = [
                'user' => $_SESSION['user'],
                'list' => $list,
            ]);
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