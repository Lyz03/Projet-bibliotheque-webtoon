<?php

namespace App\Manager;

use App\DB;
use App\Entity\User;

class UserManager
{
    public const TABLE = 'user';

    /**
     * Create a new User Entity
     * @param array $data
     * @return User
     */
    private static function createUser(array $data): User
    {
        return (new User())
            ->setId($data['id'])
            ->setEmail($data['email'])
            ->setUsername($data['username'])
            ->setPassword($data['password'])
            ->setRole($data['role'])
            ;
    }

    /**
     * Check if a user exist
     * @param string $mail
     * @return User|null
     */
    public function userExist(string $mail): ?User {
        $stmt = DB::getConnection()->prepare("SELECT * FROM " . self::TABLE . " WHERE email = :mail");

        $stmt->bindParam('mail', $mail);

        if ($stmt->execute() && $data = $stmt->fetch()) {
            return self::createUser($data);
        }

        return null;
    }

    /**
     * Register new user
     * @param string $mail
     * @param string $username
     * @param string $password
     */
    public function registerUser(string $mail, string $username, string $password) {

        $stmt = DB::getConnection()->prepare("INSERT INTO " . self::TABLE . " (email, username, password)
            VALUES (:email, :username, :password)");

        $stmt->bindParam(':email', $mail);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);

        $stmt->execute();
    }

}