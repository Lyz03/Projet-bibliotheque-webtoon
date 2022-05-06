<?php

namespace App\Manager;

use App\Config;
use App\DB;
use App\Entity\Rating;

class RatingManager
{

    public const TABLE = 'wtl_rating';

    /**
     * Return the most popular Card id
     * @param bool $seven
     * @param int $offset
     * @return array
     */
    public function getRatingForCards(bool $seven = true, int $offset = 0): array {
        $db = DB::getConnection();

        // get Cards id
        $id = [];
        $query = $db->query("SELECT DISTINCT card_id FROM " . self::TABLE .
                " LIMIT " . Config::CARD_LIMIT . " OFFSET $offset");

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
    public function getRatingByCardId(int $id): ?float {
        $review = null;
        $query = DB::getConnection()->query("SELECT AVG(mark) FROM " . self::TABLE . " WHERE card_id = $id");

        if ($data = $query->fetch()) {
            $review = $data['AVG(mark)'];
        }

        return $review;
    }

    /**
     * Return the mark for a card from a certain user
     * @param int $cardId
     * @param int $UserId
     * @return int|null
     */
    public function getRatingByUserCard(int $cardId, int $UserId): ?int {
        $review = null;
        $query = DB::getConnection()->query("SELECT mark FROM " . self::TABLE . " 
                WHERE card_id = $cardId AND user_id = $UserId");

        if ($data = $query->fetch()) {
            $review = $data['mark'];
        }

        return $review;
    }

    /**
     * Count all cards which have rating
     * @return int
     */
    public function getCardRatingNb(): int {
        $query = DB::getConnection()->query("SELECT DISTINCT card_id FROM " . self::TABLE);

        return count($query->fetchAll());
    }

    /**
     * Insert a new rating
     * @param int $mark
     * @param int $userId
     * @param int $cardId
     * @return bool
     */
    public Function addRating(int $mark, int $userId, int $cardId): bool {
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
    public function updateRating(int $mark, int $userId, int $cardId): bool {

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
    public function deleteRating(int $userId, int $cardId): bool {
        $stmt = DB::getConnection()->prepare("DELETE FROM " . self::TABLE . " WHERE user_id = :userId AND card_id = :cardId");

        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':cardId', $cardId);

        return $stmt->execute();
    }
}