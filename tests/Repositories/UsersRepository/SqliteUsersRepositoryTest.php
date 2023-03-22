<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\UsersRepository;

use GeekBrains\Blog\UnitTests\DummyLogger;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Person\Name;
use PHPUnit\Framework\TestCase;

class SqliteUsersRepositoryTest extends TestCase
{
    public function testItThrowsAnExceptionWhenUserNotFound(): void
    {
        $connectionMock = $this->createStub(\PDO::class);

        $statementStub = $this->createStub(\PDOStatement::class);

        $connectionMock->method('prepare')->willReturn($statementStub);

        $statementStub->method('fetch')->willReturn(false);

        $repository = new SqliteUsersRepository($connectionMock, new DummyLogger());

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('User with username Ivan not found');


        $repository->getByUsername('Ivan');
    }

    public function testItSavesUserToDatabase(): void
    {

        $connectionStub = $this->createStub(\PDO::class);

        $statementMock = $this->createMock(\PDOStatement::class);

        $statementMock
            ->expects($this->once())
            ->method('execute')
            ->with([
                ':uuid' => '123e4567-e89b-12d3-a456-426614174000',
                ':username' => 'ivan123',
                ':password' => 'test_password',
                ':first_name' => 'Ivan',
                ':last_name' => 'Nikitin',
            ]);

        $connectionStub->method('prepare')->willReturn($statementMock);

        $repository = new SqliteUsersRepository($connectionStub, new DummyLogger());

        $repository->save(
            new User(
                new UUID('123e4567-e89b-12d3-a456-426614174000'),
                new Name('Ivan', 'Nikitin'),
                'ivan123',
                'test_password'
            )
        );
    }
}
