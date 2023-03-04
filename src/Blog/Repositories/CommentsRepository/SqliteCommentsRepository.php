<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository;

use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\CommentsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;

class SqliteCommentsRepository implements CommentsRepositoryInterface
{

    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    public function save(Comment $comment): void
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO comments (uuid, authoruuid, postuuid, comment)
            VALUES (:uuid, :authoruuid, :postuuid, :comment)
            ON CONFLICT (uuid) DO UPDATE SET authoruuid = :authoruuid, postuuid = :postuuid, comment = :comment'
        );
        $statement->execute([
            ':uuid' => $comment->getUuid(),
            ':authoruuid' => $comment->getAuthor()->getUuid(),
            ':postuuid' => $comment->getPost()->getUuid(),
            ':comment' => $comment->getText(),
        ]);
    }

    public function get(UUID $uuid): Comment
    {
        $statement = $this->pdo->prepare(
            'SELECT * FROM comments WHERE uuid = :uuid'
        );

        $statement->execute([
            ':uuid' =>  (string) $uuid
        ]);

        return $this->getComment($statement, (string)$uuid);
    }

    private function getComment(\PDOStatement $statement, string $commentUuid): Comment
    {

        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        if (false === $result) {
            throw new PostNotFoundException("Post with id $commentUuid not found");
        }
        $userRepository = new SqliteUsersRepository($this->pdo);
        $user = $userRepository->get(new UUID($result['authoruuid']));

        $postRepository = new SqlitePostsRepository($this->pdo);
        $post = $postRepository->get(new UUID($result['postuuid']));
        return new Comment(new UUID($result['uuid']), $user, $post, $result['comment']);
    }
}
