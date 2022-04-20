<?php

namespace App\Controller;

class CardController extends AbstractController
{
    public function default()
    {
        self::render('card/card');
    }
}

