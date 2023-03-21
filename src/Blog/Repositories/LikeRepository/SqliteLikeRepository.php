<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\LikeRepository;

use GeekBrains\LevelTwo\Blog\Exceptions\LikeAlreadyExists;
use GeekBrains\LevelTwo\Blog\Exceptions\LikeNotFoundException;
use GeekBrains\LevelTwo\Blog\Like;
use GeekBrains\LevelTwo\Blog\Repositories\LikeRepository\LikeRepositoryInterface;
use GeekBrains\LevelTwo\Blog\UUID;
use PDO;
use Psr\Log\LoggerInterface;

class SqliteLikeRepository implements LikeRepositoryInterface
{


    public function __construct(
        private PDO $connection,
        private LoggerInterface $logger,
    ) {
    }

    public function save(Like $like): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO likes (uuid, useruuid, postuuid) 
            VALUES (:uuid, :useruuid, :postuuid)
            '
        );
        $statement->execute([
            ':uuid' => (string) $like->getUuid(),
            ':useruuid' => (string) $like->getUserId(),
            ':postuuid' => (string) $like->getPostId()
        ]);
        $this->logger->info("Like saved: " . $like->getUuid());
    }

    public function getByPostUuid(UUID $uuid): array
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM likes WHERE postuuid = :uuid'
        );

        $statement->execute([
            ':uuid' => (string)$uuid
        ]);

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (!$result) {
            $this->logger->warning("Like with id $uuid not found");
            throw new LikeNotFoundException('No likes for post with uuid = ' . $uuid);
        }

        $likes = [];

        foreach ($result as $like) {
            $likes[] = new Like(
                new UUID($like['uuid']),
                new UUID($like['postuuid']),
                new UUID($like['useruuid'])
            );
        }

        return $likes;
    }

    public function checkUserLikeForPostExists(string $postUuid, string $userUuid): void
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM likes WHERE postuuid = :postuuid AND useruuid = :useruuid'
        );

        $statement->execute([
            ':postuuid' => $postUuid,
            ':useruuid' => $userUuid
        ]);

        $isExisted = $statement->fetch();

        if ($isExisted) {
            throw new LikeAlreadyExists('User like for this post already exist');
        }
    }
}
