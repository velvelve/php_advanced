<?php

namespace GeekBrains\LevelTwo\Blog\Http\Actions\Post;

use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Blog\Http\Auth\IdentificationInterface;
use GeekBrains\LevelTwo\Blog\Http\ErrorResponse;
use GeekBrains\LevelTwo\Blog\Http\Request;
use GeekBrains\LevelTwo\Blog\Http\Response;
use GeekBrains\LevelTwo\Blog\Http\SuccessfullResponse;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInteface;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UserRepositoryInterface;
use GeekBrains\LevelTwo\Blog\UUID;
use Psr\Log\LoggerInterface;

class CreatePost implements ActionInterface
{
    public function __construct(
        private PostsRepositoryInteface $postsRepository,
        private IdentificationInterface $identificationInterface,
        private LoggerInterface $logger,
    ) {
    }
    public function handle(Request $request): Response
    {

        $author = $this->identificationInterface->user($request);

        $newPostUuid = UUID::random();
        try {
            $post = new Post(
                $newPostUuid,
                $author,
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text'),
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
        $this->postsRepository->save($post);
        return new SuccessfullResponse([
            'uuid' => (string)$newPostUuid,
        ]);
    }
}
