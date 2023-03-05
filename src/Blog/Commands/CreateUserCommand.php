<?php

namespace GeekBrains\LevelTwo\Blog\Commands;

use GeekBrains\LevelTwo\Blog\Commands\Arguments;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UserRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Exceptions\CommandException;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Person\Name;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;

class CreateUserCommand
{
    private UserRepositoryInterface $usersRepository;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->usersRepository  = $userRepositoryInterface;
    }

    public function handle(Arguments $arguments): void
    {
        $username = $arguments->get('username');
        if ($this->userExists($username)) {
            throw new CommandException("User already exists: $username");
        }
        $this->usersRepository->save(new User(
            UUID::random(),
            new Name($arguments->get('first_name'), $arguments->get('last_name')),
            $username
        ));
    }

    // Преобразуем входной массив
    // из предопределённой переменной $argv
    //
    // array(4) {
    // [0]=>
    // string(18) "/some/path/cli.php"
    // [1]=>
    // string(13) "username=ivan"
    // [2]=>
    // string(15) "first_name=Ivan"
    // [3]=>
    // string(17) "last_name=Nikitin"
    // }
    //
    // в ассоциативный массив вида
    // array(3) {
    // ["username"]=>
    // string(4) "ivan"
    // ["first_name"]=>
    // string(4) "Ivan"
    // ["last_name"]=>
    // string(7) "Nikitin"
    //}

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
