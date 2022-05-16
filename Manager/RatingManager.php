<?php

namespace App\Manager;

use App\Config;
use App\Controller\CardController;
use App\DB;
use App\Entity\Rating;

class RatingManager
{

    public const TABLE = Config::PREFIX . 'rating';

    /**
     * Return the most popular Card id
     * @param bool $seven
     * @param int $offset
     * @param bool $limit
     * @return array
     */
    public static function getRatingForCards(bool $seven = true, int $offset = 0, bool $limit = true): array {
        $db = DB::getConnection();

        // get Cards id
        $id = [];
        if ($limit) {
            if ($seven) {
                $query = $db->query("SELECT DISTINCT card_id FROM " . self::TABLE .
                    " LIMIT 7 OFFSET $offset");

            } else {
                $query = $db->query("SELECT DISTINCT card_id FROM " . self::TABLE .
                    " LIMIT " . Config::CARD_LIMIT . " OFFSET $offset");
            }
        } else {
            $query = $db->query("SELECT DISTINCT card_id FROM " . self::TABLE);
        }

        if ($data = $query->fetchAll()) {
            foreach ($data as $value) {
                $id[] = $value;
            }
        }

        // get the average of review for each card
        $review = [];
        foreach ($id as $value) {
            $query = $db->query("SELECT AVG(mark) FROM " . self::TABLE . " WHERE card_id = " . $value['card_id']);

            if ($data = $query->fetchAll()) {
                foreach ($data as $item) {
                    $review[$value['card_id']] = $item['AVG(mark)'];
                }
            }
        }

        arsort($review, SORT_NATURAL);

        if ($seven) {
            return array_slice(array_keys($review), 0, 7);
        }

        return array_keys($review);
    }



    /**
     * Return the mark average for a card following the given id
     * @param int $id
     * @return float|null
     */
    public static function getRatingByCardId(int $id): ?float {
        $review = null;
        $stmt = DB::getConnection()->prepare("SELECT AVG(mark) FROM " . self::TABLE . " WHERE card_id = :cardId");

        $stmt->bindParam(':cardId', $id);

        if ($stmt->execute() && $data = $stmt->fetch()) {
            $review = $data['AVG(mark)'];
        }

        return $review;
    }

    /**
     * Return the mark for a card from a certain user
     * @param int $cardId
     * @param int $userId
     * @return int|null
     */
    public static function getRatingByUserCard(int $cardId, int $userId): ?int {
        $review = null;
        $stmt = DB::getConnection()->prepare("SELECT mark FROM " . self::TABLE . " 
                WHERE card_id = :cardId AND user_id = :userId");

        $stmt->bindParam('cardId', $cardId);
        $stmt->bindParam('userId', $userId);

        if ($stmt->execute() && $data = $stmt->fetch()) {
            $review = $data['mark'];
        }

        return $review;
    }

    /**
     * Count all cards which have rating
     * @return int
     */
    public static function getCardRatingNb(): int {
        $query = DB::getConnection()->query("SELECT DISTINCT card_id FROM " . self::TABLE);

        return count($query->fetchAll());
    }

    /**
     * Count All card which have a rating and a given type
     * @param string $type
     * @return int
     */
    public static function getRatingNbByType(string $type): int {
        $stmt = DB::getConnection()->prepare("SELECT DISTINCT wtl_rating.card_id FROM wtl_rating
             INNER JOIN wtl_card ON wtl_rating.card_id = wtl_card.id WHERE wtl_card.type LIKE :type");

        $stmt->bindValue(':type', '%' . $type . '%');

        if ($stmt->execute()) {
            return count($stmt->fetchAll());
        }

        return 0;
    }

    /**
     * Insert a new rating
     * @param int $mark
     * @param int $userId
     * @param int $cardId
     * @return bool
     */
    public static Function addRating(int $mark, int $userId, int $cardId): bool {
        $stmt = DB::getConnection()->prepare("INSERT INTO " . self::TABLE . " (mark, user_id, card_id)
            VALUES (:mark, :userId, :cardId)");

        $stmt->bindParam(':mark', $mark);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':cardId', $cardId);

        return $stmt->execute();
    }

    /**
     * Update a rating
     * @param int $mark
     * @param int $userId
     * @param int $cardId
     * @return bool
     */
    public static function updateRating(int $mark, int $userId, int $cardId): bool {

        $stmt = DB::getConnection()->prepare("UPDATE " . self::TABLE . " SET 
        mark = :mark WHERE user_id = :userId AND card_id = :cardId");

        $stmt->bindParam(':mark', $mark);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':cardId', $cardId);

        return $stmt->execute();
    }

    /**
     * Delete a rating
     * @param int $userId
     * @param int $cardId
     * @return bool
     */
    public static function deleteRating(int $userId, int $cardId): bool {
        $stmt = DB::getConnection()->prepare("DELETE FROM " . self::TABLE . " WHERE user_id = :userId AND card_id = :cardId");

        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':cardId', $cardId);

        return $stmt->execute();
    }
}