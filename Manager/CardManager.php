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
            ->setDateStart($data['date_start'])
            ->setDateEnd($data['date_end'])
            ->setSynopsis($data['synopsis'])
            ->setType($data['type'])
            ->setImage($data['image'])
            ;
    }

    /**
     * Insert a new Card
     * @param string $title
     * @param string $script
     * @param string $drawing
     * @param int $dateStart
     * @param int $dateEnd
     * @param string $synopsis
     * @param string $type
     * @param string $image
     * @return int
     */
    public function addCard(string $title, string $script, string $drawing, int $dateStart, int $dateEnd,
                            string $synopsis, string $type, string $image): int
    {

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

        return DB::getConnection()->lastInsertId();
    }

    /**
     * Return a Card following the given id
     * @param int $id
     * @return Card|null
     */
    public function getCardById(int $id): ?Card {
        $card = null;
        $query = DB::getConnection()->query("SELECT * FROM " . self::TABLE . " WHERE id = " . $id);

        if ($data = $query->fetch()) {
            $card = self::createCard($data);
        }

        return $card;
    }
}