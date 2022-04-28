<?php

namespace App\Manager;

use App\DB;
use App\Entity\Comment;

class CommentManager
{

    public const TABLE = 'comment';

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
     * Get all comments for a given card
     * @param int $cardId
     * @return array
     */
    public function getCommentByCardId(int $cardId): array {
        $comments = [];
        $query = DB::getConnection()->query("SELECT * FROM " . self::TABLE . " 
                WHERE card_id = $cardId ORDER BY id DESC");

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
     * @param $userId
     * @param $cardId
     * @return bool
     */
    public function addComment(string $content, $userId, $cardId): bool {
        $stmt = DB::getConnection()->prepare("INSERT INTO " . self::TABLE . " (content, validate, user_id, card_id)
            VALUES (:content, :validate, :userId, :cardId)");

        $stmt->bindParam(':content', $content);
        $stmt->bindValue(':validate', 0);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':cardId', $cardId);

        return $stmt->execute();
    }

}