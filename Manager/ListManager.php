<?php

namespace App\Manager;

use App\DB;
use App\Entity\WebtoonList;

class ListManager
{

    public const TABLE = 'wtl_list';

    /**
     * Create a WebtoonList Entity
     * @param array $data
     * @return WebtoonList
     */
    private static function createList(array $data): WebtoonList
    {
        $userManager = new UserManager();
        $cardManager = new CardManager();

        return (new WebtoonList())
            ->setId($data['id'])
            ->setName($data['name'])
            ->setVisibility($data['visibility'])
            ->setUser($userManager->getUserById($data['user_id']))
            ->setCard($cardManager->getCardById($data['card_id']))
            ;
    }

    /**
     * Return the mark for a card from a certain user
     * @param int $cardId
     * @param int $userId
     * @return WebtoonList|null
     */
    public static function getListByUserCard(int $cardId, int $userId): ?array {
        $list = null;
        $stmt = DB::getConnection()->prepare("SELECT * FROM " . self::TABLE . " 
                WHERE card_id = :cardId AND user_id = :userId");

        $stmt->bindParam(':cardId', $cardId);
        $stmt->bindParam(':userId', $userId);

        if ($stmt->execute() && $data = $stmt->fetchAll()) {
            foreach ($data as $value) {
                $list[] = self::createList($value);
            }
        }

        return $list;
    }

    /**
     * Return the last 3 cards from a given list
     * @param string $name
     * @param int $userId
     * @return array
     */
    public static function getTreeCardList(string $name, int $userId): array {
        $list = [];
        $stmt = DB::getConnection()->prepare("SELECT * FROM " . self::TABLE . " 
                WHERE name = :name AND user_id = :userId ORDER BY id DESC LIMIT 3");

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':userId', $userId);

        if ($stmt->execute() && $data = $stmt->fetchAll()) {
            foreach ($data as $value) {
                $list[] = self::createList($value);
            }
        }

        return $list;
    }

    /**
     * Return cards from a given list
     * @param string $name
     * @param int $userId
     * @return array
     */
    public static function getCardFromList(string $name,int $userId): array {
        $list = [];
        $stmt = DB::getConnection()->prepare("SELECT * FROM " . self::TABLE . " 
                WHERE name = :name AND user_id = :userId ORDER BY id DESC");

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':userId', $userId);

        if ($stmt->execute() && $data = $stmt->fetchAll()) {
            foreach ($data as $value) {
                $list[] = self::createList($value);
            }
        }

        return $list;
    }

    /**
     * Insert a new list entry
     * @param string $name
     * @param int $visibility
     * @param int $userId
     * @param int $cardId
     * @return bool
     */
    public static Function addList(string $name, int $visibility, int $userId, int $cardId): bool {
        $stmt = DB::getConnection()->prepare("INSERT INTO " . self::TABLE . " (name, visibility, user_id, card_id)
            VALUES (:name, :visibility, :userId, :cardId)");

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':visibility', $visibility);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':cardId', $cardId);

        return $stmt->execute();
    }

    /**
     * Remove a list entry
     * @param int $userId
     * @param int $cardId
     * @param string $listName
     * @return bool
     */
    public static function removeList(int $userId, int $cardId, string $listName): bool {
        $stmt = DB::getConnection()->prepare("DELETE FROM " . self::TABLE . " WHERE name = :name AND user_id = :userId AND card_id = :cardId");

        $stmt->bindParam(':name', $listName);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':cardId', $cardId);

        return $stmt->execute();
    }
}