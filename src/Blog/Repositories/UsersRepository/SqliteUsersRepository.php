<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\UsersRepository;

use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Person\Name;

class SqliteUsersRepository implements UserRepositoryInterface
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(User $user): void
    {

        $statement = $this->pdo->prepare(
            'INSERT INTO users (uuid, username, first_name, last_name)
            VALUES (:uuid, :username, :first_name, :last_name)
            ON CONFLICT (uuid) DO UPDATE SET first_name = :first_name, last_name = :last_name'
        );
        $statement->execute([
            ':uuid' => $user->getUuid(),
            ':username' => $user->getUsername(),
            ':first_name' => $user->getName()->getFirstName(),
            ':last_name' => $user->getName()->getLastName(),
        ]);
    }

    public function get(UUID $uuid): User
    {
        $statement = $this->pdo->prepare(
            'SELECT * FROM users WHERE uuid = :uuid'
        );
        $statement->execute([
            ':uuid' => (string) $uuid
        ]);
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        if (false === $result) {
            throw new UserNotFoundException("User with id $uuid not found");
        }

        return new User(new UUID($result['uuid']), new Name($result['first_name'], $result['last_name']), $result['username']);
    }

    public function getByUsername(string $username): User
    {
        $statement = $this->pdo->prepare(
            'SELECT * FROM users WHERE username = :username'
        );
        $statement->execute([
            ':username' =>  $username
        ]);
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        if (false === $result) {
            throw new UserNotFoundException("User with username $username not found");
        }

        return new User(new UUID($result['uuid']), new Name($result['first_name'], $result['last_name']), $result['username']);
    }
}
