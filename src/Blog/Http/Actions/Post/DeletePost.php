<?php

namespace GeekBrains\LevelTwo\Blog\Http\Actions\Post;

use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Blog\Http\ErrorResponse;
use GeekBrains\LevelTwo\Blog\Http\Request;
use GeekBrains\LevelTwo\Blog\Http\Response;
use GeekBrains\LevelTwo\Blog\Http\SuccessfullResponse;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\UUID;
use InvalidArgumentException;

class DeletePost implements ActionInterface
{
    public function __construct(
        private SqlitePostsRepository $postRepository
    ) {
    }
    public function handle(Request $request): Response
    {
        try {
            $postUuid = $request->query('uuid');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
        try {
            $this->postRepository->deletePost(new UUID($postUuid));
        } catch (UserNotFoundException | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }
        return new SuccessfullResponse([]);
    }
}
