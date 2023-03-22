<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\UsersRepository;

use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Person\Name;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UserRepositoryInterface;

class DummyUsersRepository implements UserRepositoryInterface
{
    public function save(User $user): void
    {
    }
    public function get(UUID $uuid): User
    {
        throw new UserNotFoundException("Not found");
    }
    public function getByUsername(string $username): User
    {
        return new User(UUID::random(),  new Name("first", "last"), "user123", "test");
    }
}
