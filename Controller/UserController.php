<?php

namespace App\Controller;

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
}