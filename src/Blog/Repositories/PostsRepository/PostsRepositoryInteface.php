<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\PostsRepository;

use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\UUID;

interface PostsRepositoryInteface
{
    public function save(Post $post): void;
    public function get(UUID $uuid): Post;
    public function deletePost(UUID $uuid): void;
}
