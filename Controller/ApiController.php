<?php

namespace App\Controller;

use App\Manager\CardManager;

class ApiController extends AbstractController
{
    // suggestion
    public function default()
    {
        $array = [];

        $payload = file_get_contents('php://input');
        $payload = json_decode($payload);

        foreach(CardManager::getCardNameThatContain(filter_var($payload->search, FILTER_SANITIZE_STRING)) as $value) {
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