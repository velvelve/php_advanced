<?php

namespace GeekBrains\LevelTwo\Blog;

class Comment
{

    private UUID $uuid;
    private User $author;
    private Post $post;
    private string $text;

    public function __construct(UUID $uuid, User $author, Post $post, string $text)
    {
        $this->uuid = $uuid;
        $this->author = $author;
        $this->post = $post;
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
     * Get the value of post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set the value of post
     *
     * @return  self
     */
    public function setPost($post)
    {
        $this->post = $post;

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

    public function __toString()
    {
        return "Пользователь с логином " . $this->author->getUsername() . " написал комментарий к статье " . $this->post->getTitle() . " следующего содержания:\n $this->text" . PHP_EOL;
    }
}
