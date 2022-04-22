<?php

namespace App\Controller;

use App\Manager\CardManager;
use App\Manager\RatingManager;

class HomeController extends AbstractController
{
    public function default()
    {
        $cardManager = new CardManager();
        $ratingManger = new RatingManager();

        self::render('home', $data = [
            'popular' => $cardManager->getPopularCards($ratingManger->getRatingForCards()),
            'recent' => $cardManager->getLastCards(),
        ]);
    }
}
