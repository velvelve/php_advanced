<?php

namespace GeekBrains\LevelTwo\Blog\Commands;

use GeekBrains\LevelTwo\Blog\Commands\Arguments;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UserRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Exceptions\CommandException;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Person\Name;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use Psr\Log\LoggerInterface;

class CreateUserCommand
{
    private UserRepositoryInterface $usersRepository;
    private LoggerInterface $logger;

    public function __construct(UserRepositoryInterface $userRepositoryInterface, LoggerInterface $logger)
    {
        $this->usersRepository  = $userRepositoryInterface;
        $this->logger = $logger;
    }

    public function handle(Arguments $arguments): void
    {

        $this->logger->info("Create user command started");
        $username = $arguments->get('username');

        if ($this->userExists($username)) {
            throw new CommandException("User already exists: $username");
        }

        $user = User::createFrom(
            $username,
            $arguments->get('password'),
            new Name(
                $arguments->get('first_name'),
                $arguments->get('last_name')
            )
        );

        $this->usersRepository->save($user);
        $this->logger->info("User created: " . $user->getUuid());
    }

    private function userExists(string $username): bool
    {
        try {
            // Пытаемся получить пользователя из репозитория
            $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException) {
            return false;
        }
        return true;
    }
}
