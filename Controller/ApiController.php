<?php

namespace App\Controller;

use App\Manager\CardManager;

class ApiController extends AbstractController
{


    public function default()
    {
        $cardManager = new CardManager();
        $array = [];

        $payload = file_get_contents('php://input');
        $payload = json_decode($payload);

        foreach($cardManager->getAllCards() as $value) {
            $array[] = [
                'id' => $value->getId(),
            ];
        }

        http_response_code(200);
        echo json_encode($payload->search);

        exit;
    }
}