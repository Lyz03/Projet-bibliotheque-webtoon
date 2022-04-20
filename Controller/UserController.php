<?php

namespace App\Controller;

class UserController extends AbstractController
{

    public function default()
    {
        if (isset($_SESSION['user'])) {
            self::render('user/account');
            exit();
        }

        self::render('connection-inscription/connection');
    }
}