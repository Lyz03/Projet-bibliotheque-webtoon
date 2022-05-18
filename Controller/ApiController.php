<?php

namespace App\Controller;

use App\Manager\CardManager;

class ApiController extends AbstractController
{
    // suggestion
    public function default()
    {
        $array = [];

        $search = file_get_contents('php://input');
        $search = json_decode($search);

        foreach(CardManager::getCardNameThatContain(filter_var($search->search, FILTER_SANITIZE_STRING)) as $value) {
            $array[] = [
                'id' => $value->getId(),
                'title' => $value->getTitle(),
            ];
        }

        echo json_encode($array);

        http_response_code(200);
        exit;
    }
}