<?php

namespace App\Manager;

use App\DB;
use App\Entity\User;

class UserManager
{
    public const TABLE = 'wtl_user';

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
            ->setAvatar($data['avatar'])
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
        $stmt = DB::getConnection()->prepare("SELECT * FROM " . self::TABLE . " WHERE id = :id");

        $stmt->bindParam(':id', $id);

        if ($stmt->execute() && $data = $stmt->fetch()) {
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
     * Update the avatar
     * @param int $id
     * @param string $avatar
     * @return bool
     */
    public function updateAvatar(int $id, string $avatar): bool {
        $stmt = DB::getConnection()->prepare("UPDATE " . self::TABLE . " SET 
        avatar = :avatar WHERE id = :id");

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':avatar', $avatar);

        return $stmt->execute();
    }

    /**
     * Update the username
     * @param int $id
     * @param string $username
     * @return bool
     */
    public function updateUsername(int $id, string $username): bool {
        $stmt = DB::getConnection()->prepare("UPDATE " . self::TABLE . " SET 
        username = :username WHERE id = :id");

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':username', $username);

        return $stmt->execute();
    }

    /**
     * Update the email
     * @param int $id
     * @param string $email
     * @return bool
     */
    public function updateEmail(int $id, string $email): bool {
        $stmt = DB::getConnection()->prepare("UPDATE " . self::TABLE . " SET 
        email = :email WHERE id = :id");

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':email', $email);

        return $stmt->execute();
    }

    /**
     * Update the password
     * @param int $id
     * @param string $password
     * @return bool
     */
    public function updatePassword(int $id, string $password): bool {
        $stmt = DB::getConnection()->prepare("UPDATE " . self::TABLE . " SET 
        password = :password WHERE id = :id");

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':password', $password);

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