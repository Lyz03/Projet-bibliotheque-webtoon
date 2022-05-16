<?php

namespace App\Manager;

use App\Config;
use App\DB;
use App\Entity\Number;
use DateTime;

class NumberManager
{

    public const TABLE = Config::PREFIX . 'number';

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
            ->setToken($data['token'])
            ->setTime(DateTime::createFromFormat("Y-m-d H:i:s", $data['time']))
            ->setUser($userManager->getUserById($data['user_id']))
            ;
    }

    /**
     * Get a number by a user id
     * @param int $userId
     * @return Number|null
     */
    public static function getNumberByUserId(int $userId): ?Number {
        $number = null;
        $stmt = DB::getConnection()->prepare("SELECT * FROM " . self::TABLE . " 
                WHERE user_id = :userId");

        $stmt->bindParam(':userId', $userId);

        if ($stmt->execute() && $data = $stmt->fetch()) {
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
    public static function addNumber(int $userId, int $number): bool {
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
    public static function deleteNumber(int $userId): bool {
        $stmt = DB::getConnection()->prepare("DELETE FROM " . self::TABLE . " WHERE user_id = :userId");

        $stmt->bindParam(':userId', $userId);

        return $stmt->execute();
    }

    /**
     * Get a user's token
     * @param int $userId
     * @return Number|null
     */
    public static function getTokenByUserId(int $userId): ?Number {
        $token = null;
        $stmt = DB::getConnection()->prepare("SELECT * FROM " . self::TABLE . " 
                WHERE user_id = :userId");

        $stmt->bindParam(':userId', $userId);

        if ($stmt->execute() && $data = $stmt->fetch()) {
            $token = self::createNumber($data);
        }

        return $token;
    }


    /**
     * Add a token
     * @param int $userId
     * @param string|null $token
     * @return bool
     */
    public static function addToken(int $userId, ?string $token): bool {
        $stmt = DB::getConnection()->prepare("INSERT INTO " . self::TABLE . " (token, user_id)
            VALUES (:token, :userId)");

        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':userId', $userId);

        return $stmt->execute();
    }
}