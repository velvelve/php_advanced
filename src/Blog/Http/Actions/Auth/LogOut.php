<?php

namespace GeekBrains\LevelTwo\Blog\Http\Actions\Auth;

use DateTimeImmutable;
use GeekBrains\LevelTwo\Blog\Exceptions\AuthException;
use GeekBrains\LevelTwo\Blog\Exceptions\AuthTokenNotFoundException;
use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Blog\Http\Auth\TokenAuthenticationInterface;
use GeekBrains\LevelTwo\Blog\Http\ErrorResponse;
use GeekBrains\LevelTwo\Blog\Http\Request;
use GeekBrains\LevelTwo\Blog\Http\Response;
use GeekBrains\LevelTwo\Blog\Http\SuccessfullResponse;
use GeekBrains\LevelTwo\Blog\Repositories\AuthTokenRepository\AuthTokensRepositoryInterface;

class LogOut implements ActionInterface
{

    private const HEADER_PREFIX = 'Bearer ';


    public function __construct(
        private AuthTokensRepositoryInterface $authTokensRepository,
        private TokenAuthenticationInterface $authentication,
    ) {
    }
    public function handle(Request $request): Response
    {

        try {
            $this->authentication->user($request);
        } catch (AuthException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $header = $request->header('Authorization');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }

        if (!str_starts_with($header, self::HEADER_PREFIX)) {
            throw new AuthException("Malformed token: [$header]");
        }

        $token = mb_substr($header, strlen(self::HEADER_PREFIX));

        try {
            $authToken = $this->authTokensRepository->get($token);
        } catch (AuthTokenNotFoundException) {
            throw new AuthException("Bad token: [$token]");
        }

        $authToken->setExpiresOn(new DateTimeImmutable("now"));

        $this->authTokensRepository->save($authToken);

        return new SuccessfullResponse([
            'token' => (string)$authToken,
        ]);
    }
}
