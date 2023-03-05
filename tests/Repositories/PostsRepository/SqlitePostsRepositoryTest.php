<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\PostsRepository;

use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Person\Name;
use PHPUnit\Framework\TestCase;

class SqlitePostsRepositoryTest extends TestCase
{

    public function testItThrowsAnExceptionWhenPostNotFound(): void
    {
        $this->expectException(PostNotFoundException::class);

        $connectionMock = $this->createStub(\PDO::class);

        $statementMock = $this->createMock(\PDOStatement::class);

        $connectionMock->method('prepare')->willReturn($statementMock);

        $statementMock->method('fetch')->willReturn(false);

        $repo = new SqlitePostsRepository($connectionMock);

        $repo->get(UUID::random());
    }

    public function testItSavesPostToDatabase(): void
    {
        $connectionStub = $this->createStub(\PDO::class);

        $statementMock = $this->createMock(\PDOStatement::class);

        $connectionStub->method('prepare')->willReturn($statementMock);

        $testUuid = UUID::random();

        $statementMock
            ->expects($this->once())
            ->method('execute')
            ->with([
                ':uuid' => $testUuid,
                ':authoruuid' => $testUuid,
                ':title' => 'Title',
                ':posttext' => 'Post tetxt',
            ]);

        $repo = new SqlitePostsRepository($connectionStub);

        $user = new User($testUuid, new Name('Ivan', 'Ivanov'), 'Just user');

        $repo->save(
            new Post(
                new UUID($testUuid),
                $user,
                'Title',
                'Post tetxt'
            )
        );
    }

    public function testItGetPostByUuid(): void
    {

        $connectionStub = $this->createStub(\PDO::class);

        $statementMock = $this->createMock(\PDOStatement::class);

        $testUuid = UUID::random();

        $statementMock->method('fetch')->willReturn([
            'uuid' => $testUuid,
            'authoruuid' => $testUuid,
            'title' => 'Title',
            'posttext' => 'Post text',
            'username' => 'ivan123',
            'first_name' => 'Ivan',
            'last_name' => 'Ivanov'
        ]);

        $connectionStub->method('prepare')->willReturn($statementMock);

        $repo = new SqlitePostsRepository($connectionStub);

        $post = $repo->get($testUuid);

        $this->assertSame((string) $testUuid, (string)$post->getUuid());
    }
}
