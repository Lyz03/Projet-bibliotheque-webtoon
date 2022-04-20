<?php

namespace App\Entity;

class WebtoonList
{
    private int $id;
    private string $name;
    private bool $visibility;
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
     * @return WebtoonList
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return WebtoonList
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return bool
     */
    public function isVisibility(): bool
    {
        return $this->visibility;
    }

    /**
     * @param bool $visibility
     * @return WebtoonList
     */
    public function setVisibility(bool $visibility): self
    {
        $this->visibility = $visibility;
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
     * @return WebtoonList
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
     * @return WebtoonList
     */
    public function setCardId(int $cardId): self
    {
        $this->cardId = $cardId;
        return $this;
    }
}