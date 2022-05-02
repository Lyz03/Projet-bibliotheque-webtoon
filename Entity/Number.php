<?php

namespace App\Entity;

use DateTime;

class Number
{

    private int $id;
    private int $number;
    private DateTime $time;
    private User $user;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Number
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @param int $number
     * @return Number
     */
    public function setNumber(int $number): self
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getTime(): DateTime
    {
        return $this->time;
    }

    /**
     * @param DateTime $time
     * @return Number
     */
    public function setTime(DateTime $time): self
    {
        $this->time = $time;
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
     * @return Number
     */
    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }
}