<?php

namespace App\Manager;

use App\DB;
use App\Entity\Comment;

class CommentManager
{

    public const TABLE = 'wtl_comment';

    /**
     * Create a new Comment Entity
     * @param array $data
     * @return Comment
     */
    private static function createComment(array $data): Comment
    {
        $userManager = new UserManager();
        $cardManager = new CardManager();

        return (new Comment())
            ->setId($data['id'])
            ->setContent($data['content'])
            ->setValidate($data['validate'])
            ->setUser($userManager->getUserById($data['user_id']))
            ->setCard($cardManager->getCardById($data['card_id']))
            ;
    }

    /**
     * Get all comments for a given card and its visibility
     * @param int $cardId
     * @param int $validate
     * @return array
     */
    public function getCommentByCardIdValidate(int $cardId, int $validate): array {
        $comments = [];
        $stmt = DB::getConnection()->prepare("SELECT * FROM " . self::TABLE . " 
                WHERE card_id = :cardId AND validate = :validate ORDER BY id DESC");

        $stmt->bindParam(':cardId', $cardId);
        $stmt->bindParam(':validate', $validate);

        if ($stmt->execute() && $data = $stmt->fetchAll()) {
            foreach ($data as $value) {
                $comments[] = self::createComment($value);
            }
        }

        return $comments;
    }

    /**
     * Get all unvalidated comments
     * @return array
     */
    public function getUnvalidatedComment():array {
        $comments = [];
        $query = DB::getConnection()->query("SELECT * FROM " . self::TABLE . " 
                WHERE validate = 0");

        if ($data = $query->fetchAll()) {
            foreach ($data as $value) {
                $comments[] = self::createComment($value);
            }
        }

        return $comments;
    }

    /**
     * Insert a new comment
     * @param string $content
     * @param int $userId
     * @param int $cardId
     * @return bool
     */
    public function addComment(string $content, int $userId, int $cardId): bool {
        $stmt = DB::getConnection()->prepare("INSERT INTO " . self::TABLE . " (content, validate, user_id, card_id)
            VALUES (:content, :validate, :userId, :cardId)");

        $stmt->bindParam(':content', $content);
        $stmt->bindValue(':validate', 0);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':cardId', $cardId);

        return $stmt->execute();
    }

    /**
     * Validate a comment
     * @param int $id
     * @return bool
     */
    public function validateComment(int $id): bool {
        $stmt = DB::getConnection()->prepare("UPDATE " . self::TABLE . " SET 
        validate = :validate WHERE id = :id");

        $stmt->bindParam(':id', $id);
        $stmt->bindValue(':validate', 1);

        return $stmt->execute();
    }

    /**
     * delete a comment
     * @param int $id
     * @return bool
     */
    public function deleteComment(int $id): bool {
        $stmt = DB::getConnection()->prepare("DELETE FROM " . self::TABLE . " WHERE id = :id");

        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }
}