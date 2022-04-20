<?php

namespace App\Entity;

class Card
{
    private int $id;
    private string $title;
    private string $script;
    private string $drawing;
    private int $date_start;
    private int $date_end;
    private string $synopsis;
    private string $type;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Card
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Card
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getScript(): string
    {
        return $this->script;
    }

    /**
     * @param string $script
     * @return Card
     */
    public function setScript(string $script): self
    {
        $this->script = $script;
        return $this;
    }

    /**
     * @return string
     */
    public function getDrawing(): string
    {
        return $this->drawing;
    }

    /**
     * @param string $drawing
     * @return Card
     */
    public function setDrawing(string $drawing): self
    {
        $this->drawing = $drawing;
        return $this;
    }

    /**
     * @return int
     */
    public function getDateStart(): int
    {
        return $this->date_start;
    }

    /**
     * @param int $date_start
     * @return Card
     */
    public function setDateStart(int $date_start): self
    {
        $this->date_start = $date_start;
        return $this;
    }

    /**
     * @return int
     */
    public function getDateEnd(): int
    {
        return $this->date_end;
    }

    /**
     * @param int $date_end
     * @return Card
     */
    public function setDateEnd(int $date_end): self
    {
        $this->date_end = $date_end;
        return $this;
    }

    /**
     * @return string
     */
    public function getSynopsis(): string
    {
        return $this->synopsis;
    }

    /**
     * @param string $synopsis
     * @return Card
     */
    public function setSynopsis(string $synopsis): self
    {
        $this->synopsis = $synopsis;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Card
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }
}