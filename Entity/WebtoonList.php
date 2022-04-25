<?php

namespace App\Entity;

class WebtoonList
{
    private int $id;
    private string $name;
    private bool $visibility;
    private User $user;
    private Card $card;

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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return WebtoonList
     */
    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Card
     */
    public function getCard(): Card
    {
        return $this->card;
    }

    /**
     * @param Card $card
     * @return WebtoonList
     */
    public function setCard(Card $card): self
    {
        $this->card = $card;
        return $this;
    }
}