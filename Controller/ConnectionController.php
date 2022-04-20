<?php

namespace App\Controller;

class ConnectionController extends AbstractController
{
    public function default()
    {
        self::render('connection-inscription/connection');
    }
}
