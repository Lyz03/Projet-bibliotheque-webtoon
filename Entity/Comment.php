<?php

namespace App\Entity;

class Comment
{
    private int $id;
    private string $content;
    private bool $validate;
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
     * @return Comment
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return Comment
     */
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return bool
     */
    public function getValidate(): bool
    {
        return $this->validate;
    }

    /**
     * @param bool $validate
     * @return Comment
     */
    public function setValidate(bool $validate): self
    {
        $this->validate = $validate;
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
     * @return Comment
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
     * @return Comment
     */
    public function setCardId(int $cardId): self
    {
        $this->cardId = $cardId;
        return $this;
    }

}