<?php

namespace App\Manager;

use App\DB;
use App\Entity\Card;

class CardManager
{

    public const TABLE = 'card';

    /**
     * Create a new Card Entity
     * @param array $data
     * @return Card
     */
    private static function createCard(array $data): Card
    {
        return (new Card())
            ->setId($data['id'])
            ->setTitle($data['title'])
            ->setScript($data['script'])
            ->setDrawing($data['drawing'])
            ->setDateStart($data['dateStart'])
            ->setDateEnd($data['dateEnd'])
            ->setSynopsis($data['synopsis'])
            ->setType($data['type'])
            ->setImage($data['image'])
            ;
    }

    public function addCard(string $title, string $script, string $drawing, int $dateStart, int $dateEnd,
                            string $synopsis, string $type, string $image) {

        $stmt = DB::getConnection()->prepare("INSERT INTO " . self::TABLE .
            " (title, script, drawing, date_start, date_end, synopsis, type, image)
            VALUES (:title, :script, :drawing, :dateStart, :dateEnd, :synopsis, :type, :image)");

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':script', $script);
        $stmt->bindParam(':drawing', $drawing);
        $stmt->bindParam(':dateStart', $dateStart);
        $stmt->bindParam(':dateEnd', $dateEnd);
        $stmt->bindParam(':synopsis', $synopsis);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':image', $image);


        $stmt->execute();
    }
}