<?php

namespace App\Entity;

class Rating
{
    private int $id;
    private int $mark;
    private int $userId;
    private int $cardId;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Rating
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getMark(): int
    {
        return $this->mark;
    }

    /**
     * @param int $mark
     * @return Rating
     */
    public function setMark(int $mark): self
    {
        $this->mark = $mark;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return Rating
     */
    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return int
     */
    public function getCardId(): int
    {
        return $this->cardId;
    }

    /**
     * @param int $cardId
     * @return Rating
     */
    public function setCardId(int $cardId): self
    {
        $this->cardId = $cardId;
        return $this;
    }
}