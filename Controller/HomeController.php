<?php

namespace App\Controller;

use App\Manager\CardManager;
use App\Manager\RatingManager;

class HomeController extends AbstractController
{
    public function default()
    {
        $cardManager = new CardManager();
        $ratingManager = new RatingManager();

        self::render('home', $data = [
            'popular' => $cardManager->getPopularCards($ratingManager->getRatingForCards()),
            'recent' => $cardManager->getLastCards(),
        ]);
    }

    public function confidentiality() {
        self::render('user/confidentiality');
    }
}
