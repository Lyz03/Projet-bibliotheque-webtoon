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

    /**
     * Return a User following the given id
     * @param int $id
     * @return User|null
     */
    public function getUserById(int $id): ?User {
        $user = null;
        $query = DB::getConnection()->query("SELECT * FROM " . self::TABLE . " WHERE id = " . $id);

        if ($data = $query->fetch()) {
            $user = self::createUser($data);
        }

        return $user;
    }

    /**
     * Return all users
     * @return array
     */
    public function getAllUser(): array {
        $user = [];
        $query = DB::getConnection()->query("SELECT * FROM " . self::TABLE);

        if ($data = $query->fetchAll()) {
            foreach ($data as $value) {
                $user[] = self::createUser($value);
            }
        }

        return $user;
    }

    /**
     * Update a user's role
     * @param int $id
     * @param string $role
     * @return bool
     */
    public function updateRole(int $id, string $role): bool {
        $stmt = DB::getConnection()->prepare("UPDATE " . self::TABLE . " SET 
        role = :role WHERE id = :id");

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':role', $role);

        return $stmt->execute();
    }

    /**
     * Delete a user
     * @param int $id
     * @return bool
     */
    public function deleteUser(int $id): bool {
        $stmt = DB::getConnection()->prepare("DELETE FROM " . self::TABLE . " WHERE id = :id");

        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }
}