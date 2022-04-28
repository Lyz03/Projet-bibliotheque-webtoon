<?php

namespace App\Entity;

class Comment
{
    private int $id;
    private string $content;
    private bool $validate;
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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Comment
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
     * @return Comment
     */
    public function setCard(Card $card): self
    {
        $this->card = $card;
        return $this;
    }

}