<?php

namespace App\Manager;

use App\DB;
use App\Entity\Rating;

class RatingManager
{

    public const TABLE = 'rating';

    /**
     * Create a new Rating Entity
     * @param array $data
     * @return Rating
     */
    private static function createRating(array $data): Rating
    {
        $userManager = new UserManager();
        $cardManager = new CardManager();

        return (new Rating())
            ->setId($data['id'])
            ->setMark($data['mark'])
            ->setUser($userManager->getUserById($data['user_id']))
            ->setCard($cardManager->getCardById($data['card_id']))
            ;
    }

    /**
     * Return the 7 most popular Card id
     * @return array
     */
    public function getRatingForCards(): array {
        $db = DB::getConnection();

        // get Cards id
        $id = [];
        $query = $db->query("SELECT DISTINCT card_id FROM " . self::TABLE);

        if ($data = $query->fetchAll()) {
            foreach ($data as $value) {
                $id[] = $value;
            }
        }

        // get the average of review for each card
        $review = [];
        foreach ($id as $value) {
            $query = $db->query("SELECT AVG(mark) FROM " . self::TABLE . " WHERE id = " . $value['card_id']);

            if ($data = $query->fetchAll()) {
                foreach ($data as $item) {
                    $review[$value['card_id']] = $item['AVG(mark)'];
                }
            }
        }

        arsort($review, SORT_NATURAL);

        return array_slice(array_keys($review), 0, 3);
    }

}