<?php

namespace App\Manager;

use App\Config;
use App\DB;
use App\Entity\Card;

class CardManager
{

    public const TABLE = Config::PREFIX . 'card';

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
     * Return the 7 last cards
     * @return array
     */
    public static function getLastCards(): array {
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
    public static function getPopularCards(array $id): array {
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
     * @param int $offset
     * @param string $orderBy
     * @return array
     */
    public static function getAllCards( int $offset = 0, string $orderBy = 'DESC'): array {
        $cards = [];
        $query = DB::getConnection()->query("SELECT * FROM " . self::TABLE . " ORDER BY id $orderBy 
                LIMIT " . Config::CARD_LIMIT . " OFFSET $offset");

        if ($data = $query->fetchAll()) {
            foreach ($data as $value) {
                $cards[] = self::createCard($value);
            }
        }

        return $cards;
    }

    /**
     * Return the number of cards
     * @return int
     */
    public static function getCardNb(): int {
        $query = DB::getConnection()->query("SELECT COUNT(*) FROM " . self::TABLE);

        return $query->fetch()['COUNT(*)'];
    }

    /**
     * Return the number of cards having $type for type
     * @param string $type
     * @return int
     */
    public static function getCardNbByType(string $type): int {
        $query = DB::getConnection()->query("SELECT COUNT(*) FROM " . self::TABLE . " WHERE type LIKE '%$type%'");

        return $query->fetch()['COUNT(*)'];
    }

    /**
     * Get cards by type
     * @param string $type
     * @param int $offset
     * @param string $orderBy
     * @return array
     */
    public static function getCardByType(string $type, int $offset = 0, string $orderBy = 'DESC'): array {
        $cards = [];
        $query = DB::getConnection()->query("SELECT * FROM " . self::TABLE . " WHERE type LIKE '%$type%' ORDER 
                BY id $orderBy LIMIT " . Config::CARD_LIMIT . " OFFSET $offset");

        if ($data = $query->fetchAll()) {
            foreach ($data as $value) {
                $cards[] = self::createCard($value);
            }
        }

        return $cards;
    }


    /**
     * Get 5 cards by title like search
     * @param string $search
     * @return array
     */
    public static function getCardNameThatContain(string $search): array {
        $cards = [];
        $stmt = DB::getConnection()->prepare("SELECT title, id FROM " . self::TABLE . " WHERE LOWER(title) 
        LIKE LOWER(:search) ORDER BY id DESC LIMIT 5");

        $stmt->bindValue(':search', '%' . $search . '%');

        if ($stmt->execute() && $data = $stmt->fetchAll()) {
            foreach ($data as $value) {
                $cards[] = (new Card)
                    ->setId($value['id'])
                    ->setTitle($value['title'])
                ;
            }
        }
        return $cards;
    }

    /**
     * Get cards by title, script or drawing like search
     * @param string $search
     * @param int $offset
     * @return array
     */
    public static function getCardBySearch(string $search, int $offset = 0): array {
        $cards = [];
        $stmt = DB::getConnection()->prepare("SELECT * FROM " . self::TABLE . " WHERE LOWER(title) LIKE LOWER(:search) 
        OR LOWER(script) LIKE LOWER(:search) OR LOWER(drawing) LIKE LOWER(:search) ORDER BY id DESC 
        LIMIT " . Config::CARD_LIMIT . " OFFSET $offset");

        $stmt->bindValue(':search', '%' . $search . '%');

        if ($stmt->execute() && $data = $stmt->fetchAll()) {
            foreach ($data as $value) {
                $cards[] = self::createCard($value)
                ;
            }
        }
        return $cards;
    }

    /**
     * Count the card that have been searched
     * @param string $search
     * @return int
     */
    public static function getSearchCardNb(string $search): int {
        $stmt = DB::getConnection()->prepare("SELECT COUNT(*) FROM " . self::TABLE . " WHERE LOWER(title) 
            LIKE LOWER(:search) OR LOWER(script) LIKE LOWER(:search) OR LOWER(drawing) LIKE LOWER(:search) 
            ORDER BY id DESC");

        $stmt->bindValue(':search', '%' . $search . '%');

        $stmt->execute();

        return $stmt->fetch()['COUNT(*)'];
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
    public static function addCard(string $title, string $script, string $drawing, int $dateStart, int $dateEnd,
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
    public static function updateCard(int $id, string $title, string $script, string $drawing, int $dateStart, int $dateEnd,
                            string $synopsis, string $type, string $image): int {

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

    public static function deleteCard(int $id): bool {
        $stmt = DB::getConnection()->prepare("DELETE FROM " . self::TABLE . " WHERE id = :id");

        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    /**
     * Return a Card following the given id
     * @param int $id
     * @return Card|null
     */
    public static function getCardById(int $id): ?Card {
        $card = null;
        $stmt = DB::getConnection()->prepare("SELECT * FROM " . self::TABLE . " WHERE id = :id");

        $stmt->bindParam(':id', $id);

        if ($stmt->execute() && $data = $stmt->fetch()) {
            $card = self::createCard($data);
        }

        return $card;
    }
}