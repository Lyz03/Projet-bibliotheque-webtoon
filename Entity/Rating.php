<?php

namespace App\Entity;

class Rating
{
    private int $id;
    private int $mark;
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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Rating
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
     * @return Rating
     */
    public function setCard(Card $card): self
    {
        $this->card = $card;
        return $this;
    }
}