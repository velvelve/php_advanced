<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\LikeRepository;

use GeekBrains\LevelTwo\Blog\Like;
use GeekBrains\LevelTwo\Blog\UUID;

interface LikeRepositoryInterface
{
    public function save(Like $like): void;
    public function getByPostUuid(UUID $uuid): array;
    public function checkUserLikeForPostExists(string $postUuid, string $userUuid): void;
}
