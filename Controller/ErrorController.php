<?php

namespace App\Controller;

class ErrorController extends AbstractController
{
    public function default()
    {
        self::render('error/404');
    }
}