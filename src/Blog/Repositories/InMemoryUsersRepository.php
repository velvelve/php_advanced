<?php

namespace GeekBrains\LevelTwo\Blog\Repositories;

use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;

class InMemoryUsersRepository
{
    private array $users = [];

    public function save(User $user): void
    {
        $this->users[] = $user;
    }





    public function get(int $id): User
    {
        foreach ($this->users as $user) {
            if ($user->getId() === $id) {
                return $user;
            }
        }
        throw new UserNotFoundException("User with id $id not found");
    }
}
