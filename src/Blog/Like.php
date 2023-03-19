<?php

namespace GeekBrains\LevelTwo\Blog;


class Like
{
    public function __construct(
        private UUID $uuid,
        private UUID $postUuid,
        private UUID $userUuid,
    ) {
    }


    /**
     * Get the value of post_id
     */
    public function getPostId()
    {
        return $this->postUuid;
    }

    /**
     * Set the value of post_id
     *
     * @return  self
     */
    public function setPostId($post_id)
    {
        $this->postUuid = $post_id;

        return $this;
    }

    /**
     * Get the value of user_id
     */
    public function getUserId()
    {
        return $this->userUuid;
    }

    /**
     * Set the value of user_id
     *
     * @return  self
     */
    public function setUserId($user_id)
    {
        $this->userUuid = $user_id;

        return $this;
    }

    /**
     * Get the value of uuid
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Set the value of uuid
     *
     * @return  self
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }
}
