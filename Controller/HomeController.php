<?php

namespace App\Controller;

class HomeController extends AbstractController
{
    public function default()
    {
        self::render('home');
    }
}
