<?php

namespace GeekBrains\LevelTwo\Blog\Http\Actions\User;

use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Blog\Http\ErrorResponse;
use GeekBrains\LevelTwo\Blog\Http\Request;
use GeekBrains\LevelTwo\Blog\Http\Response;
use GeekBrains\LevelTwo\Blog\Http\SuccessfullResponse;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UserRepositoryInterface;

class FindByUsername implements ActionInterface
{
    public function __construct(
        private UserRepositoryInterface $usersRepository
    ) {
    }
    public function handle(Request $request): Response
    {
        try {
            $username = $request->query('username');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
        try {
            $user = $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }
        return new SuccessfullResponse([
            'username' => $user->getUsername(),
            'name' => $user->getName()->getFirstName() . ' ' . $user->getName()->getLastName(),
        ]);
    }
}
