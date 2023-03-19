<?php

namespace GeekBrains\LevelTwo\Blog\Http\Actions\Like;

use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Exceptions\LikeAlreadyExists;
use GeekBrains\LevelTwo\Blog\Http\Actions\ActionInterface;
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
        private LikeRepositoryInterface $likeRepositoryInterface
    ) {
    }


    public function handle(Request $request): Response
    {
        try {
            $postUuid = $request->jsonBodyField('postuuid');
            $userUuid = $request->jsonBodyField('useruuid');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
        try {
            $this->likeRepositoryInterface->checkUserLikeForPostExists($postUuid, $userUuid);
        } catch (LikeAlreadyExists $e) {
            return new ErrorResponse($e->getMessage());
        }
        $newLikeUuid = UUID::random();

        $like = new Like(
            $newLikeUuid,
            new UUID($postUuid),
            new UUID($userUuid)
        );
        $this->likeRepositoryInterface->save($like);
        return new SuccessfullResponse([
            'uuid' => (string) $newLikeUuid
        ]);
    }
}
