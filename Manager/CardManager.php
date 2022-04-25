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
     * Update a card
     * @param int $id
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
    public function updateCard(int $id, string $title, string $script, string $drawing, int $dateStart, int $dateEnd,
                            string $synopsis, string $type, string $image): int
    {

        $stmt = DB::getConnection()->prepare("UPDATE " . self::TABLE . " SET 
        title = :title, script = :script, drawing = :drawing, date_start = :dateStart, date_end = :dateEnd, 
        synopsis = :synopsis, type = :type, image = :image 
        WHERE id = :id");

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':script', $script);
        $stmt->bindParam(':drawing', $drawing);
        $stmt->bindParam(':dateStart', $dateStart);
        $stmt->bindParam(':dateEnd', $dateEnd);
        $stmt->bindParam(':synopsis', $synopsis);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':image', $image);

        return $stmt->execute();
    }

    public function deleteCard(int $id): bool {
        $stmt = DB::getConnection()->prepare("DELETE FROM " . self::TABLE . " WHERE id = :id");

        $stmt->bindParam(':id', $id);

        return $stmt->execute();
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

    /**
     * Return the 7 last cards
     * @return array
     */
    public function getLastCards(): array {
        $cards = [];
        $query = DB::getConnection()->query("SELECT * FROM " . self::TABLE . " ORDER BY id DESC LIMIT 7");

        if ($data = $query->fetchAll()) {
            foreach ($data as $value) {
                $cards[] = self::createCard($value);
            }
        }

        return $cards;
    }

    /**
     * Return the most popular cards
     * @param array $id
     * @return array
     */
    public function getPopularCards(array $id): array {
        $cards = [];
        foreach ($id as $value) {
            $query = DB::getConnection()->query("SELECT * FROM " . self::TABLE . " WHERE id = " . $value);

            if ($data = $query->fetchAll()) {
                foreach ($data as $item) {
                    $cards[] = self::createCard($item);
                }
            }
        }

        return $cards;
    }

    /**
     * Return all the Cards
     * @return array
     */
    public function getAllCards(): array {
        $cards = [];
        $query = DB::getConnection()->query("SELECT * FROM " . self::TABLE . " ORDER BY id DESC");

        if ($data = $query->fetchAll()) {
            foreach ($data as $value) {
                $cards[] = self::createCard($value);
            }
        }

        return $cards;
    }
}