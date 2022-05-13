<?php

namespace App\Controller;

use App\Manager\CardManager;
use App\Manager\RatingManager;

class HomeController extends AbstractController
{
    public function default()
    {
        self::render('home', $data = [
            'popular' => CardManager::getPopularCards(RatingManager::getRatingForCards()),
            'recent' => CardManager::getLastCards(),
        ]);
    }

    public function confidentiality() {
        self::render('user/confidentiality');
    }
}
