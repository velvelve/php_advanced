<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\PostsRepository;

use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInteface;

class SqlitePostsRepository implements PostsRepositoryInteface
{

    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    public function save(Post $post): void
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO posts (uuid, authoruuid, title, posttext)
            VALUES (:uuid, :authoruuid, :title, :posttext)
            ON CONFLICT (uuid) DO UPDATE SET authoruuid = :authoruuid, title = :title, posttext = :posttext'
        );
        $statement->execute([
            ':uuid' => $post->getUuid(),
            ':authoruuid' => $post->getAuthor()->getUuid(),
            ':title' => $post->getTitle(),
            ':posttext' => $post->getText(),
        ]);
    }

    public function get(UUID $uuid): Post
    {
        $statement = $this->pdo->prepare(
            'SELECT * FROM posts WHERE uuid = :uuid'
        );

        $statement->execute([
            ':uuid' =>  (string) $uuid
        ]);

        return $this->getPost($statement, (string)$uuid);
    }

    public function deletePost(UUID $uuid): void
    {
        $statement = $this->pdo->prepare(
            'DELETE FROM posts WHERE uuid = :uuid'
        );

        $statement->execute([
            ':uuid' =>  (string) $uuid
        ]);
    }

    private function getPost(\PDOStatement $statement, string $postUuid): Post
    {

        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        if (false === $result) {
            throw new PostNotFoundException("Post with id $postUuid not found");
        }
        $userRepository = new SqliteUsersRepository($this->pdo);

        $user = $userRepository->get(new UUID($result['authoruuid']));

        return new Post(new UUID($result['uuid']), $user, $result['title'], $result['posttext']);
    }
}
