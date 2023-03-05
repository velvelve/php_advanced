<?php

namespace GeekBrains\LevelTwo\Blog;

class Post
{

    private UUID $uuid;
    private User $author;
    private string $title;
    private string $text;

    public function __construct(UUID $uuid, User $author, string $title, string $text)
    {
        $this->uuid = $uuid;
        $this->author = $author;
        $this->title = $title;
        $this->text = $text;
    }


    /**
     * Get the value of uuid
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Get the value of uuid
     */
    public function setUuid(UUID $uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * Get the value of author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set the value of author
     *
     * @return  self
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get the value of text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set the value of text
     *
     * @return  self
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get the value of title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function __toString()
    {
        return "Пользователь с логином " . $this->author->getUsername() . " написал статью $this->title следующего содержания:\n $this->text" . PHP_EOL;
    }
}
