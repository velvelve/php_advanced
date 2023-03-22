<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\UsersRepository;

use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Person\Name;
use PDO;
use Psr\Log\LoggerInterface;

class SqliteUsersRepository implements UserRepositoryInterface
{

    public function __construct(
        private PDO $pdo,
        private LoggerInterface $logger,
    ) {
    }

    public function save(User $user): void
    {

        $statement = $this->pdo->prepare(
            'INSERT INTO users (uuid, username, password, first_name, last_name)
            VALUES (:uuid, :username, :password, :first_name, :last_name)
            ON CONFLICT (uuid) DO UPDATE SET first_name = :first_name, last_name = :last_name'
        );
        $statement->execute([
            ':uuid' => $user->getUuid(),
            ':username' => $user->getUsername(),
            ':password' => $user->getHashedPassword(),
            ':first_name' => $user->getName()->getFirstName(),
            ':last_name' => $user->getName()->getLastName(),
        ]);
        $this->logger->info("User saved: " . $user->getUuid());
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
            $this->logger->warning("User with id $uuid not found");
            throw new UserNotFoundException("User with id $uuid not found");
        }

        return new User(
            new UUID($result['uuid']),
            new Name(
                $result['first_name'],
                $result['last_name']
            ),
            $result['username'],
            $result['password']
        );
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

        return new User(
            new UUID($result['uuid']),
            new Name(
                $result['first_name'],
                $result['last_name']
            ),
            $result['username'],
            $result['password']
        );
    }
}
