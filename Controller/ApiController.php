<?php

namespace App\Controller;

use App\Manager\CardManager;

class ApiController extends AbstractController
{

    public function default()
    {
        $array = [];

        $payload = file_get_contents('php://input');
        $payload = json_decode($payload);

        foreach(CardManager::getCardNameThatContain($payload->search) as $value) {
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