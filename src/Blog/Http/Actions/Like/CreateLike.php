<?php

namespace GeekBrains\LevelTwo\Blog\Http\Actions\Like;

use GeekBrains\LevelTwo\Blog\Exceptions\AuthException;
use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Exceptions\LikeAlreadyExists;
use GeekBrains\LevelTwo\Blog\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Blog\Http\Auth\TokenAuthenticationInterface;
use GeekBrains\LevelTwo\Blog\Http\ErrorResponse;
use GeekBrains\LevelTwo\Blog\Http\Request;
use GeekBrains\LevelTwo\Blog\Http\Response;
use GeekBrains\LevelTwo\Blog\Http\SuccessfullResponse;
use GeekBrains\LevelTwo\Blog\Like;
use GeekBrains\LevelTwo\Blog\Repositories\LikeRepository\LikeRepositoryInterface;
use GeekBrains\LevelTwo\Blog\UUID;

class CreateLike implements ActionInterface
{

    public function __construct(
        private LikeRepositoryInterface $likeRepositoryInterface,
        private TokenAuthenticationInterface $authentication,
    ) {
    }


    public function handle(Request $request): Response
    {
        try {
            $postUuid = $request->jsonBodyField('postuuid');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $author = $this->authentication->user($request);
        } catch (AuthException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $this->likeRepositoryInterface->checkUserLikeForPostExists($postUuid, $author->getUuid());
        } catch (LikeAlreadyExists $e) {
            return new ErrorResponse($e->getMessage());
        }
        $newLikeUuid = UUID::random();

        $like = new Like(
            $newLikeUuid,
            new UUID($postUuid),
            $author->getUuid()
        );

        $this->likeRepositoryInterface->save($like);
        return new SuccessfullResponse([
            'uuid' => (string) $newLikeUuid
        ]);
    }
}
