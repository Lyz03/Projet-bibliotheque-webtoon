<?php

namespace App\Manager;

use App\DB;
use App\Entity\Number;
use DateTime;

class NumberManager
{

    public const TABLE = 'wtl_number';

    /**
     * Create a new Number Entity
     * @param array $data
     * @return Number
     */
    private static function createNumber(array $data): Number
    {
        $userManager = new UserManager();

        return (new Number())
            ->setId($data['id'])
            ->setNumber($data['number'])
            ->setTime(DateTime::createFromFormat("Y-m-d H:i:s", $data['time']))
            ->setUser($userManager->getUserById($data['user_id']))
            ;
    }

    /**
     * Get a number by a user id
     * @param int $userId
     * @return Number|null
     */
    public function getNumberByUserId(int $userId): ?Number {
        $number = null;
        $query = DB::getConnection()->query("SELECT * FROM " . self::TABLE . " 
                WHERE user_id = $userId");

        if ($data = $query->fetch()) {
            $number = self::createNumber($data);
        }

        return $number;
    }

    /**
     * Add a number
     * @param int $userId
     * @param int $number
     * @return bool
     */
    public function addNumber(int $userId, int $number): bool {
        $stmt = DB::getConnection()->prepare("INSERT INTO " . self::TABLE . " (number, user_id)
            VALUES (:number, :userId)");

        $stmt->bindParam(':number', $number);
        $stmt->bindParam(':userId', $userId);

        return $stmt->execute();
    }

    /**
     * Delete a number
     * @param int $userId
     * @return bool
     */
    public function deleteNumber(int $userId): bool {
        $stmt = DB::getConnection()->prepare("DELETE FROM " . self::TABLE . " WHERE user_id = :userId");

        $stmt->bindParam(':userId', $userId);

        return $stmt->execute();
    }
}