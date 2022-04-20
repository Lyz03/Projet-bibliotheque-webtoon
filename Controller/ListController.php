<?php

namespace App\Controller;

class ListController extends AbstractController
{
    public function default()
    {
        self::render('list/seeAll');
    }
}
